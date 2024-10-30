<?PHP 
if ( ! is_admin() ) die ('sorry, only admins allowed');

// update settings
if (! empty($_POST) && wp_verify_nonce($_POST['cfnonce'],'classy_notes') )
	{
	$cfo = get_option('classyfrieds_options');
	$cfo['admin_email'] = $_POST['admin_email'];
	$cfo['log_to_admin'] = $_POST['log_to_admin'];
	$cfo['new_listing_to_admin'] = $_POST['new_listing_to_admin'];
	update_option('classyfrieds_options', $cfo);
	echo "<div class='classynotice'>Options processed !</div>";
	}
	
global $wpdb;
$cfl = get_option('classyfrieds_language');
$cfo = get_option('classyfrieds_options'); 

?>
<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/css/admin_menus.css', __FILE__); ?>" />

<div class="classyfrieds_menu_left">
<h1>Classyfrieds notifications - settings</h1>
	<div class="center">
	<form method="POST">
	ADMIN AND USER NOTIFICATIONS
		
		<div class="row">
			<div class="lrow">
			Classyfrieds can send e-mail alerts, reminders and notifications to the listing-administrator that controls this blog or listing directory. Admin e-mails are currently sent to :
			</div>
			<div class="rrow">
			Administrator e-mail on record <br />
			<input type='email' name='admin_email' value='<?PHP echo $cfo['admin_email']; ?>' >
			</div>
			<div class='cf_clearclear'></div>
		</div>
		
		<div class="row">
			<div class="lrow">
			Classyfrieds can send the listing-administrator a daily or weekly status-update ! This e-mail contains security-alerts and other system activities from THIS blog only.
			</div>
			<div class="rrow">
			Send logs to admin ?<br />
			<select name="log_to_admin">
			<option value='off' <?PHP if($cfo['log_to_admin'] == 'off') echo 'selected'; ?> >No, turn e-mail off</option >
			<option value='daily' <?PHP if($cfo['log_to_admin'] == 'daily') echo 'selected'; ?> >Send me a daily update</option >
			<option value='weekly' <?PHP if($cfo['log_to_admin'] == 'weekly') echo 'selected'; ?> >Send me an update once a week</option >
			</select>
			</div>
			<div class='cf_clearclear'></div>
		</div>
		
		<div class="row">
			<div class="lrow">
			Administrators can be notified instantly when a new listing is added. This gives you the change to moderate new listings and publish them without delay.
			</div>
			<div class="rrow">
			Send new listing alert to admin ?<br />
			<select name="new_listing_to_admin">
			<option value='no' <?PHP if($cfo['new_listing_to_admin'] == 'no') echo 'selected'; ?> >No, turn listing alerts off</option >
			<option value='yes' <?PHP if($cfo['new_listing_to_admin'] == 'yes') echo 'selected'; ?> >Yes, Send 'new listing' alerts</option >
			</select>
			</div>
			<div class='cf_clearclear'></div>
		</div>
		
	<?php wp_nonce_field('classy_notes','cfnonce'); ?>
	<input type="submit" value="Submit changes">
	</form>
	</div>
</div>




<div class="classyfrieds_menu_right">
<img src='<?PHP echo plugins_url('/images/logo.jpg', __FILE__); ?>' width="80" style="float:right;border:2px solid blue;margin:3px"/>

<h2>News and updates</h2>
Directly from our home-page <a href='http://classyfrieds.com' title='visit classyfrieds.com'>classyfrieds.com</a><br />
<div class="smallinfo" style="overflow:auto;height:50px">
	<?PHP echo $cfo['error_log']; ?>
</div>

<?php 	
	if(function_exists('fetch_feed')) 
	{
		include_once(ABSPATH . WPINC . '/feed.php');
		$feed = 'http://classyfrieds.com/feed/';
		$rss = fetch_feed($feed);
		if (!is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity(2);
			$rss_items = $rss->get_items(0, $maxitems);
			if ($rss_items):
				echo "<ul>\n";
				foreach ( $rss_items as $item ) :
					echo '<li>';
					//print_R($item);
					echo '<h2><a href="' . $item->get_permalink() . '">' . $item->get_title() . "</a></h2>";
					echo '<p>' . $item->get_content() . "</li>\n";
				endforeach;
				echo "</ul>\n";
			endif;
		endif;		
	}
	
?>


</div>