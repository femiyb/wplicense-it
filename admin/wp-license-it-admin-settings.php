<?php

/**
 * WP License It Admin Settings Page
 *
 * @link       https://devllo.com/
 * @since      1.0.0
 *
 * @package    WP_License_It
 * @subpackage WP_License_It/includes
 */


/**
 * Prevent loading file directly
 */

defined( 'ABSPATH' ) || exit;

class WP_License_It_Admin_Settings {

    public function __construct(){
        // add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'init_settings'  ) );
    }

    public function init_settings() {
		register_setting( 'wplit-settings-pages', 'wplit-checkout-page' );
		register_setting( 'wplit-settings-pages', 'wplit-licenses-page' );
		register_setting( 'wplit-settings-payment', 'wplit-stripe-settings-test-mode' );
		register_setting( 'wplit-settings-payment', 'wplit-stripe-settings-live-pk' );
		register_setting( 'wplit-settings-payment', 'wplit-stripe-settings-live-sk' );
		register_setting( 'wplit-settings-payment', 'wplit-stripe-settings-test-pk' );
		register_setting( 'wplit-settings-payment', 'wplit-stripe-settings-test-sk' );
    }


    function enqueue_scripts() {   

        // This should only load on this settings page
        // wp_enqueue_style( 'dashboard-css', WPLIT_ADMIN_URI. 'assets/dashboard.css');

    }


