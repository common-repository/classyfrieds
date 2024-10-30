<?PHP
// classyfrieds blind-mail
// a way for visitors to contact listing owners without exposing e-mail
$cfo = get_option('classyfrieds_options'); // general options
$cfads = get_option('classyfrieds_ads'); // ads
$cfch = get_option('cpc_data'); // financial
$cfl = get_option('classyfrieds_language'); // language
?>


<a href="#x" class="cf_overlay" id="edit_form"></a>
<div class="cf_popup">
<!-- classyfrieds edit listing | http://classyfrieds.com -->
<div style='text-align:center'>
<div style='float:left;margin:-40px 0 0 -70px'>
	<img src='<?PHP echo plugins_url('/images/edit.png', __FILE__);?>'>
	</div>
<?PHP	
if ( is_user_logged_in() ) 
	{
	// process form input
	if (wp_verify_nonce($_POST['editfield'],'editthis') )
		{
		// check if user logged in
		// check if user is author
		$my_post = array();
		$my_post['ID'] = get_the_ID();
		$my_post['post_content'] = $_POST['content'];	
		wp_update_post( $my_post );
		update_post_meta(get_the_ID(), 'subtitle', $_POST['subtitle'] , TRUE); 
		}
	if (wp_verify_nonce($_POST['editfield'],'deletethis') )
		{
	
		}
	
	echo "<div>EDIT :  $rtitle</div>";
	echo "<div><img src='$img' width='80'></div>";
	echo "<div style='clear:both'></div>";
	echo "author : ". $post->post_author;
	$user_id = get_current_user_id();
	if ($user_id == $post->post_author)
		{
		?>
		<br />
		<form method='POST'>
		change subtitle<br />
		<input type='text' name='subtitle' value='<?PHP echo $sub; ?>' style='width:90%'><br />
		add to content<br />
		<textarea name='content' style='width:90%' rows='10'><?PHP echo get_the_content(); ?></textarea>
		<?php wp_nonce_field('editthis','editfield'); ?><br/>
		<div style='float:left;width:48%;text-align:center;border:2px solid green;margin-right:2%;'>
		<input type='submit' value='<?PHP echo $cfl['f_submit']; ?>'>
		<br><br><img src='<?PHP echo plugins_url('/images/submit.png', __FILE__);?>' width="70%">
		</form>
		</div>
		<div style='float:left;width:48%;text-align:center;border:2px dashed red'>	
		<form method='POST'>
		<input type='submit' value='<?PHP echo $cfl['f_delete']; ?>'>
		<br><br><img src='<?PHP echo plugins_url('/images/trash.png', __FILE__);?>' width="70%">
		<?php wp_nonce_field('deletethis','editfield'); ?>
		</form>
		</div>
		<div style='clear:both'></div>
		<?PHP
		}
	else
		{
		?>
		<h2>Claim this listing</h2>
		It seems you are not (yet) authorized to edit this listing !<br />
		If you believe you should get authorized or if this listing is about you (and was maybe placed by someone else) you can request a security-review to become registered as the author of this post.<br />
		You may incurr a small fee for this review as all requests are processed manually.<br>
		<?PHP
		}
	}
else
	{
	?>
	<h2>You are not logged in</h2>
	We need to verify that you are the author of this post, or that you are authorized to make changes to this post before we can continue !
	<br /><a href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login">Log-in to proceed</a>
	<?PHP
	}
?>
</div>
<a class="cf_close" href="#close"></a>
</div>