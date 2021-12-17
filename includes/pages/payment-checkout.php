<?php

class WPLit_Payment_Function {

    public function __construct() {
        add_shortcode( 'wplit-checkout', array($this, 'wplit_stripe_payment_function') );

        // Only load these scripts if cookie is set
        if (isset($_COOKIE['wplit_product_id'] )){

        include( WPLIT_INCLUDES_DIR. '/payment/stripe-payment.php'); 
        add_action( 'wp_enqueue_scripts', array( $this, 'wplit_stripe_payment_js' ) );

        }

    }

    function wplit_stripe_payment_js() { 

        // wp_enqueue_script( 'jquery',  'http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js');

        wp_enqueue_script( 'stripe',  'https://js.stripe.com/v3/');
        wp_enqueue_script( 'stripe-payment', WPLIT_INCLUDES_URI. 'payment/stripe-payment.js');	
        wp_enqueue_style( 'stripe-payment-css', WPLIT_INCLUDES_URI. 'payment/stripe-payment.css');	
        wp_enqueue_style( 'bootstrap', WPLIT_INCLUDES_URI. 'assets/css/bootstrap.css');    
    }

    public function wplit_stripe_payment_function($content=null) {

       

        ob_start();

        if (isset($_COOKIE['wplit_product_id'] )){

            $value = $_COOKIE['wplit_product_id']; 

            
            $wplit_product_price = get_post_meta( $value, 'wplit_product_price', true );

            $wplit_product_description = get_post_meta( $value, 'wplit_product_description', true );

            $calc_wplit_product_price = $wplit_product_price;
            $wplit_product_title = get_the_title( $value );
            ?>
            <div class="col-md-4 order-md-2 mb-4">
                <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                    <h6 class="my-0"><?php echo $wplit_product_title; ?></h6>
                    <small class="text-muted"><?php echo $wplit_product_description; ?></small>
                    </div>
                    <span class="text-muted">$<?php echo $wplit_product_price; ?></span>
                </li>
                </ul>

            </div>

            <div class="col-md-8 order-md-1">
            <form action="" method="POST" id="payment-form">
            <div class="form-group">
                <fieldset>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="wplit_billing_user_first"><?php _e('First Name'); ?></label>
                            <input name="wplit_billing_user_first" id="wplit_billing_user_first" type="text" class="wplit_billing_user_first" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="wplit_billing_user_last"><?php _e('Last Name'); ?></label>
                            <input name="wplit_billing_user_last" id="wplit_billing_user_last" type="text" class="wplit_billing_user_last" required/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wplit_billing_company"><?php _e('Company'); ?></label>
                        <div class="input-group">
                        <input name="wplit_billing_company" id="wplit_billing_company" type="text" class="wplit_billing_company form-control"/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wplit_billing_address"><?php _e('Address'); ?></label>
                        <input name="wplit_billing_address" id="wplit_billing_address" type="text" class="wplit_billing_address form-control"/>
                    </div>
                    <div class="row">
                    <div class="col-md-6 mb-3">
                            <label for="wplit_billing_state"><?php _e('State'); ?></label>
                            <div class="input-group">
                            <input name="wplit_billing_state" id="wplit_billing_state" type="text" class="wplit_billing_state"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="wplit_billing_city"><?php _e('City'); ?></label>
                            <div class="input-group">
                            <input name="wplit_billing_city" id="wplit_billing_city" type="text" class="wplit_billing_city"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6 mb-3">
                            <label for="wplit_billing_country"><?php _e('Country'); ?></label>
                            <div class="input-group">
                            <input name="wplit_billing_countryl" id="wplit_billing_countryl" type="text" class="wplit_billing_countryl"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="wplit_billing_postal"><?php _e('Postal Code'); ?></label>
                            <input name="wplit_billing_postal" id="wplit_billing_postal" type="text" class="wplit_billing_postal"/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wplit_billing_phone"><?php _e('Phone'); ?></label>
                        <div class="input-group">
                        <input name="wplit_billing_phone" id="wplit_billing_phone" type="text" class="wplit_billing_phone"/>
                        </div>
                    </div>
                    
                    <input type="hidden" name="service_id" value="6" />
                    <label for="card-element">
                    </label>
                    <div id="card-element" class="form-control">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <!-- Used to display Element errors. -->
                    <div id="card-errors" role="alert"></div>
                </fieldset>
            </div>

            <div class="row">
                <!-- /.col -->
                <div class="col-8">            
                </div>
                <div class="col-4">
                <input type="hidden" name="action" value="stripe"/>
                <input type="hidden" name="redirect" value="<?php  echo get_permalink(); ?>"/>
                <input type="hidden" name="stripe_nonce" value="<?php  echo wp_create_nonce('stripe-nonce'); ?>"/>
                <input class="btn btn-primary" type="submit" id="stripe-pay stripe-submit" name="wplitsubmit" value="Submit">
                </div>
                <!-- /.col -->
            </div>
            
            </form>
            </div>
            <?php

        } else {
            ?>
        <div class="error"> <?php esc_html_e('You have not selected any product to purchase.', 'wp-license-it'); ?> </div>
         <?php

        }

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }


}

new WPLit_Payment_Function();