<?PHP 
if ( ! is_admin() ) die ('sorry, only admins allowed');
global $wpdb;
$cfo = get_option('classyfrieds_options'); 
?>

<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/css/admin_menus.css', __FILE__); ?>" />

<div class="classyfrieds_menu_left">
<img src='<?PHP echo plugins_url('/images/logo.jpg', __FILE__); ?>' style='float:right;margin:5px;border:3px solid blue'>
<h1>Classyfrieds classifieds</h1>
<h2>First Light !</h2>
<h3>Permalinks</h3>
Classyfrieds uses a lot of wordpress core features to do its thing. On many of these features it needs pretty links. If you have not done so yet, please select 
a permalink setting in your Settings menu. We highly recommend using the /%postname%/ permalinks, both for SEO and Performance. (Contrary to some news, as of WP 3.3 the /%postname%/ permalink structure is perfectly acceptable).<br />
<h3>page hijacking</h3>
When you first run the classyfrieds system it is still missing some pages in your system. To become operational, classyfrieds needs to hijack 2 WordPress pages, namely:
<br />
* A listing page - this is the main area showing all listings.<br />
* An Add Listing page - this is where users can add classifieds<br /><br />
<strong>At this moment classyfrieds has automatically created two pages for you, called 'classyfrieds' and 'classyfried_add_listings' !</strong><br />
If you want to you can change the name of these pages in the 'PAGES' menu, however you must NOT change the existing page-permalinks when using these pages.<br />
Optionally you can delete the 2 pages we created, and select 2 existing pages in the form below that will function as your classifieds main pages.
<br />

Please take note ! Classyfrieds will literally hi-jack these 2 pages and re-route any calls to these pages to its own internal theme pages. If you select any existing pages, the content on these 
pages will be rendered useless as we hi-jack the page-links before the existing pages even load.<br />
<div class="cf_classyclear"></div>
<div class="center">
<?php
// local functions

if (! empty($_POST) && wp_verify_nonce($_POST['cfnonce'],'classy_pages') )
	{	
	// store listpage
	$cfo = get_option('classyfrieds_options');	
	$listpage = explode("|",$_POST['list_page']);
	$cfo[listings_slug] = $listpage[1];
	$cfo[listings_permalink] = get_permalink( $listpage[0] );
	// store add listingpage
	$alistpage = explode("|",$_POST['add_list_page']);
	$cfo[add_items_slug] = $alistpage[1];
	$cfo[add_items_permalink] = get_permalink( $alistpage[0] );
	update_option('classyfrieds_options', $cfo);
	classyfrieds_initialise();
	create_classyfrieds_type();
	echo "<div class='classynotice'>PAGE SETTINGS STORED !</div>";	
	// write activity to log	
	$cfo = get_option('classyfrieds_options');	
	$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " PAGE intercept was altered by administrator<br/>";
	update_option('classyfrieds_options',$cfo);
	}
?>
<strong>OPTIONAL: Select 2 exiting pages to function as listing and Add-listing pages :</strong>
<form method="POST">
Select a page to use as the Listing page : 
<select name="list_page">
<?PHP
 $pages = get_pages(); 
  foreach ( $pages as $page ) {
	echo "<option value='$page->ID|$page->post_name'";
	if ($page->post_name == $cfo[listings_slug]) echo "SELECTED";
	echo ">$page->post_name</option>";
  }
?>
</select>
<br>
Select a page to let users ADD Listings : 
<select name="add_list_page">
<?PHP
 $pages = get_pages(); 
  foreach ( $pages as $page ) {
	echo "<option value='$page->ID|$page->post_name'";
	if ($page->post_name == $cfo[add_items_slug]) echo "SELECTED";
	echo ">$page->post_name</option>";
  }
?>
</select> 
<br />(Do not use the same page twice)
<br />
 <?php wp_nonce_field('classy_pages','cfnonce'); ?>
<input type="submit" value="change pages">
</form>
</div>
<div class="cf_classyclear"></div>
<br />
<h3>File upload</h3>
If the upload path is writable (No red boxes on the right ?) classyfrieds will create a new directory in ../wp-content/uploads.
When a signed in user adds a listing and includes images, a new subdirectory will be created using the screenname of this user. All images for this user are stored in it's own directory.
It's just so you know; there is nothing you need to do for this.
<br />
<h3>Categories or 'classycats'</h3>
Classyfrieds uses its own category system, which is nice because now you can use different categories for listings and for your regular wordpress posts.
When you first start classyfrieds you won't have any categories yet. Start now by creating a few 'classycats' in the classyfrieds menu (just below the POSTS menu). it 
works exactly the same as regular categories, but they'll never interfere with the other categories in your system.<br />
</div>

<?PHP include('sidebar.php'); ?>