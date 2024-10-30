<?PHP
// classyfrieds admin menu sidebar

// reset permalinks if user submits request
if ($_POST['rst'] == 'perm') update_option('permalink_structure' , '/%postname%/');

if ($_POST['init']) {classyfrieds_initialise(); echo "<div class='classynotice'>Reinitilizing sequence complete.</div>";
	}

if ($_POST['initall']) {
	$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'classyfried_listings'");
	wp_delete_post( $id, TRUE );
	$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'classyfried_add_listing'");
	wp_delete_post( $id, TRUE );
	update_option('classyfrieds_options','');
	classyfrieds_initialise(); 
	echo "<div class='classynotice'>HARD RESET sequence complete.</div>";
	}
	
$cfo = get_option('classyfrieds_options');
if (isset($_POST['clean_log'])) { $cfo['error_log'] = ''; update_option('classyfrieds_options',$cfo);}
?>


<div class="classyfrieds_menu_right">
<img src='<?PHP echo plugins_url('/images/logo.jpg', __FILE__); ?>' width="80" style="float:right;border:2px solid blue;margin:3px"/>
<h2>Systems check - version: <?PHP echo $cfo['version']; ?></h2>
<form method="POST">
<input type="hidden" name="init" value="1">
<input type="submit" value="soft reinitialize" class="button">
</form><br />

<div class="smallinfo" style="overflow:auto;height:50px">
	<form method="POST">
	<input type="submit" name="clean_log" value="clear_log">
	</form>
	<?PHP echo $cfo['error_log']; ?>
</div>

<div class="smallinfo" style="overflow:auto;height:120px;background-color:lightyellow;border-radius:3px">
Latest news and updates from classyfrieds.com :<br />
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
					echo '<strong><a href="' . $item->get_permalink() . '">' . $item->get_title() . "</a></strong><br />";
					echo $item->get_description() ;
					echo '</li>';
				endforeach;
				echo "</ul>\n";
			endif;
		endif;		
	}
	
?>
</div>
<?PHP	
// check settings

// check permalinks for pages
if(!empty($cfo['add_items_permalink']) && !empty($cfo['listings_permalink'])) {
 echo "<div class='classykey'>are permalinks set ? :</div><div class='classyval_green'><input type='text' value=' Yes, permalinks for pages found'></div><div class='cf_clearclear'></div>";
} else {
echo "<div class='classykey'>permalinks missing ! :</div><div class='classyval_red'><form method='post'><input type='hidden' name='initall' value='1'><input type='submit' value='visit classyfrieds->classyfrieds and set the 2 pages !' title='click now to re-initialize this plugin'></form></div><div class='cf_clearclear'></div>";
}

// check permalink structure
if ( get_option('permalink_structure') == '/%postname%/')  {
 echo "<div class='classykey'>permalink structure ok ? :</div><div class='classyval_green'><input type='text' value=' Yes, permalink structure may be correct'></div><div class='cf_clearclear'></div>";
} else {
echo "<div class='classykey'>permalink error :</div><div class='classyval_red'><form method='post'><input type='hidden' value='perm' name='rst'><input type='submit' value='set permalinks to /%postname%/ or click here now !' title='click now to automatically set permalinks to /%postname%/ '></form></div><div class='cf_clearclear'></div>";
}

// check post type exists
if(post_type_exists('classyfrieds')) {
 echo "<div class='classykey'>Was post type created ? :</div><div class='classyval_green'><input type='text' value=' Yes, posttype classyfrieds was found'></div><div class='cf_clearclear'></div>";
} else {
echo "<div class='classykey'>Critical error:</div><div class='classyval_red'><input type='text' value='Posttype classyfrieds was not found'></div><div class='cf_clearclear'></div>";
}

// check upload dir for images
$uploads = wp_upload_dir();
if (is_writable($uploads['basedir'])) { echo "<div class='classykey'>upload path writable ? :</div><div class='classyval_green'><input type='text' value='".$uploads['basedir'] . "/classyfrieds/'></div><div class='cf_clearclear'></div>";}
else { echo "<div class='classykey'>upload path not writable !:</div><div class='classyval_red'><input type='text' value='" . $uploads['basedir'] . "/classyfrieds/" . "'></div><div class='cf_clearclear'></div>";}

