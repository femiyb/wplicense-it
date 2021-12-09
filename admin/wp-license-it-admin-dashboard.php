<?php

/**
 * WP License It Admin Dashboard Page
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

class WP_License_It_Admin_Dashboard {
    public function __construct(){
		add_action( 'admin_init', array( $this, 'init_settings'  ) );
    }

    public function init_settings() {
    }

    public static function wplit_dashboard_page() { ?>
        <div style="width: 100%;">
		</div>
        <?php
		$active_tab = "wplit_admin";
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

					<h1 class="h3 mb-3"><?php _e('Dashboard', 'devllo-events'); ?></h1>

					<div class="row">
						<div class="col-md-3 col-xl-3">

							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0"></h5>
								</div>

								<div class="list-group list-group-flush" role="tablist">
                                <a class="list-group-item list-group-item-action <?php echo $active_tab == 'wplit_settings' ? 'nav-tab-active' : ''; ?>" data-bs-toggle="list" href="?page=wplit-admin-dashboard&tab=wplit_admin&post_type=wplit_product" role="tab">
								<?php _e('Dashboard', 'devllo-events'); ?></a>

                                <a class="list-group-item list-group-item-action <?php echo $active_tab == 'wplit_admin_licenses' ? 'nav-tab-active' : ''; ?>" data-bs-toggle="list" href="?page=wplit-admin-dashboard&tab=wplit_admin_licenses&post_type=wplit_product" role="tab">
								<?php _e('Licenses', 'devllo-events'); ?></a>

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

                                                
                                            if( $active_tab == 'wplit_admin' ) {
                                            
                                            settings_fields( 'wplit-admin-dashboard' );
                                            do_settings_sections( 'wplit-admin-dashboard' );

                                            // Get Number of Products
                                            $count_products = wp_count_posts( $post_type = 'wplit_product' );

                                            // Get Number of Licenses
                                            global $wpdb;
                                            $table_name = $wpdb->prefix . 'wplit_product_licenses';
                                            $count_query = "select count(*) from $table_name";
                                            $count_licenses = $wpdb->get_var($count_query);

                                            // $timestamp = date('Y-m-d G:i:s');
                                            $t = time();

                                            // $count_active = "select count(*) from $table_name WHERE `Time` < valid_until";
                                            $count_active_licenses = $wpdb->get_var("SELECT COUNT(*)
                                                                    from $table_name
                                                                    WHERE unix_timestamp(valid_until) > $t
                                                                    OR valid_until = '0000-00-00 00:00:00'
                                                                    ");

                                            ?>
                                            <h3><?php _e('Stats', ''); ?></h3>

                                            <div class="container">

                                                <div class="row">
                                                    <div class="col-sm" > 
                                                        <div><?php _e('Active Products', ''); ?></div>
                                                        <div><?php print $count_products->publish; ?></div>
                                                    </div>

                                                    <div class="col-sm"> 
                                                        <div><?php _e('Licenses Sold', ''); ?></div>
                                                        <div><?php print $count_licenses; ?></div>
                                                    </div>

                                                    <div class="col-sm"> 
                                                        <div><?php _e('Active Licenses', ''); ?></div>
                                                        <div><?php print $count_active_licenses; ?></div>
                                                    </div>
                                                </div>

                                            </div>



                                            <?php
                                            } elseif ( $active_tab == 'wplit_admin_licenses') {
                                                settings_fields( 'wplit-admin-licenses' );
                                                do_settings_sections( 'wplit-admin-licenses');

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
                                                                ?> <h3>Licenses</h3>
                                                                
                                                                <table class="table table-striped">
                                                                <thead>

                                                                    <tr>
                                                                        <th> Product Name </th>
                                                                        <th> License Email </th>
                                                                        <th> License Key </th>
                                                                        <th> License Status </th>

                                                                    </tr>
                                                                </thead>
                                                                <?php
                                                                foreach ($result as $print){ 
                                                                    $license_key = $print->license_key;
                                                                    $license_email = $print->email;
                                                                    $valid_until = $print->valid_until;

                                                                    // Check if License is Active or Expired
                                                                    if ( $valid_until != '0000-00-00 00:00:00' && time() > strtotime($valid_until) ) {
                                                                        $license_status = 'Expired';
                                                                    } else {
                                                                        $license_status = 'Active';
                                                                    }

                                                                    $user_id = $print->user_id;
                                                                    $product_id = $print->product_id;

                                                                        echo '
                                                                        <tr style="">
                                                                            <td>'
                                                                                . get_the_title( $product_id ) .
                                                                            '</td>
                                                                            <td>'
                                                                                . $license_email .
                                                                            '</td>
                                                                            <td>'
                                                                                . $license_key .
                                                                            '</td>
                                                                            <td>'
                                                                                . 
                                                                                $license_status  .
                                                                            '</td>
                                                                        </tr> ';    
                                                                }
                                                                ?> </table> <?php
                                            }
                                             ?>
                                        </form>
		<?php


        
    }
}

new WP_License_It_Admin_Dashboard();