<?php

class SLN_UserRole_SalonStaff
{
    private $plugin;

    private $role;
    private $displayName;
    private $capabilities = array(
        'manage_salon' => true,
        'edit_posts' => true
    );

    public function __construct(SLN_Plugin $plugin, $role, $displayName)
    {
        $adminRole = get_role('administrator');
        $adminRole->add_cap('manage_salon');
        foreach (array(
                     SLN_Plugin::POST_TYPE_ATTENDANT,
                     SLN_Plugin::POST_TYPE_SERVICE,
                     SLN_Plugin::POST_TYPE_BOOKING,
                 ) as $k) {
            foreach (get_post_type_object($k)->cap as $v) {
                if (!isset($role->capabilities[$v])) {
                    $adminRole->add_cap($v);
                }
                $this->capabilities[$v] = true;
            }
        }
        $this->plugin = $plugin;
        $this->role = $role;
        $this->displayName = $displayName;

        $roles = wp_roles();
        if ($roles->get_role($this->role)) {
            $roles->remove_role($this->role);
        }
        $roles->add_role($this->role, $this->displayName, $this->capabilities);
    }

    /**
     * @return SLN_Plugin
     */
    protected function getPlugin()
    {
        return $this->plugin;
    }

    public static function addCapabilitiesForRole($role) {
        self::changeCapabilitiesForRole($role, true);
    }

    public static function removeCapabilitiesFoRole($role) {
        self::changeCapabilitiesForRole($role, false);
    }

    private static function changeCapabilitiesForRole($role, $canManage) {
        $roleObj = get_role($role);
        if ($canManage) {
            $roleObj->add_cap('manage_salon');
            $roleObj->add_cap('manage_options');
        } else {
            $roleObj->remove_cap('manage_salon');
            $roleObj->remove_cap('manage_options');
        }

        foreach (array(
            SLN_Plugin::POST_TYPE_ATTENDANT,
            SLN_Plugin::POST_TYPE_SERVICE,
            SLN_Plugin::POST_TYPE_BOOKING,
        ) as $k) {
            foreach (get_post_type_object($k)->cap as $v) {
                if ($canManage) {
                    $roleObj->add_cap($v);
                }
                else {
                    if ($v !== 'read') {
                        $roleObj->remove_cap($v);
                    }
                }
            }
        }
    }
}
