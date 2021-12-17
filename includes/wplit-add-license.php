<?php

class WPLit_Add_License {

    public function __construct() {

    }

    function wplit_add_order(){

        global $wpdb;
        global $current_user;
        wp_get_current_user();
        $user = wp_get_current_user();
        $user_id = $user->ID;

        if (isset($_COOKIE['wplit_product_id'] )){
        $product_id = $_COOKIE['wplit_product_id']; 
        }
        
        $today = date("Ymd");
        $order_number = strtoupper('#' .$today. '-' .wp_generate_password( 3, false, false ));
        // $order_number = strtoupper(wp_generate_password( 10, false, false ));

        $amount = get_post_meta( $product_id, 'wplit_product_price', true );
        $total_amount = $amount;
        $order_email = (string) $current_user->user_email;

        if(isset($_POST['action']) && $_POST['action'] == 'stripe' && wp_verify_nonce($_POST['stripe_nonce'], 'stripe-nonce')) {
            $first_name = sanitize_text_field($_POST['wplit_billing_user_first']) ;
            $last_name = sanitize_text_field($_POST['wplit_billing_user_last']) ;
            $billing_company = sanitize_text_field($_POST['wplit_billing_company']) ;
            $billing_address = sanitize_text_field($_POST['wplit_billing_address']) ;
            $billing_state = sanitize_text_field($_POST['wplit_billing_state']) ;
            $billing_city = sanitize_text_field($_POST['wplit_billing_city']) ;
            $billing_country = sanitize_text_field($_POST['wplit_billing_countryl']) ;
            $postal_code = sanitize_text_field($_POST['wplit_billing_postal']) ;
            $billing_phone = sanitize_text_field($_POST['wplit_billing_phone']) ;
            $discount_code = '';
            $order_status = '';
        }

        $table_name = $wpdb->prefix . 'wplit_orders';
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'product_id' => $product_id,
                'order_number' => $order_number,
                'order_sub_total' => $amount,
                'order_total' => $total_amount,
                'order_email' => $order_email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'billing_company' => $billing_company,
                'billing_address' => $billing_address,
                'billing_state' => $billing_state,
                'billing_city' => $billing_city,
                'billing_country' => $billing_country,
                'billing_phone' => $billing_phone,
                'postal_code' => $postal_code,
                'order_status' => $order_status,
                'discount_code' => $discount_code,
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
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
    }

    function wplit_add_license() {

        if (isset($_COOKIE['wplit_product_id'] )){

            do_action('wplit_before_add_license');

            $product_id = intval($_COOKIE['wplit_product_id']); 

            global $wpdb;
            global $current_user;
            wp_get_current_user();
            $user = wp_get_current_user();
            $user_id = $user->ID;

            // Continue if user doesn't have license for this product
            $email = (string) $current_user->user_email;
            // Nonce valid, handle data
            // $email = sanitize_text_field( $_POST['email'] );
            // $product_id = intval( $_POST['product'] );

            $product_api_key = get_post_meta( $product_id, 'wplit_product_api_key', true );

            $wplit_expire = get_post_meta( $product_id, 'wplit_expire', true );

            $wplit_expire_time = get_post_meta( $product_id, 'wplit_expire_time', true );

            if ($wplit_expire == 'yes'){
                if ($wplit_expire_time == '1-year' ){
                    $futureDate=date('Y-m-d', strtotime('+1 year'));
                    $valid_until = $futureDate;
                } elseif ($wplit_expire_time == '1-month' ){
                    $futureDate=date('Y-m-d', strtotime('+1 month'));
                    $valid_until = $futureDate;
                }
            }else{
                $valid_until = '0000-00-00 00:00:00';
            }
            
            
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

           $this->wplit_add_order();

            do_action('wplit_after_add_license');

            echo '<br/>License Purchased. Please visit Licenses page for License Information.';

        }
    }


}