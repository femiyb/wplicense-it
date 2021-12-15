<?php

class WP_License_It_Activator {

    public function __construct(){
		//add_action( 'init', array( $this, 'pluginprefix_setup_post_type' ));

		// add_action( 'admin_init', array( $this, 'activate' ) );
	}


    public static function activate() {
        $wplit_db_version = 0.9;

        $current_wplit_db_version = get_option('wplit_db_version');
        if ( !$current_wplit_db_version ) {
            $current_wplit_db_version = 0;
        }

        if (intval($current_wplit_db_version) < $wplit_db_version) {
            if(WP_License_It_Activator::create_upgrade_db()) {
                update_option('wplit_db_version', $wplit_db_version, true);
            }
        }

        // Create WP-Lit Files Directory
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/wplit-files';
        if (! is_dir($upload_dir)) {
        mkdir( $upload_dir, 0755 );
        }
        $wplit_protect_file = new WP_License_It_Protect_File();

        $wplit_protect_file->blockHTTPAccess($upload_dir, $fileType = '".zip"');

        flush_rewrite_rules(); 

    }


    private static function create_upgrade_db(){
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        global $wpdb;

        $table_name = $wpdb->prefix . 'wplit_product_licenses';
        $order_table_name = $wpdb->prefix . 'wplit_orders';


        $charset_collate = '';
        if (!empty($wpdb->charset)){
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty($wpdb->collate)){
            $charset_collate = "COLLATE {$wpdb->collate}";
        }

        $sql = "CREATE TABLE " . $table_name . "("
                . "id mediumint(9) NOT NULL auto_increment,"
                . "user_id mediumint(9) NOT NULL,"
                . "product_id mediumint(9) DEFAULT 0 NOT NULL,"
                . "license_key varchar(48) NOT NULL, "
                . "product_api_key varchar(48) NOT NULL, "
                . "email varchar(48) NOT NULL, "
                . "valid_until datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
                . "created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
                . "updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
                . "UNIQUE KEY id (id)" . ")" . $charset_collate. ";";

        $ordertable = "CREATE TABLE " . $order_table_name . "("
                . "id mediumint(9) NOT NULL auto_increment,"
                . "user_id mediumint(9) NOT NULL,"
                . "product_id mediumint(9) DEFAULT 0 NOT NULL,"
                . "order_number varchar(48) NOT NULL, "
                . "order_sub_total varchar(16) NOT NULL, "
                . "order_total varchar(16) NOT NULL, "
                . "order_email varchar(100) NOT NULL, "
                . "first_name varchar(50) NOT NULL, "
                . "last_name varchar(50) NOT NULL, "
                . "billing_company varchar(50) NOT NULL, "
                . "billing_address varchar(255) NOT NULL, "
                . "billing_state varchar(50) NOT NULL, "
                . "billing_city varchar(50) NOT NULL, "
                . "billing_country varchar(50) NOT NULL, "
                . "billing_phone varchar(32) NOT NULL, "
                . "postal_code varchar(16) NOT NULL,"
                . "order_status varchar(16) NOT NULL, "
                . "discount_code varchar(16) NOT NULL, "
                . "created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
                . "updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
                . "UNIQUE KEY id (id)" . ")" . $charset_collate. ";";


        maybe_create_table( $table_name, $sql );
        maybe_create_table( $order_table_name, $ordertable );

        return true;
    }
}

new WP_License_It_Activator();