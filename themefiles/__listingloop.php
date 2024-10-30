<?PHP 
// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');

 // change loop
$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array( 'post_type' => 'classyfrieds', 'posts_per_page' => $cfo[listings_per_page],'paged' =>  $page );
$loop = new WP_Query( $args );

global $current_user;
wp_get_current_user();
?><link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/layouts/'. $cfo['css_color'] . '.css', __FILE__) ; ?>" /><?PHP
if ($cfo[show_menu] == "yes") include('menu.php'); 

// start the loop
$loopcounter = 0;
if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();

if (function_exists('classyfrieds_ads')) classyfrieds_ads('main',$loopcounter); // run extension cf_ads if available

include('template_vars.php');
$layout = file_get_contents(plugin_dir_path(__FILE__) . 'layouts/'. $cfo['listing_layout'] . '.php' );
$layout = str_replace($taggit,$tagto,$layout);
echo $layout;

$loopcounter++;
endwhile; 
else : 
echo $cfl[no_listings];
endif; 
 
if(function_exists('wp_pagenavi')) {wp_pagenavi( array('query' =>$loop ) ); }
else { echo "page : "; $big = 999999999; echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages ) );
}
?>