<?php

/*admin*/

add_action('admin_menu', 'kush_micro_news_admin_menu');

function kush_micro_news_admin_menu() {

add_menu_page('Micro News Board', 'Micro News','administrator' , 'micro-news', 'micro_news_html_page','',6);

add_submenu_page('micro-news','Micro News Add New', 'Add New', 'administrator','micro-news-new', 'micro_news_html_page_add_new');
add_submenu_page('micro-news','Micro News Settings', 'Settings', 'administrator','micro-news-config', 'micro_news_config_page');
}

function micro_news_config_page(){
	if(isset($_POST['numPost']))
		{
		if($_POST['numPost']!='')
			{$num=sanitize($_POST['numPost']);
			 update_option( "kush_mn_num_news",$num);
			}
		else
			echo _e('<h3>Input something first.</h3>');
		
		}
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"> <br /> </div>
	<h2>Micro News Settings</h2>
	<br/>
	<form action="" method="post">
		Number of news to display : <input type="text" name="numPost" value="<?php echo get_option( "kush_mn_num_news");?>"/><h6 style="display:inline-block;">(via kush_micro_news_output() function)</h6>
		<br/><br/>
		<input type="submit" value="Save Changes" class="button-primary"/>
	</form>	
		
</div>
<?php
}

function micro_news_html_page(){
//Output of prestored news	
		
	kush_micro_news_output_admin();
	
}

function micro_news_html_page_add_new(){	
$what='';	
	if(isset($_POST['k_mn_hidden']) && $_POST['k_mn_hidden']=='Y')
		{
		$title=sanitize($_POST['k_mn_title']);
		$content=sanitize($_POST['k_mn_content']);
		$link=sanitize($_POST['k_mn_link']);
		
			
		global $wpdb;			
		$table_name = $wpdb->prefix . "kushmicronews";
			
		if($title!='')
			{				
			
			$rows_affected = $wpdb->query("INSERT INTO `$table_name` (`time`,`name`,`text`,`url`)VALUES('".date('Y-m-d H:i:s')."','$title','$content','$link');");
				
			
			if($rows_affected==true)
				{?><div class="updated"><p><strong><?php _e('New Post Added.' ); ?></strong></p></div>'<?php }
			}
		else
			{$what='Don\'t you think atleast title is necessary.';}
		}
?>
<div class="wrap">
<div class="icon32" id="icon-tools"> <br /> </div>
<h2>Micro News Add New Post</h2>

<?php if($what!=''){echo '<h3>'.$what.'</h3>';}?>

	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" id="add-micro-news">
	<div>
		<div class="row">
			<label for="k_mn_title">Title:</label>
			<input type="text" name="k_mn_title" id="title" placeholder="Title of News"/>
		</div>
		<div class="row">
			<label for="k_mn_content">Content:</label>
			<textarea name="k_mn_content" id="content" >Excerpt</textarea>
		</div>
		<div class="row">
			<label for="k_mn_link">Link:</label>
			<input type="text" name="k_mn_link" id="link" placeholder="Link Reference"/>
		</div>
		<input type="hidden" name="k_mn_hidden" value="Y">
		
		<div><input type="submit" value="<?php _e('Add New');?>" class="button-primary"/></div>
	</div>
	</form>
	
</div>
<?php	
}

?>