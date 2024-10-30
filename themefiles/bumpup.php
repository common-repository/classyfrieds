<?PHP
// classyfrieds blind-mail
// a way for visitors to contact listing owners without exposing e-mail
$cfo = get_option('classyfrieds_options'); // general options
$cfads = get_option('classyfrieds_ads'); // ads
$cfch = get_option('cpc_data'); // financial
$cfl = get_option('classyfrieds_language'); // language

// process form input
if (wp_verify_nonce($_POST['bumpfield'],'bumpup') )
	{
	$my_post['ID'] = $post->ID;
	$my_post['post_date'] = date('Y-m-d H:i:s');
	$my_post['post_date_gmt'] = date('Y-m-d H:i:s');
	wp_update_post( $my_post );
	echo "<div style='width:80%;margin:3px auto;padding:10px;background-color:lightgreen;color:green;border:solid 2px black'>". $cfl[bump_done] . "</div>";

	}
?>



<a href="#x" class="cf_overlay" id="bump_form"></a>
<div class="cf_popup">
<div style='text-align:center'>
<div style='float:left;margin:-40px 0 0 -70px'>
	<img src='<?PHP echo plugins_url('/images/totop.png', __FILE__);?>'>
	</div>
<?PHP		
echo "<div> $rtitle</div>";
echo "<div><img src='$img' width='80'></div>";
echo "<div style='clear:both'></div>";
?>
<br/>
<?PHP
if ( ($cfch['charge_bumping'] == 'yes') && function_exists('classyfrieds_paid_content') )
	{
	// charge for bumping (requires paid content plugin)
	?>
	<form name="_xclick2" action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<br />
	<?PHP echo $cfl[bumpup_cf_popup]; ?><br />
	<?PHP echo $cfch[bump_explain] . $cfch[bumping_cost] . $cfch[epilogue]; ?>
	
	<br />
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="<?PHP echo $cfch[ppleml]; ?> ">
	<input type="hidden" name="currency_code" value="<?PHP echo $cfch[curr]; ?>">
	<input type="hidden" name="return" value="<?PHP echo site_url() . '/cf_ppl_ok'; ?> ">
	<input type="hidden" name="item_name" value="<?PHP echo $post->ID . '|' . $rtitle;?>" >
	<input type="hidden" name="amount" value="<?PHP echo $cfch[bumping_cost]/100; ?>">
	<input type="hidden" name="notify_url" value="<?PHP echo site_url() . '/cf_ipn_bumping'; ?> ">
	<input type="submit" value="<?PHP echo $cfch[bump_proceed]; ?>">
	</form>
	<?PHP
	}
else
	{
	?>
	<form method='POST'>
	<br />
	<?PHP echo $cfl[bumpup_cf_popup]; ?><br />
	<br />
	 <?php wp_nonce_field('bumpup','bumpfield'); ?>
	<input type="submit" value="<?PHP echo $cfl[sendbump]; ?>">
	</form>
	<?PHP
	}
?>
</div>
<a class="cf_close" href="#close"></a>
</div>