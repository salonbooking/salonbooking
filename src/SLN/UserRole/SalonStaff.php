<?php

class SLN_UserRole_SalonStaff
{
    private $plugin;

    private $role;
    private $displayName;
    private $capabilities = array(
        'manage_salon' => true,
    );

    public function __construct(SLN_Plugin $plugin, $role, $displayName)
    {
        foreach (array(
                     SLN_Plugin::POST_TYPE_ATTENDANT,
                     SLN_Plugin::POST_TYPE_SERVICE,
                     SLN_Plugin::POST_TYPE_BOOKING,
                 ) as $k) {

            foreach(get_post_type_object($k)->cap as $v){
                $this->capabilities[$v] = true;
            }
        }
        $this->plugin = $plugin;
        $this->role = $role;
        $this->displayName = $displayName;
        remove_role($this->role);
        add_role($this->role, $this->displayName, $this->capabilities);
    }

    /**
     * @return SLN_Plugin
     */
    protected function getPlugin()
    {
        return $this->plugin;
    }
}
