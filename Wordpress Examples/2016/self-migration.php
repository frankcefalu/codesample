<?php

/**
  * Plugin Name: Self - Migration
  * Plugin URI: http://www.self.com
  * Description: Uses hyperloop to help migrate the latest content to co-pilot. 
  * Version: 1.0.1
  * Author: Frank Cefalu
  */
  
 
$class_autoload = array("MegatronTransform","PhotoExporter");

for($i=0;$i<count($class_autoload);$i++){

	try {
		spl_autoload_call($class_autoload[$i]);
	} catch ( Exception $e ) {
	   trigger_error('Unable to load class:'.$e, E_USER_WARNING);
	   add_settings_error('invalid-class','',$e,'error');
	}	
}




// Instantiate Classes
$megatron_markdown = new MegatronMarkdownify();
$megatron_transform = new MegatronTransform();
$hyperloop_exporter = new PhotoExporter();


// Definitions
$permalink = $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
$migration_types = array("image" => "Image Migration");
 

/*
*
* SHORTCODES
*
*/


function self_migration_json_shortcodes() {
	add_shortcode('instagram', 'self_migration_json_shortcode_instagram');
	add_shortcode('tweet', 'self_migration_json_shortcode_twitter');
	add_shortcode('youtube', 'self_migration_json_shortcode_video');
	add_shortcode('iframe', 'self_migration_json_shortcode_iframe');
}

function self_migration_json_shortcode_twitter($atts, $content, $shortcode) {
	return sprintf('[#twitter: {%s}]',$atts['url']);
}

function self_migration_shortcode_instagram($atts, $content, $shortcode) {
	return sprintf('[#instagram: {%s}]',$atts['url']);
}

function self_migration_shortcode_iframe($atts, $content, $shortcode) {
	return sprintf('[#iframe: {%s}]',$atts['url']);
}

function self_migration_shortcode_video($atts, $content, $shortcode) {
	return sprintf('[#video: {%s}]',$atts['url']);
}



add_action('register_json_shortcode', 'self_migration_json_shortcodes');


/*
*
* ADMIN MENU
*
*/

function self_migration_menu() {
	add_options_page( 'My Self Migration', 'My Self Migration', 'manage_options', 'self-unique-identifier', 'my_self_migration_options' );
}

function my_self_migration_options() {
	global $permalink, $migration_types;
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
		
	
	echo '<div class="wrap">';
	
	foreach($migration_types as $type => $value){
		echo sprintf('<li><a href="%s?migration_type=%s">%s</a></li>',$permalink,$type,$value);
	}
	
	echo '</div>';
}

add_action( 'admin_menu', 'self_migration_menu' );


/*
*
* Listener
*
*/

if(isset($_GET['migration_type'])){
	
	switch($_GET['migration_type']){
	
		case 'image':
				self_migration_image_migrate();
		break;
		
	}
}



/*
*
* Migration Tools
*
*/


function self_migration_image_migrate( ) {

	  $posts = get_posts(array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => 5
	));
	foreach ($posts as $post) {
		$photo = new PhotoExporter($post);
		try {
			$photo->export();
			$photo->updateOriginal();
		} catch ( Exception $e ) {
			error_log($e);
		}
	}
}

