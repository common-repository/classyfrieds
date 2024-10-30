<?PHP 
if ( ! is_admin() ) die ('sorry, only admins allowed');
global $wpdb;
$cfo = get_option('classyfrieds_options'); 
?>

<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/css/admin_menus.css', __FILE__); ?>" />

<div class="classyfrieds_menu_left">
<h1>Classyfrieds classifieds system - settings</h1>

<div class="center">
<?php

if (! empty($_POST) && wp_verify_nonce($_POST['cfnonce'],'classy_pages') )
	{
	$cfo = get_option('classyfrieds_options');
	$cfo[reroute_MAIN] = $_POST[reroute_MAIN];
	$cfo[cf_sidebars] = $_POST['show_sidebars'];
	$cfo['show_comments'] = $_POST['show_comments'];
	// store listpage
	$cfo[listings_per_page] = $_POST['cfo_pp'];
	$cfo[show_menu] = $_POST['cfo_menu'];
	$cfo['show_search_in_menu'] = $_POST['show_search_in_menu'];
	$cfo[listing_layout] = $_POST['cfo_layout'];
	$cfo[css_color] = $_POST['cfo_colors'];
	$cfo['cfo_featured_css'] = $_POST['cfo_featured_css'];
	$cfo[listing_layout_single] = $_POST['cfo_layout_single'];
	$cfo[listing_layout_taxonomy] = $_POST['cfo_layout_taxonomy'];
	$cfo[allow_visitors] = $_POST['allow_visitors'];
	$cfo[auto_publish] = $_POST['auto_publish'];
	$cfo[language] = $_POST['language'];
	$cfo[show_bumpup] = $_POST[show_bumpup];
	$cfo[charge_bumpup] = $_POST[charge_bumpup];
	$cfo[show_featured] = $_POST[show_featured];
	$cfo[charge_featured] = $_POST[charge_featured];
	$cfo[show_extend] = $_POST[show_extend];
	$cfo[charge_extend] = $_POST[charge_extend];
	update_option('classyfrieds_options', $cfo);
	// fill language
	$cfl = array();
	include(plugin_dir_path( dirname(__FILE__) ) . '/themefiles/language/'. $cfo['language'] . '.php');
	update_option('classyfrieds_language',$cfl);

	echo "<div class='classynotice'>Options processed !</div>";
	}
?>

<form method="POST">
CORE BEHAVIOUR MODIFICATIONS
<div class="row">
	<div class="lrow">
	Classyfrieds can intercept calls to your WP front page and display it's listings instead ! NOTE : this setting OVERRIDES other wordpress settings for front-page views !
	</div>
	<div class="rrow">
	Intercept calls to front-page ?<br />
	<select name="reroute_MAIN">
	<option value='INTERCEPTING_FRONT_PAGE' <?PHP if($cfo[reroute_MAIN] == 'INTERCEPTING_FRONT_PAGE') echo 'selected'; ?> >Yes, show listings instead</option >
	<option value='no' <?PHP if($cfo[reroute_MAIN] == 'no') echo 'selected'; ?> >No, don't touch the front page</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Classyfrieds has it's own sidebars that only show up next to listings and user forms. You can turn these sidebars off here if they conflict with your theme !
	</div>
	<div class="rrow">
	Show classyfrieds sidebars ?<br />
	<select name="show_sidebars">
	<option value='on' <?PHP if($cfo['cf_sidebars'] == 'on') echo 'selected'; ?> >Yes, show custom sidebars</option >
	<option value='off' <?PHP if($cfo['cf_sidebars'] == 'off') echo 'selected'; ?> >No, do not show classyfrieds sidebars</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Comments on classyfrieds listings can be turned off, even if comments for the general blog are turned on ! (If general blog comments are off, classyfrieds comments will not show regardless of settings here).
	</div>
	<div class="rrow">
	display the comment box ?<br />
	<select name="show_comments">
	<option value='yes' <?PHP if($cfo['show_comments'] == 'yes') echo 'selected'; ?> >Yes, show comment boxes when available</option >
	<option value='no' <?PHP if($cfo['show_comments'] == 'no') echo 'selected'; ?> >No, never show comments on classyfrieds</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<input type="submit" value="Submit changes">
