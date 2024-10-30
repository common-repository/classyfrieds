<?PHP 
if ( ! is_admin() ) die ('sorry, only admins allowed');
global $wpdb;
$cfo = get_option('classyfrieds_options'); 
?>

<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/css/admin_menus.css', __FILE__); ?>" />

<div class="classyfrieds_menu_left">
<h1>Classyfrieds system upgrades</h1>
The classyfrieds system allows you to mix and match infinite stylesheets with infinite layouts for infinite styling of your listings. Use the forms below 
to add new styles and layouts to your system. You can download additional layouts and styles from <a href='http://classyfrieds.com' title='classyfrieds homepage'>classyfrieds.com</a>.<br />
<div class="center">
<?php

if (! empty($_POST) && wp_verify_nonce($_POST['cfupnonce'],'classy_pages') )
	{
	if ($_FILES['upgrade_language']['error'] == "0")
		{
		$ext = end(explode(".",$_FILES['upgrade_language']['name']));
		if (substr($_FILES['upgrade_language']['name'],0,5) != "lang_" ||  $ext != "php")
			{echo "<div class='classynotice'>This file does not appear to be a Classyfrieds language file ! Try again</div>";}
		else
			{
			if(!move_uploaded_file($_FILES["upgrade_language"]["tmp_name"], plugin_dir_path(__FILE__) . '../themefiles/language/' . $_FILES["upgrade_language"]["name"])) echo "error moving";		
			echo "<div class='classynotice'>language file was added !</div>";
			}
		}
	if ($_FILES['upgrade_stylesheet']['error'] == "0")
		{
		$ext = end(explode(".",$_FILES['upgrade_stylesheet']['name']));
		if ($ext != "css")
			{echo "<div class='classynotice'>This file does not appear to be a stylesheet or .css file ! Try again</div>";}
		else
			{
			if(!move_uploaded_file($_FILES["upgrade_stylesheet"]["tmp_name"], plugin_dir_path(__FILE__) . '../themefiles/layouts/' . $_FILES["upgrade_stylesheet"]["name"])) echo "error moving";		
			echo "<div class='classynotice'>stylesheet or .css file was added !</div>";
			}
		}
	if ($_FILES['upgrade_index_layout']['error'] == "0")
		{
		$ext = end(explode(".",$_FILES['upgrade_index_layout']['name']));
		if (substr($_FILES['upgrade_index_layout']['name'],0,5) != "listi" ||  $ext != "php")
			{echo "<div class='classynotice'>This file does not appear to be an INDEX layout file ! Try again</div>";}
		else
			{
			if(!move_uploaded_file($_FILES["upgrade_index_layout"]["tmp_name"], plugin_dir_path(__FILE__) . '../themefiles/layouts/' . $_FILES["upgrade_index_layout"]["name"])) echo "error moving";		
			echo "<div class='classynotice'>index layout file was added !</div>";
			}
		}
	if ($_FILES['upgrade_single_layout']['error'] == "0")
		{
		$ext = end(explode(".",$_FILES['upgrade_single_layout']['name']));
		if (substr($_FILES['upgrade_single_layout']['name'],0,5) != "singl" ||  $ext != "php")
			{echo "<div class='classynotice'>This file does not appear to be a SINGLE layout file ! Try again</div>";}
		else
			{
			if(!move_uploaded_file($_FILES["upgrade_single_layout"]["tmp_name"], plugin_dir_path(__FILE__) . '../themefiles/layouts/' . $_FILES["upgrade_single_layout"]["name"])) echo "error moving";		
			echo "<div class='classynotice'>SINGLE layout file was added !</div>";
			}
		}
	}
?>

<form method="POST" enctype="multipart/form-data">
<h2>Languages</h2>
You can add languages using the language upload button.<br>
<div class="row">
	<div class="lrow">
	Currently available languages<br />
		<div class="smallinfo" style="overflow:auto;height:30px;width:200px">
		<?PHP
		$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/language/" ;
		foreach (glob($here."*.php") as $filename) {
		$filename = end(explode("/",$filename));
		$filename = reset(explode(".",$filename));
		//if($cfo['language'] == $filename) $sel = "selected"; else $sel = "";
		//echo "<option value='$filename' $sel>$filename</option>";
		echo $filename . "<br />";
		}	
		?>
		</div>
	</div>
	<div class="rrow">
	Add a new language file :<br />
	<input type="file" name="upgrade_language">
	</div>
	<div class='cf_clearclear'></div>
