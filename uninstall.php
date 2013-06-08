<?php

if(defined(WP_UNINSTALL_PLUGIN))
{exit;}

delete_option('kush_mn_db_version');
delete_option('kush_mn_num_news');
delete_option('kush_mn_show_lborder');
delete_option('kush_mn_show_linkclean');
delete_option('kush_mn_parse_html');

global $wpdb;
   $table_name = $wpdb->prefix . "kushmicronews"; 
   
   $query = "DROP TABLE $table_name";
	
	$wpdb->query($query);
	
?>