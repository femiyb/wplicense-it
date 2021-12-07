<?php
 
function devllo_events_load_stripe_scripts() {
    wp_register_script( 'stripe-payment', WPLIT_INCLUDES_URI. 'payment/stripe-payment.js');	
    wp_enqueue_script( 'stripe',  'https://js.stripe.com/v3/');
	global $stripe_options;
 
	$stripe_options_mode = get_option('stripe_settings_test_mode');
	// check to see if we are in test mode
	if(isset($stripe_options_mode) && $stripe_options_mode) {
		$publishable = get_option('stripe_settings_test_publishable_key');
	} else {
		$publishable = get_option('stripe_settings_live_publishable_key');
	}

	// wp_enqueue_script('jquery');

	// wp_register_script('stripe', 'https://js.stripe.com/v2/'); 
	// wp_register_script('stripe3', 'https://js.stripe.com/v3/'); 

	wp_localize_script('stripe-processing', 'stripe_vars', array(
			'publishable_key' => $publishable,
		)
	);
}
add_action( 'init', 'devllo_events_load_stripe_scripts' );
?>


/*
class Stripe_Scipts {


function wplit_load_stripe_scripts() {
    $wplit_prouct_price = get_post_meta( 34, 'wplit_product_price', true );

	//wp_register_script('stripe-processing', DEVLLO_EVENTS_REG_URI . 'payments/stripe/js/stripe-processing.js');
    wp_register_script( 'stripe-payment', WPLIT_INCLUDES_URI. 'payment/stripe-payment.js');	
    wp_enqueue_script( 'stripe',  'https://js.stripe.com/v3/');


	// global $stripe_options;
 
	//$stripe_options_mode = get_option('stripe_settings_test_mode');
	// check to see if we are in test mode
	// if(isset($stripe_options_mode) && $stripe_options_mode) {
	// 	$publishable = get_option('stripe_settings_test_publishable_key');
	// } else {
	// 	$publishable = get_option('stripe_settings_live_publishable_key');
	// }

    $publishable = 'pk_test_51K1Z01H0HAiCT4KEXYDxu10I6WCtxiiUQaBX8cgnaCPLaqnhckuSBugfBtjq7B15WPA54wFOjqTg4tMmTMtZ9TRP00LGfyNUz7';


	// wp_enqueue_script('jquery');

	// wp_register_script('stripe', 'https://js.stripe.com/v2/'); 
	// wp_register_script('stripe3', 'https://js.stripe.com/v3/'); 

	wp_localize_script('stripe-payment', 'stripe_vars', array(
			'publishable_key' => $publishable,
		)
	);


    return $wplit_prouct_price;
}
// add_action( 'init', 'wplit_load_stripe_scripts' );

}
?>
