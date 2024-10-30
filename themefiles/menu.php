<?PHP
// user menu - classyfrieds

// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');
?>


<div class='classyfrieds_menu'>
<a href='<?PHP echo $cfo[listings_permalink]; ?>' title='listing page'><?PHP echo $cfl[m_listings]; ?></a>
<a href='<?PHP echo $cfo[add_items_permalink]; ?>' title='add a listing'><?PHP echo $cfl[m_add_listings]; ?></a>
<?PHP 
if ($cfo['show_search_in_menu'] == 'yes')
	{
	?>
	<br />
	<div style='margin:1px auto'>
		<form method="get" class='topmenu_search' action="<?php echo home_url( '/' ); ?>">
		<input type="text" class="field" name="s" id="s" value="<?PHP echo $cfl['search']; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">
		</form>
		<div class='cf_clearclear'></div>
	</div>
	<?PHP
	}
	?>
</div>
<div class='cf_clearclear'></div>