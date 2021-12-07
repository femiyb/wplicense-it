<?php

class WP_License_It_Settings {
    public function __construct() {

       //  add_action('admin_post_license_manager_add_license', array($this, 'handle_add_license'));
    }

    public static function wplit_settings_page() {
        // Get License Products List
        global $wpdb;
        global $current_user;

        $products = get_posts(
            array(
                'orderby' => 'post_title',
                'order' => 'ASC',
                'post_type' => 'wplit_product',
                'nopaging' => true,
                'suppress_filters' => true,
                

            )
        ); ?>

        <?php
        /**
        * The view for the admin page used for adding a new license.
        *
        * @package    Wp_License_Manager
        * @subpackage Wp_License_Manager/admin/partials
        */

        $result =  $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wplit_product_licenses",
            );
            ?> <h3>Licenses</h3> <?php
            foreach ($result as $print){ 
                $license_key = $print->license_key;
                $license_email = $print->email;

                $user_id = $print->user_id;
                $product_id = $print->product_id;
                     echo '
                     <div style="display: grid;">
                        <div> Product: '
                            . get_the_title( $product_id ) .
                        '</div>
                        <div> License Key: '
                            . $license_key .
                        '</div>
                        <div> License Email: '
                            . $license_email .
                        '</div>
                    </div>';    
            }
        ?>
        <!-- <div class="wrap">


           <div id="icon-edit" class="icon32 icon32-posts-post"></div>
            <h2><?php // _e( 'Add New License', '' ); ?></h2>
            <p>
                <?php
                    // $instructions = 'Use this form to manually add a product license. '
                    //     . 'After completing the process, make sure to pass the license key to the customer.';
                    // _e( $instructions, '');
                ?>
            </p> -->

            <!-- <form action="<?php //echo admin_url( 'admin-post.php' ); ?>" method="post">
                <?php //wp_nonce_field( 'wp-license-manager-add-license', 'wp-license-manager-add-license-nonce' ); ?>
                <input type="hidden" name="action" value="license_manager_add_license">
                <table class="form-table">
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="email">
                                <?php// _e( 'Email', '' ); ?>
                                <span class="description"><?php // _e( '(required)', ''); ?></span>
                            </label>
                        </th>
                        <td>
                            <input name="email" type="text" id="email" aria-required="true">
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="email">
                                <?php // _e( 'Product', '' ); ?>
                                <span class="description"><?php // _e( '(required)', '' ); ?></span>
                            </label>
                        </th>
                        <td>
                            <select name="product" id="product" aria-required="true">
                                <?php // foreach ( $products as $product ) : ?>
                                    <option value="<?php // echo $product->ID; ?>">
                                    <?php // echo $product->post_title; ?></option>
                                <?php // endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="valid_until">
                                <?php // _e( 'Valid until',''); ?>
                            </label>
                        </th>
                        <td>
                            <input name="valid_until" type="text" id="valid_until" aria-required="false" />
                            <p class="description">
                                <?php // _e( '(Format: YYYY-MM-DD HH:MM:SS / Leave empty for infinite)','');?>
                            </p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="add-license" class="button button-primary"
                        value="<?php // _e( 'Add License', '' ); ?>" >
                </p>
            </form> -->
        </div>

        <?php
    }

    /**
    * Handler for the add_license action (submitting
    * the "Add New License" form).
    */
    function handle_add_license() {
        global $wpdb;
        if ( ! empty( $_POST )
            && check_admin_referer( 'wp-license-manager-add-license',
                'wp-license-manager-add-license-nonce' ) ) {
            // Nonce valid, handle data
            $email = sanitize_text_field( $_POST['email'] );
            $valid_until = sanitize_text_field( $_POST['valid_until'] );
            $product_id = intval( $_POST['product'] );

            $product_api_key = get_post_meta( $product_id, 'wplit_product_api_key', true );
            
            
            $license_key = wp_generate_password( 24, false, false );
            // Save data to database
            $table_name = $wpdb->prefix . 'wplit_product_licenses';
            $wpdb->insert(
                $table_name,
                array(
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
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            echo '<br/>You are Registered, you will be attending this event';

            // Redirect to the list of licenses for displaying the new license
        //  wp_redirect( admin_url( 'edit.php?post_type=wplit_product' ) );
        }

    }
}

new WP_License_It_Settings();