// check standard files
$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'classyfried_listings'");
if ($id) echo "<div class='classykey'>listing page :</div><div class='classyval_green'><input type='text' value='Standard file detected'></div><div class='cf_clearclear'></div>";
// check standard files
$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'classyfried_add_listing'");
if ($id) echo "<div class='classykey'>Add a listing page :</div><div class='classyval_green'><input type='text' value='Standard file detected'></div><div class='cf_clearclear'></div>";

// check paginate plugin

// pagenavi
if(function_exists('wp_pagenavi')) { 
	echo "<div class='classykey'>pagenavi :</div><div class='classyval_green'><input type='text' value=' detected ! listings pagination activated.'></div><div class='cf_clearclear'></div>";}
else { 
	echo "<div class='classykey'>pagenavi pagination :</div><div class='classyval_yellow'><input type='text' value=' Pagenavi plugin not detected. pagination turned off.'></div><div class='cf_clearclear'></div>";}

// paid content
if (function_exists('classyfrieds_paid_content')){
	echo "<div class='classykey'>paid content extension :</div><div class='classyval_green'><input type='text' value=' paid content plugin detected & activated.'></div><div class='cf_clearclear'></div>";}
else { 
	echo "<div class='classykey'>paid content extension :</div><div class='classyval_yellow'><a href='http://classyfrieds.com/classyfrieds-paid-content/'><input type='text' value=' paid content plugin not detected. CLICK HERE NOW to get it'  title='Click to download the paid content plugin'></a></div><div class='cf_clearclear'></div>";}

if (function_exists('classyfrieds_ads')){
	echo "<div class='classykey'>ads & notices extension :</div><div class='classyval_green'><input type='text' value=' ads & notices plugin detected & activated.'></div><div class='cf_clearclear'></div>";}
else { 
	echo "<div class='classykey'>ads & notices extension :</div><div class='classyval_yellow'><a href='http://classyfrieds.com/classyfrieds-ads-and-notices/' ><input type='text' value=' ads & notices plugin not detected. CLICK HERE NOW to get it' title='Click to download the ads & notices plugin'></a></div><div class='cf_clearclear'></div>";}

if (function_exists('classyfrieds_sidebarfun_on') || strlen($cfo['ver_sidebarfun'] > 1) ){
	echo "<div class='classykey'>Specialty sidebar Widgets :</div><div class='classyval_green'><input type='text' value=' classyfrieds sidebarfun widgets detected & activated.'></div><div class='cf_clearclear'></div>";}
else { 
	echo "<div class='classykey'>Specialty sidebar Widgets :</div><div class='classyval_yellow'><a href='http://classyfrieds.com/sidebar-fun-for-classyfrieds/' ><input type='text' value=' sidebarfun widgets not detected. click here to get them' title='Click to download the sidebarfun widgets'></a></div><div class='cf_clearclear'></div>";}
	
if (function_exists('cf_mapme') || strlen($cfo['ver_maps'] > 1) ){
	echo "<div class='classykey'>google maps popup :</div><div class='classyval_green'><input type='text' value=' google maps detected & activated.'></div><div class='cf_clearclear'></div>";}
else { 
	echo "<div class='classykey'>google maps popup :</div><div class='classyval_yellow'><a href='http://classyfrieds.com/classyfrieds-maps/' ><input type='text' value=' google maps implementation not detected. click here to get it' title='Click to download the google maps plugin'></a></div><div class='cf_clearclear'></div>";}
	
	
// list all options
ksort($cfo);
foreach ($cfo as $key => $val)
	{
	echo "<div class='classykey'>$key :</div><div class='classyval'><input type='text' value='$val' disabled></div><div class='cf_clearclear'></div>";
	}

?>
<br />
<form method="POST">
<input type="hidden" name="initall" value="1">
<input type="submit" value="flush system and reinitialize" class="button">
</form><br />
<small>Classyfrieds is developed and maintained by Pete Scheepens | wordpressprogrammeurs.nl</small>
</div>