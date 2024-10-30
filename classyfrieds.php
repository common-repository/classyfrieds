<?php
/*
Plugin Name: * classyfrieds
Plugin URI: http://wordpressprogrammeurs.nl
Description: A directory listing slash classified system for wordpress. visit <a href='http://classyfrieds.com'>classyfrieds.com</a> for live demonstrations, expansion packs, forums and howto's
Author: pete scheepens
Author URI: http://wordpressprogrammeurs.nl
Version: 3.2
*/

// pull default or previously set options
$cfo = get_option('classyfrieds_options');
$cfo['version'] = '3.2';
update_option('classyfrieds_options', $cfo);


// initialise
include_once('init.php');
include_once('functions.php');

// initialising
 // register_activation_hook(__FILE__,'classyfrieds_database'); // removed in V 2.6
register_activation_hook( __FILE__, 'classyfrieds_initialise' );
register_activation_hook(__FILE__, 'classy_activation');
register_deactivation_hook(__FILE__, 'classy_deactivation');

// call thickbox support
//add_action( 'wp_enqueue_scripts', 'add_thickbox' ); // removed in V2.9 in favor of css3

// create menu's
add_action('admin_menu', 'classyfrieds_menu',6);
function classyfrieds_menu() {
	add_menu_page('classyfrieds menu', 'classyfrieds', 'administrator', 'classyfrieds_mainmenu', 'classyfrieds_settings', plugins_url('/images/menu-icon.png', __FILE__) );
	add_submenu_page( 'classyfrieds_mainmenu', 'layouts & styles', 'layouts & styles', 'administrator', 'classyfrieds_sub_listings','classyfrieds_listings' );
	add_submenu_page( 'classyfrieds_mainmenu', 'uploadform', 'uploadform', 'administrator', 'classyfrieds_sub_uform','classyfrieds_uform' );
	add_submenu_page( 'classyfrieds_mainmenu', 'upgrades', 'upgrades', 'administrator', 'classyfrieds_upgrade','classyfrieds_upgrade' );
	add_submenu_page( 'classyfrieds_mainmenu', 'language edit', 'language edit', 'administrator', 'classyfrieds_advanced','classyfrieds_lang_edit' );
	add_submenu_page( 'classyfrieds_mainmenu', 'notifications', 'notifications', 'administrator', 'classyfrieds_notify','classyfrieds_notify' );
	}
	
		function classyfrieds_settings(){
		include_once('menus/main.php');
		}

		function classyfrieds_listings(){
		include_once('menus/listings.php');
		}
		
		function classyfrieds_uform(){
		include_once('menus/uploadform.php');
		}
		
		function classyfrieds_upgrade(){
		include_once('menus/upgrade.php');
		}
		
		function classyfrieds_lang_edit(){
		include_once('menus/language_editor.php');
		}
		
		function classyfrieds_notify(){
		include_once('menus/notification.php');
		}

// add our default backup css
 add_action( 'wp_enqueue_scripts', 'classyfrieds_css' );
 function classyfrieds_css() {
        wp_register_style( 'classy_css', plugins_url('/css/classyfrieds.css', __FILE__) );
        wp_enqueue_style( 'classy_css' );
    }
 
//Template REDIRECT
add_action("template_redirect", 'classyfrieds_redirect');

// create custom posts
add_action( 'init', 'create_classyfrieds_type', 0 );

// set up a CRON schedule for maintenance and notifications
add_action('wp', 'classy_activation');

add_action('admin_notify_cron', 'classyfrieds_adminreports_cron');

// activate cron
if ( !wp_next_scheduled( 'admin_notify_cron' ) ) {
$cfo = get_option('classyfrieds_options');
if ($cfo['log_to_admin'] == 'daily') {wp_schedule_event(time(), 'daily', 'admin_notify_cron');}
elseif ($cfo['log_to_admin'] == 'weekly') {wp_schedule_event(time(), 'weekly', 'admin_notify_cron');}
else 
	{ // do nothing - user requested notifications off
	}
}

// add a 'weekly' option to CRON
function cf_add_weekly( $schedules ) {
    $schedules['weekly'] = array(
        'interval' => 604800, //that's how many seconds in a week, for the unix timestamp
        'display' => __('weekly')
    );
    return $schedules;
}
add_filter('cron_schedules', 'cf_add_weekly');


function classy_deactivation() {
	// copy language file to uploads (saves data through upgrade)
	$uploads = wp_upload_dir();
	if (!is_dir($uploads['basedir'] . "/classyfrieds/language/")) 
		{ 
		if (!mkdir($uploads['basedir'] . "/classyfrieds/language",  0775, TRUE)) 
			{
			$cfo = get_option('classyfrieds_options');
			$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " ERROR ! cannot create backup dir for language file. Language changes will be lost on plugin upgrade. <br/>";
			update_option('classyfrieds_options', $cfo);
			}
		}
	$clangtheme = plugin_dir_path( __FILE__ )  . '/themefiles/language/lang_CUSTOMIZED.php' ;
	$clangbackup = $uploads['basedir'] . "/classyfrieds/language/lang_CUSTOMIZED.php" ;	
	if (file_exists($clangtheme)) 
		{ 
		if (!copy($clangtheme, $clangbackup)) 
			{ 
			$cfo = get_option('classyfrieds_options');
			$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " ERROR ! cannot copy language file to backup dir. Language changes will be lost on plugin upgrade. <br/>";
			update_option('classyfrieds_options', $cfo);
			} 
		}
	// de-activate cron
	wp_clear_scheduled_hook('classy-C');
}

function classy_activation() {
	// copy files back from uploads
	$uploads = wp_upload_dir();
	$clangtheme = plugin_dir_path( __FILE__ )  . '/themefiles/language/lang_CUSTOMIZED.php' ;
	$clangbackup = $uploads['basedir'] . "/classyfrieds/language/lang_CUSTOMIZED.php" ;	
	if (file_exists($clangbackup)) 
		{ 
		if (!copy($clangbackup, $clangtheme)) 
			{ 
			$cfo = get_option('classyfrieds_options');
			$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " ERROR ! could not copy language file back to active system. Re-build custom language if needed. <br/>";
			update_option('classyfrieds_options', $cfo);
			} 
		}		
}


function cf_admin_notice(){
		$struct = get_option('permalink_structure');
		if (empty($struct))
		echo '<div class="updated">
		   <p> Classyfrieds notice ! Permalinks appear to be set to Default. You need to set permalinks to a custom value for classyfrieds to work correctly !. ("Post name" or /%postname%/ permalinks are recommended) currently : '. $struct . '</p>
		</div>';

}
add_action('admin_notices', 'cf_admin_notice');
?>