<div class="cf_classyclear"></div>

GENERAL LAYOUT AND STYLE OPTIONS
<div class="row">
	<div class="lrow">
	Classyfrieds comes in many different languages. You can choose from the available languages or get some more from our modules-server.</div>
	<div class="rrow">
	Select the preferred language<br />
	<select name="language">
	<?PHP
	$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/language/" ;
	foreach (glob($here."lang_*.php") as $filename) {
	$filename = end(explode("/",$filename));
	$filename = reset(explode(".",$filename));
	if($cfo['language'] == $filename) $sel = "selected"; else $sel = "";
	echo "<option value='$filename' $sel>$filename</option>";
	}	
	?>
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	The listings on the main page can be displayed in a variety of ways. When selecting columns listings are shown in blocks otherwise they are shown in bars.</div>
	<div class="rrow">
	Select the preferred layout for your listings<br />
	<select name="cfo_layout">
	<?PHP
	$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
	foreach (glob($here."listing_*.php") as $filename) {
	$filename = end(explode("/",$filename));
	$filename = reset(explode(".",$filename));
	if($cfo['listing_layout'] == $filename) $sel = "selected"; else $sel = "";
	echo "<option value='$filename' $sel>$filename</option>";
	}	
	?>
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	When users click on a listing title or image they are taken to a new page with more details and a comment box. You can apply different layouts to the "single" page.</div>
	<div class="rrow">
	Select the preferred layout for your Single listing<br />
	<select name="cfo_layout_single">
	<?PHP
	$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
	foreach (glob($here."single_*.php") as $filename) {
	$filename = end(explode("/",$filename));
	$filename = reset(explode(".",$filename));
	if($cfo['listing_layout_single'] == $filename) $sel = "selected"; else $sel = "";
	echo "<option value='$filename' $sel>$filename</option>";
	}	
	?>
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	If a user clicks on a category in a listing or a widget a page is shown with all listings in that category.. You can apply different layouts to the "category" page.</div>
	<div class="rrow">
	Select the preferred layout for your category overview<br />
	<select name="cfo_layout_taxonomy">
	<?PHP
	$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
	foreach (glob($here."taxonomy_*.php") as $filename) {
	$filename = end(explode("/",$filename));
	$filename = reset(explode(".",$filename));
	if($cfo['listing_layout_taxonomy'] == $filename) $sel = "selected"; else $sel = "";
	echo "<option value='$filename' $sel>$filename</option>";
	}	
	?>
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Various color schemes can be applied to the classyfrieds system. See our premium modules for even more colors and layout options.
	</div>
	<div class="rrow">
	Select an overall color scheme<br />
	<select name="cfo_colors">
	<?PHP
	$here = plugin_dir_path( dirname(__FILE__ )) . "themefiles/layouts/" ;
	foreach (glob($here."*.css") as $filename) {
	$filename = end(explode("/",$filename));
	$filename = reset(explode(".",$filename));
	if($cfo['css_color'] == $filename) $sel = "selected"; else $sel = "";
	echo "<option value='$filename' $sel>$filename</option>";
	}	
	?>
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Users (or you as admin) can create 'Featured' listings, (either paid or free). These featured listings stand out because of different styling. You can adjust that styling here if needed.
	</div>
	<div class="rrow">
	enter CSS code for featured listings.<br />
	<textarea name="cfo_featured_css" style="width:90%"><?PHP echo $cfo['cfo_featured_css']; ?></textarea>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	A menu with links to the listings and an "add-listing" link can be shown above your listings automatically
	</div>
	<div class="rrow">
	Show the menu above the listings<br />
	<select name="cfo_menu">
	<option value='yes' <?PHP if($cfo[show_menu] == 'yes') echo 'selected'; ?> >Yes</option >
	<option value='no' <?PHP if($cfo[show_menu] == 'no') echo 'selected'; ?> >No</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	A searchbox can be inserted in the above mentioned menu.
	</div>
	<div class="rrow">
	Show a searchbox in the menu ?<br />
	<select name="show_search_in_menu">
	<option value='yes' <?PHP if($cfo['show_search_in_menu'] == 'yes') echo 'selected'; ?> >Yes</option >
	<option value='no' <?PHP if($cfo['show_search_in_menu'] == 'no') echo 'selected'; ?> >No</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Select the maximum amount of listings you want on a page at any given time. Column layout typically has 3 columns so it's best to make your amount of posts divisible by 3
	</div>
	<div class="rrow">
	Set Listings per page<br />
	<input type="number" name="cfo_pp" min="3" max="45" size="5" value='<?PHP echo $cfo[listings_per_page]; ?>' >
	</div>
	<div class='cf_clearclear'></div>
