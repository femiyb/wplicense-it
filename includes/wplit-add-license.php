<?php

class WPLit_Add_License {

    public function __construct() {

    }

    function add_license() {

        if (isset($_COOKIE['wplit_product_id'] )){

            do_action('wplit_before_add_license');

            $product_id = $_COOKIE['wplit_product_id']; 

            global $wpdb;
            global $current_user;
            wp_get_current_user();
            $user = wp_get_current_user();
            $user_id = $user->ID;

            // Continue if user doesn't have license for this product
            $email = (string) $current_user->user_email;
            // Nonce valid, handle data
            // $email = sanitize_text_field( $_POST['email'] );
            $valid_until = '0000-00-00 00:00:00';
            // $product_id = intval( $_POST['product'] );

            $product_api_key = get_post_meta( $product_id, 'wplit_product_api_key', true );
            
            
            $license_key = wp_generate_password( 24, false, false );
            // Save data to database
            $table_name = $wpdb->prefix . 'wplit_product_licenses';
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'email' => $email,
                    'license_key' => $license_key,
                    'product_api_key' => $product_api_key,
                    'valid_until' => $valid_until,
                    'created_at' => current_time( 'mysql' ),
                    'updated_at' => current_time( 'mysql' )
                ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            do_action('wplit_after_add_license');

            echo '<br/>License Purchased. Please visit Licenses page for License Information.';

        }
    }


}