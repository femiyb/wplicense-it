<?php
 
function devllo_events_load_stripe_scripts() {
   wp_register_script( 'stripe-payment', WPLIT_INCLUDES_URI. 'payment/stripe-payment.js');	
	global $stripe_options;
 
	$stripe_options_mode = get_option('wplit-stripe-settings-test-mode');
	// check to see if we are in test mode
	if(isset($stripe_options_mode) && $stripe_options_mode) {
		$publishable = get_option('wplit-stripe-settings-test-pk');
	} else {
		$publishable = get_option('wplit-stripe-settings-live-pk');
	}

	// wp_enqueue_script('jquery');

	// wp_register_script('stripe', 'https://js.stripe.com/v2/'); 
	// wp_register_script('stripe3', 'https://js.stripe.com/v3/'); 

	wp_localize_script('stripe-payment', 'stripe_vars', array(
			'publishable_key' => $publishable,
		)
	);
}
add_action( 'init', 'devllo_events_load_stripe_scripts' );
?>