</div>

<input type="submit" value="Submit changes">
<div class="cf_classyclear"></div>

USER RESTRICTION AND POST PUBLISHING
<div class="row">
	<div class="lrow">
	Using this option you can allow non-registered users (visitors) to add listings. (this is NOT recommended and spam sensitive!)
	</div>
	<div class="rrow">
	allow visitor listings ?<br />
	<select name="allow_visitors">
	<option value='no' <?PHP if(empty($cfo[allow_visitors]) ) echo 'selected'; ?> >---</option >
	<option value='yes' <?PHP if($cfo[allow_visitors] == 'yes') echo 'selected'; ?> >Yes</option >
	<option value='no' <?PHP if($cfo[allow_visitors] == 'no') echo 'selected'; ?> >No</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	When a user submits a listing they are normally marked as 'PENDING' so you can review them first.You can also choose to "PUBLISH" these listings immediately.
	</div>
	<div class="rrow">
	<?PHP
	if (function_exists('classyfrieds_paid_content'))
		{
		?>
		Paid content plugin detected !<br />
		<span style="color:red">status forced to 'pending'</span><br />
		<select name="auto_publish">
		<option value='pending' <?PHP if($cfo[auto_publish] == 'pending') echo 'selected'; ?> >pending</option >
		</select>
		<?PHP
		}
	else
		{
		?>
		Status for newly added listings ?<br />
		<select name="auto_publish">
		<option value='pending' <?PHP if($cfo[auto_publish] == 'pending') echo 'selected'; ?> >pending</option >
		<option value='publish' <?PHP if($cfo[auto_publish] == 'publish') echo 'selected'; ?> >publish</option >
		<option value='draft' <?PHP if($cfo[auto_publish] == 'draft') echo 'selected'; ?> >draft</option >
		<option value='private' <?PHP if($cfo[auto_publish] == 'private') echo 'selected'; ?> >private</option >
		</select>
		<?PHP
		}
		?>
	</div>
	<div class='cf_clearclear'></div>
</div>
<input type="submit" value="Submit changes">
<div class="cf_classyclear"></div>
POST BUMPING , FEATURING & EXTENDING
<div class="row">
	<div class="lrow">
	Underneath a single view listing you can offer users an option to 'BUMP' a listing up to the top of the list (reset publish date)
	</div>
	<div class="rrow">
	Show a bumpup option ?<br />
	<select name="show_bumpup">
	<option value='yes' <?PHP if($cfo[show_bumpup] == 'yes') echo 'selected'; ?> >Yes</option >
	<option value='no' <?PHP if($cfo[show_bumpup] == 'no') echo 'selected'; ?> >No</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	If you have the 'PAID CONTENT' plugin, you can charge a fee for post bumping. (actual fee is set in the paid content tab).
	</div>
	<div class="rrow">
