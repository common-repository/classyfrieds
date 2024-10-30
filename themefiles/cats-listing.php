<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
 
// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');

global $current_user;
wp_get_current_user();
global $wp;
get_header(); 

 ?>
<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/layouts/'. $cfo['css_color'] . '.css', __FILE__) ; ?>" />

<div class='classy_listing_main' <?PHP if ($cfo['cf_sidebars'] == 'off' ) echo "style='width:100%'";?> >
<?PHP if ($cfo[show_menu] == "yes") include('menu.php');  ?>
<div class="classy_header"><?PHP echo $wp->query_vars["classycats"]; ?></div>

<?PHP
// start the loop
$loopcounter = 0;
if ( have_posts() ) : while ( have_posts() ) : the_post();

if (function_exists('classyfrieds_ads')) classyfrieds_ads('main',$loopcounter); // run extension cf_ads if available

include('template_vars.php');
$layout = file_get_contents(plugin_dir_path(__FILE__) . 'layouts/'. $cfo['listing_layout_taxonomy'] . '.php' );
// $layout = file_get_contents(plugins_url('/layouts/'. $cfo['listing_layout_taxonomy'] . '.php', __FILE__) ); // updated due to http://wordpress.org/support/topic/plugin-classyfrieds-failed-to-open-stream-error-why?replies=3
$layout = str_replace($taggit,$tagto,$layout);
echo $layout;

$loopcounter++;
endwhile; 
else : 
echo $cfl[no_listings];
endif; 
?>

</div>

<?php 
if ($cfo['cf_sidebars'] != 'off' )
include('add_listing_sidebar.php'); 
?>

<div style="clear:both"></div>
<?php get_footer(); ?>