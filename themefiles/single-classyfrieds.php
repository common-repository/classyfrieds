<script language="javascript"> 
function toggle(id) {
	var ele = document.getElementById(id);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
  	}
	else {
		ele.style.display = "block";
	}
} 
</script>

<?php
/**
 * The Template for displaying all single posts.
 */
// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');
global $current_user;
wp_get_current_user();

get_header(); 
?>
<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/layouts/'. $cfo['css_color'] . '.css', __FILE__) ; ?>" />

<div class='classy_listing_single' <?PHP if ($cfo['cf_sidebars'] == 'off' ) echo "style='width:100%'";?> >
<?PHP if ($cfo[show_menu] == "yes") include('menu.php'); ?>
				<?php while ( have_posts() ) : the_post(); 
				if (is_single() ) {if (function_exists('cf_setPostViews')) cf_setPostViews(get_the_ID()); } // count post views in single mode
				?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php echo $cfl[post_nav]; ?></h3>
						<span class="nav-previous"><?php previous_post_link(); ?></span>
						<span class="nav-next"><?php next_post_link(); ?></span>
					</nav><!-- #nav-single -->
<div class='cf_clearclear'></div>							
		
<?PHP 
include('template_vars.php');
$layout = file_get_contents(plugin_dir_path(__FILE__) . 'layouts/'. $cfo['listing_layout_single'] . '.php' );
// $layout = file_get_contents(plugins_url('/layouts/'. $cfo['listing_layout_single'] . '.php', __FILE__) ); // BUG : http://wordpress.org/support/topic/plugin-classyfrieds-failed-to-open-stream-error-why?replies=3
$layout = str_replace($taggit,$tagto,$layout);
echo $layout;
?>



<?php if ($cfo['show_comments'] == "yes") comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>

</div>

<?php 
if ($cfo['cf_sidebars'] != 'off' )
include('add_listing_sidebar.php'); 
?>

<div style="clear:both"></div>

<?php get_footer(); ?>
