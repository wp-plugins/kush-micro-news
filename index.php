<?php
/*
Plugin Name: Kush Micro News
Description: Spread the news in shortest possible way. Use links to refer data and title to concise it.
Version: 1.0
Author: Kush Sharma
Author Email: kush.dev@live.com 
Author URI: http://softnuke.com/
Plugin URI: https://github.com/kushsharma/micro-news
*/

define('KUSH_MICRO_NEWS_DIR', plugin_dir_path(__FILE__));
define('KUSH_MICRO_NEWS_URL', plugin_dir_url(__FILE__));
	

function kush_micronews_load_depen_reg(){
	wp_register_style( 'kush_mn_style', KUSH_MICRO_NEWS_URL.'assets/css/style.css');
	wp_register_style( 'kush_mn_style-admin', KUSH_MICRO_NEWS_URL.'assets/css/style-admin.css');
	wp_register_script( 'kush_mn_script', KUSH_MICRO_NEWS_URL.'assets/js/script.js',array('jquery'),'08052013');
	//importing stylesheet and js.
}
add_action('init','kush_micronews_load_depen_reg');

add_action('wp_enqueue_scripts','kush_micronews_load_depen');
add_action('admin_enqueue_scripts','kush_micronews_load_depen');
function kush_micronews_load_depen(){

	if(is_admin())
		{wp_enqueue_style('kush_mn_style-admin');
		wp_enqueue_script('kush_mn_script');
		
		$arr =array('url'=>KUSH_MICRO_NEWS_URL);
		
		wp_localize_script( 'kush_mn_script', 'object', $arr );
		}
	else
		wp_enqueue_style('kush_mn_style');
}


function kush_micronews_load(){
	
    if(is_admin()) //load admin files only in admin
        require_once(KUSH_MICRO_NEWS_DIR.'includes/admin.php');
        
    require_once(KUSH_MICRO_NEWS_DIR.'includes/core.php');
	
}
kush_micronews_load();

register_activation_hook(__FILE__, 'kush_micronews_activation');
register_deactivation_hook(__FILE__, 'msp_micronews_deactivation');


class KushMNWidget extends WP_Widget {

		
		function KushMNWidget() {
			// Instantiate the parent object
			parent::__construct( 'kush-micro-news', 'Kush Micro News','description=Micro news data will output where this widget resides.' );
		}

		function widget( $args, $instance ) {
			// Widget output
			extract($args, EXTR_SKIP);
			
			$no_news = empty($instance['no_news']) ? '' : apply_filters('no_news', $instance['no_news']);
			
			echo $before_widget;			
			kush_micro_news_output($no_news);			
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			// Save widget options
			$instance = $old_instance;
		
			$instance['no_news'] = strip_tags(stripslashes($new_instance['no_news']));
			
			return $instance;
		}

		function form( $instance ) {
			// Output admin widget options form
			
			
		$instance = wp_parse_args(
			(array)$instance,
				array(
					'no_news' => ''
				)
		);
			$no_news = strip_tags(stripslashes($instance['no_news']));
			
?>
			
			  	<div class="option">
				  <label for="no_news">
					<?php _e('Number of news'); ?>
				  </label>
				  <input type="text" id="<?php echo $this->get_field_id('no_news'); ?>" name="<?php echo $this->get_field_name('no_news'); ?>" value="<?php echo $instance['no_news']; ?>" />
				  <h5>~Default: 5</h5>
				</div>
			
<?php
		}
		
		
	}
function kush_mn_reg_widg(){
	register_widget( "KushMNWidget" );
}
add_action('widgets_init', 'kush_mn_reg_widg');	
	

function kush_micronews_activation() {
    
	//actions to perform once on plugin activation go here  	
	
function kush_mn_install () {
   global $wpdb;
   $table_name = $wpdb->prefix . "kushmicronews"; 
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."';")!=$table_name)   
{	
	$query = "CREATE TABLE $table_name (
	  id mediumint(9) PRIMARY KEY AUTO_INCREMENT,
	  time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	  name mediumtext NOT NULL,
	  text text NOT NULL,
	  url tinytext
	);";
	
	dbDelta( $query );



  $welcome_name = "Ms. WordPress";
  $welcome_text = "Congratulations, you just completed the installation! Delete or edit this news.";
  $welcome_link = "http://www.softnuke.com";

  $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' =>$welcome_text, 'url' => $welcome_link ) );
}
  
  add_option( "kush_mn_db_version", "1.0" );
  add_option( "kush_mn_num_news","5");
}
kush_mn_install ();	
	
}

function kush_micronews_deactivation() {    
	// actions to perform once on plugin deactivation go here	
		
	delete_option('kush_mn_db_version');
	delete_option( 'kush_mn_num_news');
	
	unregister_widget('KushMNWidget');
}




function sanitize($data){
	return htmlentities($data);	
}
?>