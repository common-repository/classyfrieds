<?PHP
// generic functions for classyfrieds

function classyfrieds_adminreports_cron() {
// this is ran regularly for maintenance and notification
$cfo = get_option('classyfrieds_options');	 // pull options
$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " executing " . $cfo['log_to_admin'] . " maintenance routine. <br/>";

// check classyfrieds table for post id's to expire
global $wpdb;
$expid = $wpdb->get_results( "SELECT post_id FROM " .$wpdb->prefix . "classyfrieds WHERE time_expire < " . time() );
foreach ($expid as $exid)
	{
	wp_delete_post( $exid->post_id, TRUE );
	$cfo['error_log'] .= "POST EXPIRED : I deleted post ID $exid->post_id <br>";	
	}

// to be sure run query on post meta too	
$args = array
	(
	'post_type' => 'product',
	'meta_query' => array
		(
		array(
			'key' => '_cf_expire',
			'value' => time(),
			'compare' => '<',
			'type' => 'NUMERIC'
			),
		)
	);
$the_query = new WP_Query( $args );
// mini Loop
while ( $the_query->have_posts() ) : $the_query->the_post();
	wp_delete_post( get_the_ID(), TRUE );
	$cfo['error_log'] .= "POST EXPIRED (loop 2) : I deleted post ID ". get_the_ID() . " <br>";	
endwhile;
// Reset Post Data
wp_reset_postdata();

// chop error log to max-lenght
$cfo['error_log'] = substr($cfo['error_log'],0,1000)."....";
// and save it
update_option('classyfrieds_options', $cfo);

$bits = $cfo['error_log'];

// mail a daily report if needed	
if (!empty($bits))
	{
	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
	$to = $cfo['admin_email'];
	$subject = 'your classyfrieds-plugin has notifications !';
	$message = "this is the classyfrieds-plugin from your blog : " . get_option('blogname') . " with a notification !
	<br />
	<strong>$bits<br />http://classyfrieds.com</strong>
	";
	$headers = "From: wordpress classyfrieds <$to> \r\n";
	$mail = wp_mail($to, $subject, $message, $headers);
	}
}

// post counters
// function to display number of posts.
function cf_getPostViews($postID){
    $count_key = '_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Views";
    }
    return $count.' Views';
}

// function to count views.
function cf_setPostViews($postID) {
    $count_key = '_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// create shortcodes
function classyfr_func( $atts ) {
	extract( shortcode_atts( array('type' => 'list', 'bar' => 'something else', ), $atts ) );
	if ($type == 'list') include( plugin_dir_path(__FILE__) . 'themefiles/__listingloop.php') ;
	if ($type == 'addlist') include( plugin_dir_path(__FILE__) . 'themefiles/page-classyfried_add_listing.php') ;	
}
add_shortcode( 'classyfr', 'classyfr_func' );

// get full html content
function get_content($more_link_text = '(more...)', $stripteaser = 0, $more_file = '')
{
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}