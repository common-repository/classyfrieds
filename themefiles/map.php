<?PHP
// classyfrieds map cf_popup
?>
<style>
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#map_canvas {
  height: 100%;
}

@media print {
  html, body {
    height: auto;
  }

  #map_canvas {
    height: 650px;
  }
}
</style>
 <a href="#x" class="cf_overlay" id="map_form"></a>
 <div class="cf_popup" style='text-align:center'>
	<div style='float:left;margin:-40px 0 0 -70px'>
	<img src='<?PHP echo plugins_url('/images/globe.png', __FILE__);?>'>
	</div>
	<?PHP 
	echo "<div style='float:left'><img src='$img' width='80'></div>";
	echo "<div style='float:left;font-size:22px;margin:10px 0px 0px 10px'>$contactname</div>"; 
	echo "<div style='clear:both'></div>";
	
	if (function_exists('cf_mapme') )
		{cf_mapme($cregion , $country);}
	else
		{
		?>
		<br/><h2>We're Sorry !</h2><br/>The website operator has chosen<br/> not to activate the map function at this time<br/><br/>
		<img src='<?PHP echo plugins_url('/images/nomap.jpg', __FILE__);?>'>
		<?PHP
		}
	?>
	
	
	
	
	
	
	
	
	<a class="cf_close" href="#close"></a>
</div>