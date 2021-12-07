<?php
/*
    Plugin Name: WP License It
    Plugin URI: https://femiyb.com/
    Description: Plugin and Theme Licensing plugin
    Author: Devllo
    Version: 0.1
    Author URI: https://femiyb.com/
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

        include( 'includes/pages/wplit-render-product.php'); 
        include( 'includes/pages/view-licenses.php'); 
        include( 'includes/pages/payment-checkout.php'); 

    }


    public function includes(){

    }

    public function define_constants(){
        define( 'WPLIT_URI', plugin_dir_url( __FILE__ ) );
        define( 'WPLIT_DIR', dirname(__FILE__) );

        define( 'WPLIT_ADMIN_URI', WPLIT_URI . 'admin/' );
        define( 'WPLIT_INCLUDES_URI', WPLIT_URI . 'includes/' );


        define( 'WPLIT_ADMIN_DIR', WPLIT_DIR . '/admin' );
        define( 'WPLIT_INCLUDES_DIR', WPLIT_DIR . '/includes' );


    }

    public function init_hooks(){
    }
    


}

new WP_License_It();