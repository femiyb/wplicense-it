<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Delete the table
global $wpdb;

$table_name = $wpdb->prefix . 'wplit_product_licenses';
$order_table_name = $wpdb->prefix . 'wplit_orders';

$sql = "DROP TABLE IF EXISTS $table_name";
$ordertable = "DROP TABLE IF EXISTS $order_table_name";

$wpdb->query($sql);
$wpdb->query($ordertable);

delete_option("wplit_db_version");