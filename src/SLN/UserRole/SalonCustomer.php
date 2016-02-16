<?php

class SLN_UserRole_SalonCustomer
{
	private $plugin;

	private $role;
	private $displayName;
	private $capabilities = array(
		'read' => true,
		'edit_posts' => false,
		'delete_posts' => false,
		'publish_posts' => false,
		'upload_files' => false,
		'manage_salon' => false
	);

	public function __construct(SLN_Plugin $plugin, $role, $displayName)
	{
		$this->plugin = $plugin;
		$this->role = $role;
		$this->displayName = $displayName;
		remove_role($this->role);
		add_role( $this->role, $this->displayName, $this->capabilities);
	}

	/**
	 * @return SLN_Plugin
	 */
	protected function getPlugin()
	{
		return $this->plugin;
	}
}
