<?php

class WPLit_Payment_Function {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'wplit_stripe_payment_js' ) );
        add_shortcode( 'wplit-checkout', array($this, 'wplit_stripe_payment_function') );

        include( WPLIT_INCLUDES_DIR. '/payment/stripe-payment.php'); 

    }

    function wplit_stripe_payment_js() { 
        wp_enqueue_script( 'jquery',  'http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js');

        wp_enqueue_script( 'stripe',  'https://js.stripe.com/v3/');
        wp_enqueue_script( 'stripe-payment', WPLIT_INCLUDES_URI. 'payment/stripe-payment.js');	
        wp_enqueue_style( 'stripe-payment-css', WPLIT_INCLUDES_URI. 'payment/stripe-payment.css');	

      //  wp_enqueue_style( 'dashboard-css', DEVLLO_EVENTS_ADMIN_URI. 'assets/css/dashboard.css');	

	
        
    }

    public function wplit_stripe_payment_function($content=null) {
        ob_start();

        if (isset($_COOKIE['wplit_product_id'] )){

            $value = $_COOKIE['wplit_product_id']; 
            

            $wplit_product_price = get_post_meta( $value, 'wplit_product_price', true );
            $calc_wplit_product_price = $wplit_product_price;
            ?>
            <form action="" method="POST" id="payment-form">
            <div class="form-group">
                <input type="hidden" name="service_id" value="6" />
                <label for="card-element">
                </label>
                <div id="card-element" class="form-control">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display Element errors. -->
                <div id="card-errors" role="alert"></div>
            </div>
            <h2><?php  _e('Submit a payment of $' . $wplit_product_price , 'devllo-events-registration'); ?></h2>

            <div class="row">
                <!-- /.col -->
                <div class="col-8">            
                </div>
                <div class="col-4">
                <input type="hidden" name="action" value="stripe"/>
                <input type="hidden" name="redirect" value="<?php  echo get_permalink(); ?>"/>
                <input type="hidden" name="stripe_nonce" value="<?php  echo wp_create_nonce('stripe-nonce'); ?>"/>
                <button class="btn btn-primary" type="submit" id="stripe-pay stripe-submit"><?php  _e('Submit Payment', 'devllo-events-registration'); ?></button>
                </div>
                <!-- /.col -->
            </div>
            
            </form>
            <?php

            
        } else {
            ?>
            <div class="error"> You have not selected any product to purchase. </div>
            <?php

        }

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }


}

new WPLit_Payment_Function();