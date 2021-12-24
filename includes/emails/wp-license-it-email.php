<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_License_It_Email {

    public function __construct() {
        add_action('wplit_after_add_license', array($this, 'wplit_customer_email'));
        add_action('wplit_after_add_license', array($this, 'wplit_admin_email'));
        add_filter('wp_mail_content_type', array($this, 'wplit_format_email'));        
    }


    public function wplit_admin_email(){

        global $wp_locale;
        if (isset($_COOKIE['wplit_product_id'] )){
            $product_id = intval($_COOKIE['wplit_product_id']); 
        }

        $product_name = get_the_title($product_id);
        $product_link = get_permalink( $product_id );

        $admin_email = get_bloginfo('admin_email');

        $to = $admin_email;
        $subject = 'New Product Purchased'; 
        $message = 'A user has purchased <a href="' . $product_link . ' ">' . $product_name . '</a>.';

        wp_mail( $to, $subject, $message );

    }

    public function wplit_customer_email(){
        global $wp_locale;

        $current_user = wp_get_current_user();

        if (isset($_COOKIE['wplit_product_id'] )){
            $product_id = intval($_COOKIE['wplit_product_id']); 
        }

        $product_name = get_the_title($product_id);

        $site_name = get_bloginfo( 'name' );
        $url = get_permalink(get_option('wplit-licenses-page'));   

        $to = $current_user->user_email;
        $subject = 'Your Product Purchase at ' .$site_name;
        $message = 'You have successfully purchased a license for ' .$product_name. '.<br/>';
        $message .= 'You can manage your Licenses here: <a href="' . $url . '">Manage Licenses';

        wp_mail( $to, $subject, $message );

    }

    // Format Emails
    function wplit_format_email(){
        return 'text/html';
    }
}

new WP_License_It_Email;