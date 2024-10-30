<?PHP
// classyfrieds blind-mail
// a way for visitors to contact listing owners without exposing e-mail


// process form input
if (wp_verify_nonce($_POST['bmfield'],'blindmailform') )
  {
  add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
	$to = $cmail;
	$subject = 'notice from a visitor: '. $_POST[ m_title] ;
	$message = "A visitor responded to your listing over at : " . get_option('blogname') . " !
	<br /><br /><strong>" . $_POST[m_body] . "</strong>";
	$headers = "From: " . $_POST[m_myname] . " <" . $_POST[m_mymail] . "> \r\n";
	$mail = wp_mail($to, $subject, $message, $headers);
	echo "<div style='width:80%;margin:3px auto;padding:10px;background-color:lightgreen;color:green;border:solid 2px black'>". $cfl[mailsent] . "</div>";
  }
?>

 <a href="#x" class="cf_overlay" id="email_form"></a>
 <div class="cf_popup">
	<div style='float:left;margin:-40px 0 0 -70px'>
	<img src='<?PHP echo plugins_url('/images/mail.png', __FILE__);?>'>
	</div>
	<?PHP 
	echo "<div style='float:left'><img src='$img' width='80'></div>";
	echo "<div style='float:left;font-size:22px;margin:10px 0px 0px 10px'>$cfl[to] <br/>$contactname</div>"; 
	echo "<div style='clear:both'></div>";
	?>
	<form method='POST'>
	<br />
	<?PHP echo $cfl[mailtitle]; ?><br />
	<input type='text' name='m_title' style='width:90%'><br />
	<?PHP echo $cfl[mailmessage]; ?><br />
	<textarea name='m_body' rows='10'style='width:90%'></textarea>
	<br />
	<?PHP echo $cfl[mailyourname]; ?><br />
	<input type='text' name='m_myname' style='width:90%'>
	<br />
	<?PHP echo $cfl[mailyourmail]; ?><br />
	<input type='email' name='m_mymail' style='width:90%'>
	<br />
	 <?php wp_nonce_field('blindmailform','bmfield'); ?>
	<br />
	<input type="submit" value="<?PHP echo $cfl[sendmail]; ?>">
	</form>
	<a class="cf_close" href="#close"></a>
</div>