<?PHP
if (function_exists('classyfrieds_paid_content'))
		{
		?>
		Charge A fee for bumpup option ?<br />
		<select name="charge_bumpup">
		<option value='yes' <?PHP if($cfo[charge_bumpup] == 'yes') echo 'selected'; ?> >Yes (set charge in pc tab)</option >
		<option value='no' <?PHP if($cfo[charge_bumpup] == 'no') echo 'selected'; ?> >No, make it free</option >
		</select>
		<?PHP
		}
	else
		{
		?>
		Charge A fee for bumpup option ?<br />
		<select name="charge_bumpup">
		<option value='no' <?PHP if($cfo[charge_bumpup] == 'no') echo 'selected'; ?> >No, (PAID CONTENT plugin missing)</option >
		</select>
		<?PHP
		}
?>
	</div>
	<div class='cf_clearclear'></div>
</div>
<br />

<div class="row">
	<div class="lrow">
	Underneath a single view listing you can offer users an option to 'FEATURE' a listing so it stands out and may get listed in special widgets
	</div>
	<div class="rrow">
	Show a 'make featured' option ?<br />
	<select name="show_featured">
	<option value='yes' <?PHP if($cfo[show_featured] == 'yes') echo 'selected'; ?> >Yes</option >
	<option value='no' <?PHP if($cfo[show_featured] == 'no') echo 'selected'; ?> >No</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	If you have the 'PAID CONTENT' plugin, you can charge a fee for featuring posts. (actual fee is set in the paid content tab).
	</div>
	<div class="rrow">
<?PHP
if (function_exists('classyfrieds_paid_content'))
		{
		?>
		Charge A fee for featuring posts ?<br />
		<select name="charge_featured">
		<option value='yes' <?PHP if($cfo[charge_featured] == 'yes') echo 'selected'; ?> >Yes (set charge in pc tab)</option >
		<option value='no' <?PHP if($cfo[charge_featured] == 'no') echo 'selected'; ?> >No, make it free</option >
		</select>
		<?PHP
		}
	else
		{
		?>
		Charge A fee for bumpup option ?<br />
		<select name="charge_featured">
		<option value='no' <?PHP if($cfo[charge_featured] == 'no') echo 'selected'; ?> >No, (PAID CONTENT plugin missing)</option >
		</select>
		<?PHP
		}
?>
	</div>
	<div class='cf_clearclear'></div>
</div>

<br />
<div class="row">
	<div class="lrow">
	Underneath a single view listing you can offer users an option to 'EXTEND' the life of a listing so it shows longer.
	</div>
	<div class="rrow">
	Show an extend option ?<br />
	<select name="show_extend">
	<option value='yes' <?PHP if($cfo[show_extend] == 'yes') echo 'selected'; ?> >Yes</option >
	<option value='no' <?PHP if($cfo[show_extend] == 'no') echo 'selected'; ?> >No</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	If you have the 'PAID CONTENT' plugin, you can set several fees for listing extensions. (actual fee is set in the paid content tab).
	</div>
	<div class="rrow">
<?PHP
if (function_exists('classyfrieds_paid_content'))
		{
		?>
		Charge fees for extending listings ?<br />
		<select name="charge_extend">
		<option value='yes' <?PHP if($cfo[charge_extend] == 'yes') echo 'selected'; ?> >Yes (set charges in pc tab)</option >
		<option value='no' <?PHP if($cfo[charge_extend] == 'no') echo 'selected'; ?> >No, make it free</option >
		</select>
		<?PHP
		}
	else
		{
		?>
		Charge A fee for extending listings ?<br />
		<select name="charge_extend">
		<option value='no' <?PHP if($cfo[charge_extend] == 'no') echo 'selected'; ?> >No, (PAID CONTENT plugin missing)</option >
		</select>
		<?PHP
		}
?>
	</div>
	<div class='cf_clearclear'></div>
</div>

 <?php wp_nonce_field('classy_pages','cfnonce'); ?>
<input type="submit" value="Submit changes">
</form>
</div>

<br /><br />

</div>

<?PHP include('sidebar.php'); ?>