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
delete_option('kush_mn_head_textColor');
delete_option('kush_mn_head_highlightColor');
delete_option('kush_mn_head_back');
delete_option('kush_mn_load_nav');
delete_option('kush_mn_load_nav_swap');
delete_option('kush_mn_read_story_text');
delete_option('kush_mn_editor_access');

global $wpdb;
	$table_name = $wpdb->prefix . "kushmicronews"; 
   
	$query = "DROP TABLE IF EXISTS $table_name";
	
	$wpdb->query($query);
	
?>