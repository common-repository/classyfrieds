<!-- main classyfrieds loaded -->
<?php
/**
 * The main listing template file for classyfrieds.
 */
 
 // load general options
 get_header(); 
 
$cfo = get_option('classyfrieds_options');
 ?>
 
 <!-- main classyfrieds initialize -->
<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/layouts/'. $cfo['css_color'] . '.css', __FILE__) ; ?>" />
 
<div class='classy_listing_main' <?PHP if ($cfo['cf_sidebars'] == 'off' ) echo "style='width:100%'";?> >

<?PHP include('__listingloop.php'); ?>

<!-- main classyfrieds end content - go sidebar -->
</div><!-- #content -->

<?php 
if ($cfo['cf_sidebars'] != 'off' )
include('add_listing_sidebar.php'); 
?>

<div style="clear:both"></div>
<?php get_footer(); ?>