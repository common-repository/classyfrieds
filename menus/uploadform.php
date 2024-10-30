<?PHP 
// classyfrieds admin menu - upload form layout

if ( ! is_admin() ) die ('sorry, only admins allowed');
global $wpdb;
$cfo = get_option('classyfrieds_options'); 
?>

<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/css/admin_menus.css', __FILE__); ?>" />

<div class="classyfrieds_menu_left">
<h1>Classyfrieds - upload form fields</h1>
<div class="center">
<h2>Add or remove fields that users see when they add a listing.</h2>
<?php
// check form entries
if (! empty($_POST) && wp_verify_nonce($_POST['cfnonce'],'classy_pages_uf') )
	{
	// get options
	$cfo = get_option('classyfrieds_options');	
	// store listpage
	$cfo[form_subtitle] = $_POST[form_subtitle];
	$cfo['form_version'] = $_POST['form_version'];
	$cfo['form_country'] = $_POST['form_country'];
	$cfo['form_cats'] = $_POST['form_cats'];
	$cfo['allow_create_cats'] = $_POST['allow_create_cats'];	
	$cfo['form_additional_info'] = $_POST['form_additional_info'];
	$cfo['form_url'] = $_POST['form_url'];
	$cfo['form_zip'] = $_POST['form_zip'];
	$cfo[form_pricing_field] = $_POST[form_pricing_field];
	$cfo[form_image_field] = $_POST[form_image_field];
	$cfo[form_keywords_field] = $_POST[form_keywords_field];
	$cfo[form_post_expiration] = $_POST[form_post_expiration];
	$cfo[form_featured_field] = $_POST[form_featured_field];
	update_option('classyfrieds_options', $cfo);	
	echo "<div class='classynotice'>Options processed !</div>";
	}
?>


<form method="POST">

