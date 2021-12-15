<?php

class WPLit_Render_Product { 
    public function __construct(){
        add_shortcode( 'wplit-product', array($this, 'render_product') );
        add_action( 'template_redirect', array ($this, 'handle_add_license'));
        add_action('init', array ($this, 'set_cookie'));

        include( WPLIT_INCLUDES_DIR. '/wplit-add-license.php'); 

    }

    public function render_product($attr){

        // ob_start();
        $args = shortcode_atts( array(
     
            'id' => '',
            'download_text' => 'Download',
 
        ), $attr );

        $wplit_product_name = get_post_meta( $args['id'], 'wplit_product_name', true );
        $wplit_product_version = get_post_meta( $args['id'], 'wplit_product_version', true );

        $wplit_product_api_key = get_post_meta( $args['id'], 'wplit_product_api_key', true );

        
        $wplit_tested_wp_version = get_post_meta( $args['id'], 'wplit_tested_wp_version', true );

        $wplit_required_wp_version = get_post_meta( $args['id'], 'wplit_required_wp_version', true );

        $wplit_product_description = get_post_meta( $args['id'], 'wplit_product_description', true );

        $wplit_product_logo_url = get_post_meta( $args['id'], 'wplit_product_logo_url', true );

        $wplit_product_price = get_post_meta( $args['id'], 'wplit_product_price', true );

        $product_id = $args['id'];

        if ($wplit_product_api_key){
    
            $output = ' 
            <div class="wplit_render_product_container" style="display: grid;">
                <div style="width: 100%;">
                    <div class="" style="width: 20%; float: left;">
                        <div class="logo">
                            <img src="' . $wplit_product_logo_url. '">
                        </div>
                    </div>

                    <div class="" style="width: 70%">
                        <div class="desciption">'
                            . $wplit_product_name .'<br/>' . $wplit_product_description .
                        '</div>
                    </div>
                </div>


                <div style="width: 100%;">
                    <div class="" style=" float: left;">
                        <div class="download">
                            <form action="" method="POST">
                            <input type="hidden" name="id" value="' . $product_id . '" />

                            <input type="submit" name="add-license" class="button button-primary" value="' . $args['download_text'] . '" />
                            </form>
                        </div>
                    </div>

                    <div class="" style="">
                        <div class="info">
                            Version: ' .$wplit_product_version . '
                            <br/>Price: $ ' . $wplit_product_price . '
                        </div>
                    </div>
                </div>
            </div>';
            do_action('wplit-notices');
            
            return $output;
        }


    }



    public function set_cookie() {
        if(isset($_POST['add-license'])) {
            $product_id = intval( $_POST['id'] );
            setcookie("wplit_product_id", $product_id, time()+60, '/');

        }
    }


   function wplit_license_purchased_notice(){
        echo "<div>You already have a License for this product.</div>";

    }

    function wplit_logged_out_notice(){
        echo "<div>Please Log In to Purchase License.</div>";

    }
    /**
    * Handler for the wplit_add_license action (submitting
    * the "Add New License" form). 
    */
    public function handle_add_license() {

        global $wpdb;
        global $current_user;
        wp_get_current_user();
        $user = wp_get_current_user();
        $user_id = $user->ID;

        if(isset($_POST['add-license'])) {
            $product_id = intval( $_POST['id'] );

            // Is User logged in
            if ( is_user_logged_in() ) {

                
                $id = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT id FROM " . $wpdb->prefix . "wplit_product_licenses 
                        WHERE user_id = %d AND product_id = %d LIMIT 1",
                        $user_id, $product_id
                )
                );

                // Check if user already has license for this product
                if ( $id > 0 )
                {
                    add_action('wplit-notices', array($this, 'wplit_license_purchased_notice'));
                } else {

                    // Check if this is a free product

                    $wplit_product_price = get_post_meta( $product_id, 'wplit_product_price', true );

                    if (isset($wplit_product_price) && $wplit_product_price > 0){

                        // Redirect to Checkout page for paid products

                        $url = get_permalink(get_option('wplit-checkout-page'));   

                        if($url){
                        wp_redirect( $url );
                        }

                    } else {
                    // If event is free, go to add license function
                    $WPLit_Add_License = new WPLit_Add_License;
                    $WPLit_Add_License->wplit_add_license();
                    }     
                }
            }
            else
            {
                add_action('wplit-notices', array($this, 'wplit_logged_out_notice'));
            }
        }

    }
}


new WPLit_Render_Product;

