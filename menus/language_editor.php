<?PHP 
if ( ! is_admin() ) die ('sorry, only admins allowed');
global $wpdb;
$cfl = get_option('classyfrieds_language');
$cfo = get_option('classyfrieds_options'); 

// writing custom_lang & switch to custom file
if (! empty($_POST) && wp_verify_nonce($_POST['cfnonce'],'classy_lang') )
	{
	$line = '<?PHP' . "\n\r";
	foreach ($cfl as $key => $val)
		{
		$line .= '$cfl[\'' . $key . '\'] = "' . $_POST[$key] . '";' . "\n\r";
		}
	$line .= "\n\r" . '?>';
	// create a custom language file in wp_uploads
		/* set to abandon in favor of copying all settings on deactivation
		$uploads = wp_upload_dir();
		if (!is_dir($uploads['basedir'] . "/classyfrieds/language/")) { if (!mkdir($uploads['basedir'] . "/classyfrieds/language",  0775, TRUE)) die('cannot create dir');}
		$filen = $uploads['basedir'] . "/classyfrieds/language/lang_CUSTOMIZED.php" ;	
		*/
	$filen = plugin_dir_path( dirname(__FILE__) )  . '/themefiles/language/lang_CUSTOMIZED.php' ;
	$fp = fopen($filen, 'w');
	fwrite($fp, $line);
	fclose($fp);
	$cfo = get_option('classyfrieds_options');
	$cfo[language] = 'lang_CUSTOMIZED';
	// re-fill language array
	$cfl = array();
	include(plugin_dir_path( dirname(__FILE__) ) . '/themefiles/language/'. $cfo['language'] . '.php');
	update_option('classyfrieds_language',$cfl);
	
	$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " LANGUAGES : a new customized language file was created !. <br/>";
	update_option('classyfrieds_options', $cfo);
	echo "<div style='border:2px solid black;background-color:yellow;width:60%;margin:4px auto;padding:3px;text-align:center'>Default language has been switched to lang_CUSTOMIZED !</div>";
	}
?>

<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/css/admin_menus.css', __FILE__); ?>" />

<div class="classyfrieds_menu_left">
	<div class="center">
	<h1>LANGUAGE EDITOR </h1>(this language editor is still in Beta-testing. Please report any and all issues at <a href='http://classyfrieds.com/forums/'>http://classyfrieds.com/forums/</a><br />
	<br />
	<?PHP echo "currently active language file is : " . $cfo[language]; ?>
	<br />
	<br />

	Please read instructions to the right first !

	<form method="POST">	
	<?php wp_nonce_field('classy_lang','cfnonce'); ?><br/>
	<?PHP	
	// list all options
	$cfl = get_option('classyfrieds_language');
	$submc = 0;
	foreach ($cfl as $key => $val)
		{
		$submc++; if ($submc > 10) {$submc = 0; echo '<div style="margin:5px"><input type="submit" value="WRITE THIS TEXT TO CUSTUM_LANG"></div>'; }
		echo "<div class='classykey'>$key :</div><div class='classyval'><input type='text' name='$key' value='$val'></div><div class='cf_clearclear'></div>";
		}	
	?>
	
	<input type="submit" value="save this text as lang_CUSTOMIZED">
	</form>
</div>
</div>


<div class="classyfrieds_menu_right">
<img src='<?PHP echo plugins_url('/images/logo.jpg', __FILE__); ?>' width="80" style="float:right;border:2px solid blue;margin:3px"/>
<h2>Language instructions</h2><br /><br />
<div style='background-color:lightyellow;border:1px solid red'>
<h3>How to change language items</h3>
The language variables on the left are copied from your CURRENTLY ACTIVE language file. Once you make changes to these settings and hit the save button on the bottom, your new language settings 
will be written to a file called 'lang_CUSTOMIZED'. This 'lang_CUSTOMIZED' will now instantly become the actively selected language to be used in your classyfrieds system.<br/><br/>
You can customize your language further at any time by making changes to the language fields here.<br>
<h3>I am still confused, help me !</h3>
Suppose you have a dutch language file, and you are not happy with the standard texts :<br/><br/>
1. Select your dutch language file in layouts & styles, then save **<br />
2. visit this language editor and change the fields you need, then save it.<br />
3. Your active language is switched to 'lang_CUSTOMIZED' and changes take effect immmediately.<br /><br />
<small>To change language back to a standard file : visit the layouts & styles tab</small>
</div>

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