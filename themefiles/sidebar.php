<?PHP
// sidebar for classyfrieds listings etc.

// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');
?>
<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/layouts/'. $cfo['css_color'] . '.css', __FILE__) ; ?>" />
<div class="classy_sidebar">

<?PHP if ( ! dynamic_sidebar( 'classybar' ) ) : ?>
<div class="classy_box">
	<?php get_search_form(); ?>
</div>

<div class="classy_box">
	<h3 class="widget-title"><?php _e( 'Archives', 'twentyten' ); ?></h3>
<?php wp_get_archives( 'type=monthly' ); ?>
</div>
<?php endif; ?>

</div>

		