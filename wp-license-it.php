<?php
/*
    Plugin Name: WP License It
    Plugin URI: https://devlloplugins.com/
    Description: Plugin and Theme Licensing plugin
    Author: Devllo
    Version: 0.1
    Author URI: https://devllo.com/
    Text Domain: wp-license-it
    Domain Path: /languages
*/

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Current plugin version.
 */
define( 'WP_LICENSE_IT_VERSION', '0.1' );

class WP_License_It {

    private static $_instance = null;
    public $_session = null;

    /**
     * Constructor
     */

    public function __construct(){
        $this->define_constants();
		$this->includes();
        $this->init_hooks();

        include( 'admin/wp-license-it-product-admin.php');
        include( 'admin/wp-license-it-product-post.php'); 
        include( 'admin/wp-license-it-admin-menu.php'); 
        include( 'admin/wp-license-it-settings.php');

        include( 'includes/wp-license-it-protect-file.php'); 
        include( 'includes/wp-license-it-activator.php');
        include( 'includes/wp-license-it-api.php'); 

    }


    public function includes(){

    }

    public function define_constants(){

    }

    public function init_hooks(){
    }
    


}

new WP_License_It();