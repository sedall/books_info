<?php
/*
Plugin Name: Cyan Test
Plugin URI: https://ircyan.com/
Description: یک پلاگین بصورت آزمایشی
Version: 1.2
Author: @Cyanteam
Author URI: https://itcyan.com
*/
/* create database tables */
load_plugin_textdomain('cyan', true, basename( dirname( __FILE__ ) ) . '/languages' );
   global $wpdb;
$table_name = $wpdb->prefix . "books_info";
$my_products_db_version = '1.0.0';
$charset_collate = $wpdb->get_charset_collate();

if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

    $sql = "CREATE TABLE $table_name (
            `ID` bigint(20) UNSIGNED NOT NULL  AUTO_INCREMENT,
            `post_ID` text NOT NULL,
            `isbn` text NOT NULL,
            PRIMARY KEY  (ID)
    )$charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'my_db_version', $my_products_db_version );
}
require_once(plugin_dir_path( __FILE__ ) . 'list_table.php' );
require_once(plugin_dir_path( __FILE__ ) . 'posttype/book.php' );
?>