    public static function wplit_settings_page(){ ?>
        <div style="width: 100%;">
		</div>
        <?php
		$active_tab = "wplit_settings";
		$tab = filter_input(
			INPUT_GET, 
			'tab', 
			FILTER_CALLBACK, 
			['options' => 'esc_html']
		);
        if( isset( $tab ) ) {
            $active_tab = $tab;
		  } ?>
		<div class="wrapper">
             <!-- SideBar Starts Here -->
		  <?php // Add Sidebar
		 wplit_admin_sidebar (); 
		  ?>
        <!-- SideBar Ends -->

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle d-flex">
          	<!-- LOAD LOGO HERE -->

            	</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
					
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="message-square"></i>

								</div>
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3"><?php _e('Settings', 'devllo-events'); ?></h1>

					<div class="row">
						<div class="col-md-3 col-xl-3">

							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0"></h5>
								</div>

								<div class="list-group list-group-flush" role="tablist">
                                <a class="list-group-item list-group-item-action <?php echo $active_tab == 'wplit_settings' ? 'nav-tab-active' : ''; ?>" data-bs-toggle="list" href="?page=wplit-admin-settings&tab=wplit_settings&post_type=wplit_product" role="tab">
									<?php _e('General', 'devllo-events'); ?></a>

                                    <a class="list-group-item list-group-item-action <?php echo $active_tab == 'wplit_settings_pages' ? 'nav-tab-active' : ''; ?>" data-bs-toggle="list" href="?page=wplit-admin-settings&tab=wplit_settings_pages&post_type=wplit_product" role="tab">
									<?php _e('Pages', 'devllo-events'); ?></a>

                                    <a class="list-group-item list-group-item-action <?php echo $active_tab == 'wplit_settings_payment' ? 'nav-tab-active' : ''; ?>" data-bs-toggle="list" href="?page=wplit-admin-settings&tab=wplit_settings_payment&post_type=wplit_product" role="tab">
									<?php _e('Payment', 'devllo-events'); ?></a>

								</div>
							</div>
						</div>

						<div class="col-md-9 col-xl-9">
							<div class="tab-content">
								<div class="tab-pane fade show active" id="account" role="tabpanel">

			
								<div class="card" style="max-width: none;">
										<div class="card-header">

											<h5 class="card-title mb-0"></h5>
										</div>
										<div class="card-body">
										<form method="post" action="options.php">
		<?php

       
        if( $active_tab == 'wplit_settings' ) {

           
        settings_fields( 'wplit-admin-settings' );
        do_settings_sections( 'wplit-admin-settings' ); ?>
        
        <h3><?php _e('General Settings', 'wp-license-it'); ?></h3>

        <?php
        } elseif( $active_tab == 'wplit_settings_pages' ) {
			// Pages Settings
            settings_fields( 'wplit-settings-pages' );
            do_settings_sections( 'wplit-settings-pages' );
            ?>

            <h3><?php _e('Pages', 'wp-license-it'); ?></h3>
            <table class="table">
			<?php
			 function devllo_post_exists_by_slug( $post_slug ) {
				$loop_posts = new WP_Query( array( 'post_type' => 'page', 'post_status' => 'any', 'name' => $post_slug, 'posts_per_page' => 1, 'fields' => 'ids' ) );
				return ( $loop_posts->have_posts() ? $loop_posts->posts[0] : false );
			} ?>
			<tr>
			<th style="text-align: left;"><?php _e('Checkout Page', 'devllo-events'); ?></th>
			<td>
			<em><?php _e('This page should include the shortcode', 'devllo-events');?> [wplit-checkout]<br/></em>
			<?php   
			wp_dropdown_pages( array( 
				'name' => 'wplit-checkout-page', 
				'show_option_none' => __( '— Select —' ), 
				'option_none_value' => '0', 
				'selected' => get_option('wplit-checkout-page'),
				));
			?>
			</td>
			<td><a target="_blank" href="<?php echo esc_url( get_permalink(get_option('wplit-checkout-page')) ); ?>" class="button button-secondary"><?php _e('View Page', 'devllo-events'); ?></a></td>
			</tr>

			<tr>
			<th style="text-align: left;"><?php _e('License Page', 'devllo-events'); ?></th>
			<td>
			<em><?php _e('This page should include the shortcode', 'devllo-events');?> [wplit-licenses]<br/></em>
			<?php   
			wp_dropdown_pages( array( 
				'name' => 'wplit-licenses-page', 
				'show_option_none' => __( '— Select —' ), 
				'option_none_value' => '0', 
				'selected' => get_option('wplit-licenses-page'),
				));
			?>
			</td>
			<td><a target="_blank" href="<?php echo esc_url( get_permalink(get_option('wplit-licenses-page')) ); ?>" class="button button-secondary"><?php _e('View Page', 'devllo-events'); ?></a></td>
			</tr>

			</table>

            <?php

        } elseif( $active_tab == 'wplit_settings_payment' ) {
            settings_fields( 'wplit-settings-payment' );
            do_settings_sections( 'wplit-settings-payment' );
			
            ?>

        <h3><?php _e('Payment', 'wp-license-it'); ?></h3>

        <h4><?php _e('Stripe API Settings', 'wp-license-it'); ?></h4>

		<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Test Mode?', 'wp-license-it'); ?>
						</th>
						<td>
							<input id="wplit-stripe-settings-test-mode" name="wplit-stripe-settings-test-mode" type="checkbox" value="1" <?php checked(1, get_option('wplit-stripe-settings-test-mode')); ?> />
							<label class="description" for="wplit-stripe-settings-test-mode"><?php _e('Check this to switch Stripe to test mode.', 'devllo_events_stripe'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>	
 
			<h3 class="title"><?php _e('API Keys', 'wp-license-it'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Live Publishable Key', 'wp-license-it'); ?>
						</th>
						<td>
							<input id="wplit-stripe-settings-live-pk" name="wplit-stripe-settings-live-pk" type="text" class="regular-text" value="<?php echo get_option('wplit-stripe-settings-live-pk'); ?>"/>
							<label class="description" for="wplit-stripe-settings-live-pk"><?php _e('Paste your live publishable key.', 'wp-license-it'); ?></label>
						</td>
					</tr>

					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Live Secret Key', 'wp-license-it'); ?>
						</th>
						<td>
							<input id="wplit-stripe-settings-live-sk" name="wplit-stripe-settings-live-sk" type="password" class="regular-text" value="<?php echo get_option('wplit-stripe-settings-live-sk'); ?>"/>
							<label class="description" for="wplit-stripe-settings-live-sk"><?php _e('Paste your live secret key.', 'wp-license-it'); ?></label>
						</td>
					</tr>
				
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Test Publishable Key', 'wp-license-it'); ?>
						</th>
						<td>
							<input id="wplit-stripe-settings-test-pk" name="wplit-stripe-settings-test-pk" class="regular-text" type="text" value="<?php echo get_option('wplit-stripe-settings-test-pk'); ?>"/>
							<label class="description" for="wplit-stripe-settings-test-pk"><?php _e('Paste your test publishable key.', 'wp-license-it'); ?></label>
						</td>
					</tr>

					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Test Secret Key', 'wp-license-it'); ?>
						</th>
						<td>
							<input id="wplit-stripe-settings-test-sk" name="wplit-stripe-settings-test-sk" type="password" class="regular-text" value="<?php echo get_option('wplit-stripe-settings-test-sk'); ?>"/>
							<label class="description" for="wplit-stripe-settings-test-sk"><?php _e('Paste your test secret key.', 'wp-license-it'); ?></label>
						</td>
					</tr>

				<!--	<tr valign="top">	
						<th scope="row" valign="top">
							<?php// _e('Currency', 'wp-license-it'); ?>
						</th>
						<td>
						<?php // $options = get_option( 'wplit_payemnet_currency' ); ?>
						<select name="wplit_payemnet_currency">
                <option value="" selected="selected">-</option>
				<option value='1' <?php // selected( $options['wplit_payemnet_currency'], 1 ); ?>>Option 1</option>

                </option>
            </select>
							<label class="description" for="wplit_payemnet_currency"><?php// _e('Paste your test secret key.', 'wp-license-it'); ?></label>
						</td>
					</tr> -->
				</tbody>
			</table>	

        <?php
		}
		submit_button();

        ?>
         </form>										
	</div>
								</div>
								</div>
			
							</div>
						</div>
					</div>

				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a href="https://devlloplugins.com/" class="text-muted"><strong>Devllo Plugins</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="https://devlloplugins.com/support/">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://devlloplugins.com/documentations/events-by-devllo-documentation/">Help Center</a>
								</li>
                                <!--
								<li class="list-inline-item">
									<a class="text-muted" href="#">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Terms</a>
								</li>
                                    -->
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
    <?php 
    }


}

new WP_License_It_Admin_Settings();