To use or activate a newly uploaded language visit the 'listings' menu !<br />
<input type="submit" value="transmit language file !"> <br />	
</div>

<br />
<div class='cf_classyclear'></div>
<h2>Stylesheets (.css)</h2>
You can add new stylesheets using the stylesheet upload button.<br>Stylesheets (.CSS) typically determine the color user, border types, and sizes of screen objects.<br>
<div class="row">
	<div class="lrow">
	Currently available stylesheets<br />
		<div class="smallinfo" style="overflow:auto;height:30px;width:200px">
		<?PHP
		$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
		foreach (glob($here."*.css") as $filename) {
		$filename = end(explode("/",$filename));
		$filename = reset(explode(".",$filename));
		//if($cfo['css_color'] == $filename) $sel = "selected"; else $sel = "";
		//echo "<option value='$filename' $sel>$filename</option>";
		echo $filename . "<br />";
		}	
		?>
		</div>
	</div>
	<div class="rrow">
	Add a new stylesheet / .css file :<br />
	<input type="file" name="upgrade_stylesheet">
	</div>
	<div class='cf_clearclear'></div>
To use or activate a newly uploaded stylesheet visit the 'listings' menu !<br />
<input type="submit" value="transmit your stylesheet !">
</div>

<br />
<div class='cf_classyclear'></div>
<h2>listing INDEX layouts</h2>
You can change the way your listings appear on the INDEX page.<br>Upload a new layout and inifitely combine it with new stylesheets.<br>
<div class="row">
	<div class="lrow">
	Currently available index layouts<br />
		<div class="smallinfo" style="overflow:auto;height:30px;width:200px">
		<?PHP
		$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
		foreach (glob($here."listing_*.php") as $filename) {
		$filename = end(explode("/",$filename));
		$filename = reset(explode(".",$filename));
		//if($cfo['css_color'] == $filename) $sel = "selected"; else $sel = "";
		//echo "<option value='$filename' $sel>$filename</option>";
		echo $filename . "<br />";
		}	
		?>
		</div>
	</div>
	<div class="rrow">
	Add a new INDEX layout :<br />
	<input type="file" name="upgrade_index_layout">
	</div>
	<div class='cf_clearclear'></div>
To use or activate a newly uploaded layout visit the 'listings' menu !<br />
<input type="submit" value="transmit your INDEX layout !">
</div>

<br />
<div class='cf_classyclear'></div>
<h2>SINGLE listing layouts</h2>
You can change the way your SINGLE listing appears when a user clicks on a link.<br>Upload a new layout and inifitely combine it with new stylesheets.<br>
<div class="row">
	<div class="lrow">
	Currently available SINGLE layouts<br />
		<div class="smallinfo" style="overflow:auto;height:30px;width:200px">
		<?PHP
		$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
		foreach (glob($here."single_*.php") as $filename) {
		$filename = end(explode("/",$filename));
		$filename = reset(explode(".",$filename));
		//if($cfo['css_color'] == $filename) $sel = "selected"; else $sel = "";
		//echo "<option value='$filename' $sel>$filename</option>";
		echo $filename . "<br />";
		}	
		?>
		</div>
	</div>
	<div class="rrow">
	Add a new SINGLE layout :<br />
	<input type="file" name="upgrade_single_layout">
	</div>
	<div class='cf_clearclear'></div>
To use or activate a newly uploaded layout visit the 'listings' menu !<br />
<input type="submit" value="transmit your SINGLE layout !">
</div>



<?php wp_nonce_field('classy_pages','cfupnonce'); ?>
</form>
</div>

<br /><br />

</div>

<div class="classyfrieds_menu_right">
<img src='<?PHP echo plugins_url('/images/logo.jpg', __FILE__); ?>' width="80" style="float:right;border:2px solid blue;margin:3px"/>
<h2>News and updates</h2>
Directly from our home-page <a href='http://classyfrieds.com' title='visit classyfrieds.com'>classyfrieds.com</a><br />
<div class="smallinfo" style="overflow:auto;height:50px">
	<form method="POST">
	<input type="submit" name="clean_log" value="clear_log">
	</form>
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