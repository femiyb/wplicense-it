<?php
/*
    Plugin Name: WPLicense It
    Plugin URI: https://wplicenseit.com/
    Description: Plugin and Theme Licensing plugin
    Author: Devllo Plugins
    Version: 0.9.3
    Author URI: http://devlloplugins.com/
    Text Domain: wplicense-it
    Domain Path: /languages
*/

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Current plugin version.
 */
define( 'WPLICENSE_IT_VERSION', '0.9.3' );

if ( ! class_exists( 'WPLicense_It' ) ) {

class WPLicense_It {

    private static $_instance = null;
    public $_session = null;

    /**
     * Constructor
     */

    public function __construct(){
        register_activation_hook( __FILE__, array( 'WP_License_It_Activator', 'activate' ));

        $this->define_constants();
		$this->includes();
        $this->init_hooks();

        // Admin Files
        include( 'admin/wplicense-it-product-admin.php');
        include( 'admin/wplicense-it-product-post.php'); 
        include( 'admin/wplicense-it-admin-menu.php'); 

        // Include Files
        include( 'includes/wplicense-it-protect-file.php'); 
        include( 'includes/wplicense-it-activator.php');
        include( 'includes/wplicense-it-api.php'); 

        // Pages Files
        include( 'includes/pages/wplit-render-product.php'); 
        include( 'includes/pages/view-licenses.php'); 
        include( 'includes/pages/payment-checkout.php'); 

        // Email
        include( 'includes/emails/wplicense-it-email.php'); 

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
}

new WPLicense_It();