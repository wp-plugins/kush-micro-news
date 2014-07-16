<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

//delete all registered options
delete_option('kush_mn_db_version');
delete_option('kush_mn_num_news');
delete_option('kush_mn_show_lborder');
delete_option('kush_mn_show_linkclean');
delete_option('kush_mn_parse_html');
delete_option('kush_mn_widget_name');
delete_option('kush_mn_color_title');
delete_option('kush_mn_color_text');
delete_option('kush_mn_color_link');

global $wpdb;
	$table_name = $wpdb->prefix . "kushmicronews"; 
   
	$query = "DROP TABLE IF EXISTS $table_name";
	
	$wpdb->query($query);
	
?>