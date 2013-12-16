<?php

/*admin*/

add_action('admin_menu', 'kush_micro_news_admin_menu');

function kush_micro_news_admin_menu() {

add_menu_page('Micro News Board', 'Micro News','administrator' , 'micro-news', 'micro_news_html_page','','6.1995');

add_submenu_page('micro-news','Micro News Add New', 'Add New', 'administrator','micro-news-new', 'micro_news_html_page_add_new');
add_submenu_page('micro-news','Micro News Settings', 'Settings', 'administrator','micro-news-config', 'micro_news_config_page');
}

function micro_news_config_page(){
$what='';
if(isset($_POST['valSub']))
	{if(isset($_POST['numPost']))
		{
		if($_POST['numPost']!='')
			{$num=sanitize($_POST['numPost']);
			 update_option( "kush_mn_num_news",$num);
			 
			 $what='Changes Saved !';
			}
		else
			echo _e('<h3>No of post cannot left blank.</h3>');
			
		}
	if(isset($_POST['chkBorder']))
		{if($_POST['chkBorder']==true)
			update_option( "kush_mn_show_lborder",'true');		
			
		$what='Changes Saved !';
		}
	else
		update_option( "kush_mn_show_lborder",'false');
		
	if(isset($_POST['chkHover']))
		{if($_POST['chkHover']==true)
			update_option( "kush_mn_show_linkclean",'true');		
		
		$what='Changes Saved !';
		}
	else	
	update_option('kush_mn_show_linkclean','false');
		
	if(isset($_POST['chkHtmlParse']))
		{if($_POST['chkHtmlParse']==true)
			update_option( "kush_mn_parse_html",'true');		
		
		$what='Changes Saved !';
		}
	else	
	update_option('kush_mn_parse_html','false');
	
}
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"> <br /> </div>
	<h2>Micro News Settings</h2>
	<?php echo ($what!='')?'<div class="updated"><p><strong>'.$what.'</strong></p></div>':''; ?>
	<br/>
	<form action="" method="post">
		<h3>Functional Settings :</h3>
		<div class="options">
			<label for="numPost">Number of news to display :</label>
			<input type="text" name="numPost" value="<?php echo get_option( "kush_mn_num_news");?>"/>
			<h6 style="display:inline-block;margin:0;">(via kush_micro_news_output() function)</h6>
		</div>
		<h3>Display Settings :</h3>
		<div class="options">
			<label for="chkBorder">Enable colorful borders:</label>
			<input type="checkbox" name="chkBorder" value="true" <?php $sBor=get_option('kush_mn_show_lborder');if($sBor=='true'){echo 'checked';}?>/>
		</div>
		<div class="options">
			<label for="chkHover">Enable link hover effect:</label>
			<input type="checkbox" name="chkHover" value="true" <?php $lHov=get_option('kush_mn_show_linkclean');if($lHov=='true'){echo 'checked';}?>/>
		</div>
		<h3>Input Settings :</h3>
		<div class="options">
			<label for="chkHtmlParse">Allow HTML parsing while adding news:</label>
			<input type="checkbox" name="chkHtmlParse" value="true" <?php $lHov=get_option('kush_mn_parse_html');if($lHov=='true'){echo 'checked';}?>/>
			<h6 style="display:inline-block;margin:0;">(Try not to use improper markup if HTML parsing is enabled otherwise it could break up your whole site.)</h6>
		</div>
		<br/><br/>
		<input type="hidden" name="valSub" value="submitted"/>
		<input type="submit" value="Save Changes" class="button-primary"/>
		<br><hr>
		Download Backup of your Micro News data : <a href="?backup=true" target="_blank" class="button-primary">Download</a>
	</form>	
	<br><br>
	Note : Give <a href="http://plugins.svn.wordpress.org/kush-micro-news/trunk/readme.txt" target="_blank">readme.txt</a> a try before experimenting stuff if you have no idea what you are doing.
	
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
		if(get_option('kush_mn_parse_html')=='false')
		{
			$title=sanitize($_POST['k_mn_title']);
			$content=sanitize($_POST['k_mn_content']);
			$link=sanitize($_POST['k_mn_link']);
		}
		else
		{
			$title=$_POST['k_mn_title'];
			$content=$_POST['k_mn_content'];
			$link=$_POST['k_mn_link'];
		}
		
			
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
			<textarea name="k_mn_content" id="content" placeholder="Excerpt">.</textarea>
		</div>
		<div class="row">
			<label for="k_mn_link">Link:</label>
			<input type="text" name="k_mn_link" id="link" placeholder="Link Reference"/>
		</div>
		<input type="hidden" name="k_mn_hidden" value="Y">
		
		<div class="row">
			<input type="submit" value="<?php _e('Add New');?>" class="button-primary"/>
		</div>
	</div>
	</form>
	
</div>
<?php	
}

?>