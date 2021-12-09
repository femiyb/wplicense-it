<?php

class WP_License_It_Admin_Menu {
    /**
	 * menus
	 * @var array
	 */
	public $_menus = array();

	/**
	 * instead new class
	 * @var null
	 */
    static $_instance = null;
    
    public function __construct() {
		// admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		include('wp-license-it-admin-sidebar.php');
		// include('wp-license-it-admin-page.php');
		include( 'wp-license-it-admin-settings.php');
		include( 'wp-license-it-admin-dashboard.php');


		
	}

     //  admin menu callback
	public function admin_menu() {
		/**
		 * menus
		 * @var array
		 */
		$menus = apply_filters( 'wp_license_it_admin_menu', $this->_menus );
		if ( $menus ) {
			foreach ( $menus as $menu ) {
				call_user_func_array( 'add_submenu_page', $menu );
			}
		}
		add_submenu_page( 'edit.php?post_type=wplit_product', __('Dashboard', ''), __('Dashboard', ''), 'manage_options', 'wplit-admin-dashboard', array( 'WP_License_It_Admin_Dashboard', 'wplit_dashboard_page'  )); 
        add_submenu_page( 'edit.php?post_type=wplit_product', __('Settings', ''), __('Settings', ''), 'manage_options', 'wplit-admin-settings', array( 'WP_License_It_Admin_Settings', 'wplit_settings_page'  )); 
		// add_submenu_page( 'edit.php?post_type=wplit_product', __('Admin', ''), __('Admin', ''), 'manage_options', 'wplit-admin-page', array( 'WPLit_Admin_Page', 'devllo_events_settings_page'  )); 

	}
	
    /**
	 * add menu item
	 *
	 * @param $params
	 */
	public function add_menu( $params ) {
		$this->_menus[] = $params;
	}

	/**
	 * instance
	 * @return object class
	 */
	public static function instance() {
		if ( self::$_instance )
			return self::$_instance;

		return new self();
	}
}

new WP_License_It_Admin_Menu();