<?PHP
// load options
global $post;
$cfo = get_option('classyfrieds_options'); // general options
$cfads = get_option('classyfrieds_ads'); // ads
$cfch = get_option('cpc_data'); // financial
$cfl = get_option('classyfrieds_language'); // language
$sub = get_post_meta($post->ID,'subtitle',TRUE);
$ver = get_post_meta($post->ID,'version',TRUE);
$price = get_post_meta($post->ID,'price',TRUE);
$adtl = get_post_meta($post->ID,'additional',TRUE);
$furl = get_post_meta($post->ID,'url',TRUE);
$tags = get_the_tag_list('tags: ',', ', '');
$contactname = get_post_meta($post->ID,'cname',TRUE);
//$cname = "<a href='#TB_inline?height=500&width=400&inlineId=contactmail' class='thickbox' title='$cfl[contacttitle]'> $contactname </a>"; // V 2.9 removed thickbox in favor of css3
$cname = "<a href='#email_form' id='login_pop' title='$cfl[contacttitle]'>$contactname<img src='" . plugins_url('/images/smmail.png', __FILE__) . "' style='margin-bottom:-12px;padding:1px 5px'></a>";
$cmail = get_post_meta($post->ID,'cmail',TRUE);
$cregion = get_post_meta($post->ID,'cregion',TRUE);
$country = get_post_meta($post->ID,'country',TRUE);
$img = get_post_meta($post->ID,'foto',TRUE);
$broken = plugins_url('/images/noimg.png', __FILE__);
if (empty($img)) $img = $broken;
$img2 = get_post_meta($post->ID,'foto2',TRUE);
if (empty($img2)) $img2 = 'no image';
$img3 = get_post_meta($post->ID,'foto3',TRUE);
if (empty($img3)) $img3 = 'no image';
$rtitle = get_the_title();
$rperm = get_permalink();
$rcats = get_the_term_list( $post->ID, 'classycats', 'category: ', ', ', '' );
$rexcerpt = $cfads[cf_ads_main_before] . get_the_excerpt() . $cfads[cf_ads_main_after];

$content = $cfads[cf_ads_main_before] . get_content() . $cfads[cf_ads_main_after]; // special function

$mapper = "<a href='#map_form' id='login_pop' title='$cfl[locate]'><img src='" . plugins_url('/images/globe64.png', __FILE__) . "' class='cf_largehover'></a>";


// ratings
if (function_exists( 'cf_return_author_love' ) ) $rating = cf_return_author_love( $post->ID); else $rating = '';

// expiration -> $expire_time
$expire_time = get_post_meta($post->ID,'_cf_expire',TRUE) - time();
	
	if (empty($expire_time) || ($expire_time < -360000) ) $expire_time = $cfl[expires] . "??" ;
	elseif ($expire_time < (60*60*48) ) 
	{$expire_time = $cfl[expires] . (int) ($expire_time / ( 60*60 )) . " $cfl[hours]" ;}
	else
	{$expire_time = $cfl[expires] . (int) ($expire_time / ( 60*60*24 )) . " $cfl[days]" ;}

// eexpiration extender
if ($cfo['show_extend'] == 'yes') 
	{ // $extender = "<a href='#TB_inline?height=500&width=400&inlineId=extlist' class='thickbox' title='$cfl[extend_explain]'> ( $cfl[extend] )</a>"; // V 2.9 removed thickbox in favor of css3
	$extender = "<a href='#extend_form' id='login_pop' title='$cfl[extend_explain]'> ( $cfl[extend] )</a>";
	}
else 
	{$extender = '';}

// listing bumpup	
if ($cfo[show_bumpup] == 'yes') 
	{// $bumpup = "<a href='#TB_inline?height=500&width=400&inlineId=bumpup' class='thickbox' title='$cfl[bumpup_explain]'> $cfl[bumpup] </a>"; // V 2.9 removed thickbox in favor of css3
	$bumpup = "<a href='#bump_form' id='login_pop' title='$cfl[bumpup_explain]'> $cfl[bumpup] </a>";
	}
else 
	{$bumpup = '';}

// listing feature
if ($cfo[show_featured] == 'yes') 
	{// $show_featured = "<a href='#TB_inline?height=500&width=400&inlineId=featureme' class='thickbox' title='$cfl[featured_explain]'> $cfl[featured] </a>"; // V 2.9 removed thickbox in favor of css3
	$show_featured = "<a href='#featured_form' id='login_pop' title='$cfl[featured_explain]'> $cfl[featured] </a>";
	}
else 
	{$show_featured = '';}

// listing edit
if (empty($cfl[editme])) $cfl[editme] = 'edit';	
$edit_listing = "<a href='#edit_form' id='login_pop' title='$cfl[edit_explain]'> $cfl[editme] </a>";

// post counter
if (function_exists('cf_getPostViews')) $postviews = cf_getPostViews($post->ID);

// is post featured ? then apply css to the style
$is_feature = get_post_meta($post->ID,'_cf_featured',TRUE);
if ($is_feature > time() )$featured = "style='" . $cfo['cfo_featured_css'] . "' "; else $featured = '';

$taggit = array('{{title}}','{{subt}}','{{ver}}','{{perm}}','{{cats}}','{{img}}','{{img2}}','{{img3}}','{{excerpt}}','{{content}}','{{tags}}','{{price}}','{{contactname}}','{{contactmail}}','{{contactregion}}','{{country}}','{{adtl}}','{{broken}}','{{rating}}','{{url}}', '{{expire}}','{{bumpup}}','{{extender}}','{{postviews}}','{{makefeatured}}','{{featured}}','{{edit}}','{{map}}');
$tagto  = array($rtitle    , $sub   , $ver      , $rperm   , $rcats   , $img   , $img2   , $img3   , $rexcerpt   ,     $content     ,  $tags     ,   $price  ,    $cname       ,     $cmail      ,     $cregion    , $country ,  $adtl ,  $broken    , $rating    , $furl, $expire_time, $bumpup    ,    $extender  , $postviews ,   $show_featured   ,  $featured  ,  $edit_listing , $mapper);

// pull in the blind mailing system and other cf_popup goodies
include('blind_mail.php');
if ($cfo['show_extend'] == 'yes') include('extender.php');
if ($cfo['show_bumpup'] == 'yes') include('bumpup.php');
if ($cfo['show_featured'] == 'yes') include('make_featured.php');
if ( is_user_logged_in() ) include('edit_listing.php');
include('map.php');
?>