<div class="row">
	<div class="lrow">
	A subtitle shows up below the title. example use: job description, salespitch, extra info etc.</div>
	<div class="rrow">
	turn <strong>subtitle</strong> on or off ?<br />
	<select name="form_subtitle">
	<option value='on' <?PHP if($cfo[form_subtitle] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_subtitle] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	dropdown box with countries of the world. Lets user select a country.</div>
	<div class="rrow">
	turn <strong>countries dropdown</strong> on or off ?<br />
	<select name="form_country">
	<option value='on' <?PHP if($cfo[form_country] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_country] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
	<div class="lrow">
	together with a country, A zipcode field enables for geo-coding on a google map.</div>
	<div class="rrow">
	turn <strong>zipcode or region</strong> on or off ?<br />
	<select name="form_zip">
	<option value='on' <?PHP if($cfo[form_zip] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_zip] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Small box that holds a year/make/version etc. - this typically shows up on the single layout view.</div>
	<div class="rrow">
	turn <strong>year/make/model box</strong> on or off ?<br />
	<select name="form_version">
	<option value='on' <?PHP if($cfo[form_version] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_version] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Classyfrieds categories are important. They group listings enhance user experience and provide extra SEO power. If you turn this feature off all listings will end up in the first created category.</div>
	<div class="rrow">
	turn category <strong>selector</strong> on or off ?<br />
	<select name="form_cats">
	<option value='on' <?PHP if($cfo[form_cats] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_cats] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
	<br />
	<div class="lrow">
	As the directory owner it is hard to think of all the categories you would need. With this feature you can have users create their own categories if needed, or they can select from existing ones.</div>
	<div class="rrow">
	allow category <strong>creation</strong> by users ?<br />
	<select name="allow_create_cats">
	<option value='on' <?PHP if($cfo[allow_create_cats] == 'on') echo 'selected'; ?> >yes</option >
	<option value='off' <?PHP if($cfo[allow_create_cats] == 'off') echo 'selected'; ?> >no</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	additional info gives the user another large box to enter 'small print' information. This typically shows up in small italic print underneath the description.</div>
	<div class="rrow">
	allow users to enter <strong>additional info</strong> ?<br />
	<select name="form_additional_info">
	<option value='on' <?PHP if($cfo[form_additional_info] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_additional_info] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	The url box allows users to enter a website address. In certain layouts this URL is automatically hotlinked (with a 'nofollow' tag).</div>
	<div class="rrow">
	allow users to enter a <strong>website address</strong> ?<br />
	<select name="form_url">
	<option value='on' <?PHP if($cfo[form_url] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_url] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	Sometimes users may need to enter pricing info. When turned on the users gets two smaller boxes. One for the round numbers (e.g. Euro's) and one for the cents/pennies etc.</div>
	<div class="rrow">
	show a <strong>'price' field</strong> ?<br />
	<select name="form_pricing_field">
	<option value='on' <?PHP if($cfo[form_pricing_field] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_pricing_field] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	An images says more than a thousand words ! Allow users to upload an image to enhance their listing ? (these are copied to your server)</div>
	<div class="rrow">
	Allow upload of <strong>images</strong> ?<br />
	<select name="form_image_field">
	<option value='Allow_1_image' <?PHP if($cfo[form_image_field] == 'Allow_1_image') echo 'selected'; ?> >Allow 1 image</option >
	<option value='Allow_2_images' <?PHP if($cfo[form_image_field] == 'Allow_2_images') echo 'selected'; ?> >Allow 2 images</option >
	<option value='Allow_3_images' <?PHP if($cfo[form_image_field] == 'Allow_3_images') echo 'selected'; ?> >Allow 3 images</option >
	<option value='no_image_upload' <?PHP if($cfo[form_image_field] == 'no_image_upload') echo 'selected'; ?> >no_image_upload</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>

<div class="row">
	<div class="lrow">
	keywords are useful when trying to find certain listings. You can allow users to enter a list of keywords.</div>
	<div class="rrow">
	show a <strong>keywords field</strong> ?<br />
	<select name="form_keywords_field">
	<option value='on' <?PHP if($cfo[form_keywords_field] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_keywords_field] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>
<div class='cf_classyclear'></div>

<div class="row">
	<div class="lrow">
	Listings can be set to expire automatically after a certain time. With optional premium modules your visitors can be asked to pay to extend this exiration time.</div>
	<div class="rrow">
	set standard<strong>Post expiration</strong> time<br />
	<select name="form_post_expiration">
	<option value='604800' <?PHP if($cfo[form_post_expiration] == '604800') echo 'selected'; ?> >1 week</option >
	<option value='2419200' <?PHP if($cfo[form_post_expiration] == '2419200') echo 'selected'; ?> >4 weeks</option >
	<option value='7776000' <?PHP if($cfo[form_post_expiration] == '7776000') echo 'selected'; ?> >90 days</option >
	<option value='31536000' <?PHP if($cfo[form_post_expiration] == '31536000') echo 'selected'; ?> >1 year</option >
	<option value='3153600000' <?PHP if($cfo[form_post_expiration] == '3153600000') echo 'selected'; ?> >100 year</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>


<div class="row">
	<div class="lrow">
	Featured listings stand out by either a bright border, a banner or other feature. How a features listing is marked depends on the stylesheet you activated. This is normally only a paid feature.</div>
	<div class="rrow">
	show a <strong>'Make Featured' checkbox</strong> ?<br />
	<select name="form_featured_field">
	<option value='off' <?PHP if(empty($cfo[form_featured_field])) echo 'selected'; ?> >---</option >
	<option value='on' <?PHP if($cfo[form_featured_field] == 'on') echo 'selected'; ?> >on</option >
	<option value='off' <?PHP if($cfo[form_featured_field] == 'off') echo 'selected'; ?> >off</option >
	</select>
	</div>
	<div class='cf_clearclear'></div>
</div>
<div class='cf_classyclear'></div>


<?php wp_nonce_field('classy_pages_uf','cfnonce'); ?>
<input type="submit" value="Submit changes">
</form>
</div>

<br /><br />

</div>

<?PHP include('sidebar.php'); ?>