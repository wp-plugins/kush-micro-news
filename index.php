<?php
/*
Plugin Name: Kush Micro News
Description: Spread the news in shortest possible way. Use links to refer data and title to concise it.
Version: 1.6.3
Author: Kush Sharma
Author Email: thekushsharma@gmail.com 
Author URI: http://softnuke.com/
Plugin URI: https://github.com/kushsharma/micro-news
Last Officially Updated: 03 June 2015
*/

define('KUSH_MICRO_NEWS_DIR', plugin_dir_path(__FILE__));
define('KUSH_MICRO_NEWS_URL', plugin_dir_url(__FILE__));
	

function kush_micronews_load_depen_reg(){
	wp_register_style( 'kush_mn_style', KUSH_MICRO_NEWS_URL.'assets/css/style.css', array(), '03032015');	
	wp_register_script( 'kush_mn_script', KUSH_MICRO_NEWS_URL.'assets/js/script.js', array('jquery'), '03032015');
	//importing stylesheet and js.
}
add_action('init','kush_micronews_load_depen_reg');
add_action('init','kush_micronews_create_shortcode');

add_action('wp_enqueue_scripts','kush_micronews_load_depen');
add_action('admin_enqueue_scripts','kush_micronews_load_depen');


function kush_micronews_load_depen(){
//load dependent libraries
	if(is_admin())
	{//load admin files only in admin
		wp_enqueue_script('kush_mn_script');
	}
	else if(get_option('kush_mn_load_nav') != "false")
	{//load only if user wants load more navigation

		wp_enqueue_script('kush_mn_script');
		
		//$arr =array('url'=>KUSH_MICRO_NEWS_URL);		
		//can let me access js var from php
		//wp_localize_script( 'kush_mn_script', 'kush_mn_script_object', $arr );

		wp_localize_script( 'kush_mn_script', 'kush_mn_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));  
	}
	
	wp_enqueue_style('kush_mn_style');
}


function kush_micronews_load(){
	
    if(is_admin()) //load admin files only in admin
        {require_once(KUSH_MICRO_NEWS_DIR.'includes/admin.php');
		 include_once(KUSH_MICRO_NEWS_DIR.'includes/backup.php');
        }
    require_once(KUSH_MICRO_NEWS_DIR.'includes/core.php');
	
}
kush_micronews_load();

function kush_micronews_create_shortcode(){
	// this will create shortcode [kushmicronews news="5" header="true"]
	
	function micronews_shortcode( $atts ) {
	    $a = shortcode_atts( array( 'news' => '5', 'header' => 'true', 'category' => 'default', 'simple' => 'false' ), $atts );

	    return kush_micro_news_output($a['news'], $a['header'], 0, $a['simple'], $a['category']);
	}
	add_shortcode( 'kushmicronews', 'micronews_shortcode' );
}


register_activation_hook(__FILE__, 'kush_micronews_activation');
register_deactivation_hook(__FILE__, 'kush_micronews_deactivation');

class KushMNWidget extends WP_Widget {

		
		function KushMNWidget() {
			// Instantiate the parent object
			parent::__construct( 'kush-micro-news', 'Kush Micro News','description=Micro news data will output where this widget resides.' );
		}

		function widget( $args, $instance ) {
			// Widget output
			extract($args, EXTR_SKIP);
			
			$no_news = empty($instance['no_news']) ? get_option( "kush_mn_num_news", '5') : apply_filters('no_news', $instance['no_news']);
			$news_cat = empty($instance['news_cat']) ? 'default' : apply_filters('news_cat', $instance['news_cat']);
			
			echo $before_widget;			
			echo kush_micro_news_output($no_news, "true", "0", "false", $news_cat);			
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			// Save widget options
			$instance = $old_instance;
		
			$instance['no_news'] = strip_tags(stripslashes($new_instance['no_news']));
			$instance['news_cat'] = strip_tags(stripslashes($new_instance['news_cat']));

			return $instance;
		}

		function form( $instance ) {
			// Output admin widget options form
			
			
		$instance = wp_parse_args((array)$instance,	array('no_news' => '5', 'news_cat' => 'default'));

		$no_news = strip_tags(stripslashes($instance['no_news']));
		$news_cat =  strip_tags(stripslashes($instance['news_cat'])); 	

		$dbver = get_option('kush_mn_db_version','0');
?>
			
			  	<div class="option">
				  <label for="no_news">
					<?php _e('Number of news'); ?>
				  </label>
				  <input type="text" id="<?php echo $this->get_field_id('no_news'); ?>" name="<?php echo $this->get_field_name('no_news'); ?>" value="<?php echo $instance['no_news']; ?>" />
				  <h5>~Default: 5</h5>
				</div>
				<?php if($dbver != '0' && $dbver != '1.0'): ?> 
					<div class="option">
					  <label for="news_cat">
						<?php _e('Category'); ?>
					  </label>
	<!-- 				  <input type="text" id="<?php echo $this->get_field_id('news_cat'); ?>" name="<?php echo $this->get_field_name('news_cat'); ?>" value="<?php echo $news_cat; ?>" />
	 -->				<select name="<?php echo $this->get_field_name('news_cat'); ?>" id="<?php echo $this->get_field_id('news_cat'); ?>">
						  <option value="default" <?php if($news_cat == "default") echo 'selected';?> >Default</option>
						  <option value="cata" <?php if($news_cat == "cata") echo 'selected';?> >CatA</option>
						  <option value="catb" <?php if($news_cat == "catb") echo 'selected';?> >CatB</option>
						  <option value="catc" <?php if($news_cat == "catc") echo 'selected';?> >CatC</option>
						  <option value="catd" <?php if($news_cat == "catd") echo 'selected';?> >CatD</option>
						</select>
					  <h5>~Default: "default"</h5>
					</div>
				<?php endif;?>
				
			
<?php
		}//form ends		
}//class ends

//this will add micro news widget along with all wordpress widgets
function kush_mn_reg_widg(){
	register_widget( "KushMNWidget" );
}
add_action('widgets_init', 'kush_mn_reg_widg');	
	

function kush_micronews_activation() {    
	//actions to perform once on plugin activation go here  	
	
	function kush_mn_install(){
		global $wpdb;
		$table_name = $wpdb->prefix . "kushmicronews"; 
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$ver=get_option("kush_mn_db_version");
		
	if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."';")!=$table_name)   
	{	
		$query = "CREATE TABLE `$table_name` (
		  id mediumint(9) PRIMARY KEY AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  name mediumtext NOT NULL,
		  text text NOT NULL,
		  url tinytext,
		  category varchar(20) DEFAULT 'default'
		);";
		
		dbDelta( $query );



	  $welcome_name = "Ms. WordPress";
	  $welcome_text = "Congratulations, you just completed the installation! Delete or edit this news.";
	  $welcome_link = "http://www.softnuke.com";
	  $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' =>$welcome_text, 'url' => $welcome_link ) );
		
	  //update charset
	  $rows_affected = $wpdb->query("ALTER TABLE `$table_name` CONVERT TO CHARACTER SET utf8");

	}
	
		//setting default values
		add_option('kush_mn_db_version', "1.1" );
		//db : 1.0 without category column

		add_option('kush_mn_num_news',"5"); 
		add_option('kush_mn_show_lborder','true');
		add_option('kush_mn_show_linkclean','true');
		add_option('kush_mn_parse_html','false');
		add_option('kush_mn_widget_name','Micro News');
		add_option('kush_mn_color_title','#0066cc');
		add_option('kush_mn_color_text','#666666');
		add_option('kush_mn_color_link','#000000');
		add_option('kush_mn_head_textColor','#FFFFFF');
		add_option('kush_mn_head_highlightColor','#808080');
		add_option('kush_mn_head_back','default');
	  	add_option('kush_mn_load_nav','false');
	  	add_option('kush_mn_load_nav_swap','true');
	  	add_option('kush_mn_editor_access','false');
	  	add_option('kush_mn_read_story_text','Read Full story &raquo;');
	}
	kush_mn_install();

}

function kush_micronews_deactivation() {    
	// actions to perform once on plugin deactivation go here	
		
	unregister_widget('KushMNWidget');
}


// misc functions
function sanitize($data){
	return htmlentities($data);	
}
?>