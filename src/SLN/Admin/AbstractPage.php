<?php

abstract class SLN_Admin_AbstractPage
{
    const PAGE = '';
    const PRIORITY = 10;
    protected $plugin;
    protected $settings;

    abstract public function show();
    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin   = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu'), static::PRIORITY, 0);
    }


    public function enqueueAssets()
    {
        SLN_Action_InitScripts::enqueueSelect2();
        SLN_Action_InitScripts::enqueueTwitterBootstrap(true);
        SLN_Action_InitScripts::enqueueAdmin();
    }

    public function admin_menu()
    {

    }

    protected function getCapability()
    {
        return apply_filters('salonviews/settings/capability', 'manage_salon');
    }

    protected function classicAdminMenu($pageTitle, $menuTitle)
    {
        $pagename = add_submenu_page(
            'salon',
            $pageTitle,
            $menuTitle,
            $this->getCapability(),
            static::PAGE,
            array($this, 'show')
        );
        add_action($pagename, array($this, 'enqueueAssets'), 0);
    }
}