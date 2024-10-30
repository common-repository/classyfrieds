<?PHP
// classyfrieds extender
// a way for visitors to extend listing beyond current expiration
$cfo = get_option('classyfrieds_options'); // general options
$cfads = get_option('classyfrieds_ads'); // ads
$cfch = get_option('cpc_data'); // financial
$cfl = get_option('classyfrieds_language'); // language

// process form input
if (wp_verify_nonce($_POST['extendfield'],'extedit') )
	{
	$my_post['ID'] = $post->ID;
	$my_post['post_date'] = date('Y-m-d H:i:s');
	$my_post['post_date_gmt'] = date('Y-m-d H:i:s');
	wp_update_post( $my_post );
	echo "<div style='width:80%;margin:3px auto;padding:10px;background-color:lightgreen;color:green;border:solid 2px black'>". $cfl[bump_done] . "</div>";
	}
?>


<a href="#x" class="cf_overlay" id="extend_form"></a>
<div class="cf_popup">
<div style='text-align:center'>
<div style='float:left;margin:-40px 0 0 -70px'>
	<img src='<?PHP echo plugins_url('/images/extend.png', __FILE__);?>'>
	</div>
	<div style='float:left'>
	
	</div>
	

		<img src='<?PHP echo $img; ?>' width='80'><br/><?PHP echo $rtitle; ?>

<div style='clear:both'></div>

<?PHP
if ( ($cfch[charge_extend] == 'yes') && function_exists('classyfrieds_paid_content') )
	{
	?>	
	<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<br />
	<?PHP echo $cfch[extend_explain]; ?><br />
	<select name="amount">
	<option value='<?PHP echo $cfch[extend_week_cost]/100; ?>' ><?PHP echo $cfl['extend_week']; ?> <?PHP echo $cfch[extend_week_cost]; ?> <?PHP echo $cfch[epilogue]; ?></option>
	<option value='<?PHP echo $cfch[extend_month_cost]/100; ?>' ><?PHP echo $cfl['extend_month']; ?> <?PHP echo $cfch[extend_month_cost]; ?> <?PHP echo $cfch[epilogue]; ?></option>
	<option value='<?PHP echo $cfch[extend_6months_cost]/100; ?>' ><?PHP echo $cfl['extend_6months']; ?> <?PHP echo $cfch[extend_6months_cost]; ?> <?PHP echo $cfch[epilogue]; ?></option>
	<option value='<?PHP echo $cfch[extend_year_cost]/100; ?>' ><?PHP echo $cfl['extend_year']; ?> <?PHP echo $cfch[extend_year_cost]; ?> <?PHP echo $cfch[epilogue]; ?></option>
	</select>
	<br />
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="<?PHP echo $cfch[ppleml]; ?> ">
	<input type="hidden" name="currency_code" value="<?PHP echo $cfch[curr]; ?>">
	<input type="hidden" name="return" value="<?PHP echo site_url() . '/cf_ppl_ok'; ?> ">
	<input type="hidden" name="item_name" value="<?PHP echo $post->ID . '|' . $rtitle;?>" >
	<input type="hidden" name="notify_url" value="<?PHP echo site_url() . '/cf_ipn_extend'; ?> ">
	<input type="submit" value="<?PHP echo $cfch[extend_proceed]; ?>">
	</form>
	<?PHP
	}
else
	{
	?>
	<form method='POST'>
	<br />
	<?PHP echo $cfl[extend_cf_popup]; ?><br />
	<br />
	 <?php wp_nonce_field('extendit','extendfield'); ?>
	<input type="submit" value="<?PHP echo $cfl[sendextend]; ?>">
	</form>
	<?PHP
	}
?>
</div>
<a class="cf_close" href="#close"></a>
</div>