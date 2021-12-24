<?php

class WP_License_It_Product_Post {

    public function __construct(){
        add_action( 'init', array($this, 'wp_license_it_post_type'), 0);
        add_action( 'admin_init', array($this, 'add_wp_license_it_caps'));

    }

		
	// Register Custom Post Type
	function wp_license_it_post_type() {
		$labels = array(
			'name'                  => _x( 'License It Products', 'Post Type General Name', 'wp_license_it' ),
			'singular_name'         => _x( 'License Products', 'Post Type Singular Name', 'wp_license_it' ),
			'menu_name'             => __( 'License Products', 'wp_license_it' ),
			'name_admin_bar'        => __( 'License Product', 'wp_license_it' ),
			'archives'              => __( 'Product Archives', 'wp_license_it' ),
			'attributes'            => __( 'Product Attributes', 'wp_license_it' ),
			'parent_item_colon'     => __( 'Parent Product:', 'wp_license_it' ),
			'all_items'             => __( 'All Products', 'wp_license_it' ),
			'add_new_item'          => __( 'Add New Product', 'wp_license_it' ),
			'add_new'               => __( 'Add New License Product', 'wp_license_it' ),
			'new_item'              => __( 'New License Product', 'wp_license_it' ),
			'edit_item'             => __( 'Edit License Product', 'wp_license_it' ),
			'update_item'           => __( 'Update Product', 'wp_license_it' ),
			'view_item'             => __( 'View Product', 'wp_license_it' ),
			'view_items'            => __( 'View Products', 'wp_license_it' ),
			'search_items'          => __( 'Search Product', 'wp_license_it' ),
			'not_found'             => __( 'Not found', 'wp_license_it' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp_license_it' ),
			'featured_image'        => __( 'Featured Image', 'wp_license_it' ),
			'set_featured_image'    => __( 'Set featured image', 'wp_license_it' ),
			'remove_featured_image' => __( 'Remove featured image', 'wp_license_it' ),
			'use_featured_image'    => __( 'Use as featured image', 'wp_license_it' ),
			'insert_into_item'      => __( 'Insert into Post', 'wp_license_it' ),
			'uploaded_to_this_item' => __( 'Uploaded to this post', 'wp_license_it' ),
			'items_list'            => __( 'Products list', 'wp_license_it' ),
			'items_list_navigation' => __( 'Products list navigation', 'wp_license_it' ),
			'filter_items_list'     => __( 'Filter posts list', 'wp_license_it' ),
		);
		$capabilities = array(
			'edit_post'             => 'edit_wp_license_it_product',
			'read_post'             => 'read_wp_license_it_product',
			'delete_post'           => 'delete_wp_license_it_product',
			'edit_posts'            => 'edit_wp_license_it_product',
			'edit_others_posts'     => 'edit_others_wp_license_it_product',
			'publish_posts'         => 'publish_wp_license_it_product',
			'read_private_posts'    => 'read_private_wp_license_it_product',

		);
		$args = array(
			'label'                 => __( 'License Products', 'wp_license_it' ),
			'description'           => __( 'WP License It Products', 'wp_license_it' ),
			'labels'                => $labels,
			'supports'           	=> array( 'title', 'editor', 'author', 'thumbnail' ),
			'rewrite'				=> array('slug' => 'license'),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-plugins-checked',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
			'show_in_rest'          => true,
		);
		register_post_type( 'wplit_product', $args );

	}

	function add_wp_license_it_caps() {
		// gets the administrator role
		$admin = get_role( 'administrator' );

		$admin->add_cap( 'edit_wp_license_it_product' ); 
		$admin->add_cap( 'edit_wp_license_it_products' ); 
		$admin->add_cap( 'edit_others_wp_license_it_product' ); 
		$admin->add_cap( 'publish_wp_license_it_product' ); 
		$admin->add_cap( 'read_wp_license_it_product' ); 
		$admin->add_cap( 'read_private_wp_license_it_product' ); 
		$admin->add_cap( 'delete_wp_license_it_product' ); 
	}

}

new WP_License_It_Product_Post();