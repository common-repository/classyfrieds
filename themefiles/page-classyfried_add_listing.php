<?php
// this is the add-a-listing page for classyfrieds

// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');

// wp generic
global $current_user;
wp_get_current_user();	
get_header(); 

?>
<link rel="stylesheet" type="text/css" href="<?PHP echo plugins_url('/layouts/'. $cfo['css_color'] . '.css', __FILE__) ; ?>" />

<div class="classyfrieds_addlisting" <?PHP if ($cfo['cf_sidebars'] == 'off' ) echo "style='width:100%'";?>  >
<?PHP include('menu.php'); ?>
<div class="cf_classyclear"></div>

<script language="JavaScript">
function setVisibility(id, visibility) {
document.getElementById(id).style.display = visibility;
}
</script>

<?PHP 

// process form input
if (wp_verify_nonce($_POST['classyfried-field'],'classy_nonce') )
  {
    // process form data
    //echo $cfl[processing].$_POST['categorie'];
    
    // check if post already exists
    global $wpdb;
	$uploads = wp_upload_dir();
    $number = $wpdb->get_var($wpdb->prepare( "SELECT ID FROM wp_posts WHERE post_title = '" . $_POST['titel'] . "' " ) );
    if ( !empty($number) ) {$error .= $cfl[title_exists];}
    
    if (strlen($_POST['titel']) < 3) $error .= $cfl[tooshort];

    // check price field
    if (empty($_POST['prijs'])) $_POST['prijs'] == "0";
    
    // check foto upload
	$image = 0;
	if ($_FILES['foto']['error'] == "0")
		{
		if (($_FILES['foto']['size'] < 2000000) && ($_FILES['foto']['type'] == 'image/gif' || $_FILES['foto']['type'] == 'image/jpeg' || $_FILES['foto']['type'] == 'image/jpg' || $_FILES['foto']['type'] == 'image/png'))    
			{$image = 1;} else{$error .= $cfl[nopic] ;}
		}
    
    // check foto2 upload
    if ($_FILES['foto2']['error'] == "0")
		{
		if (($_FILES['foto2']['size'] < 2000000) && ($_FILES['foto']['type'] == 'image/gif' || $_FILES['foto']['type'] == 'image/jpeg' || $_FILES['foto']['type'] == 'image/jpg' || $_FILES['foto']['type'] == 'image/png'))    
			{$image = 1;} else{$error .= $cfl[nopic] ;}
		}
    
    if ($_FILES['foto3']['error'] == "0")
		{
		// check foto3 upload
		if (($_FILES['foto3']['size'] < 2000000) && ($_FILES['foto']['type'] == 'image/gif' || $_FILES['foto']['type'] == 'image/jpeg' || $_FILES['foto']['type'] == 'image/jpg' || $_FILES['foto']['type'] == 'image/png'))    
			{$image = 1;} else{$error .= $cfl[nopic] ;}
		}
   
	if(empty($error))
	   {
	   if ($image == 1) {	
			 // valid foto - move it
			if (!is_dir($uploads['basedir'] . "/classyfrieds")) {
			 if (!mkdir($uploads['basedir'] . "/classyfrieds",  0775, TRUE)) die('cannot create dir');
			 }
			if (!is_dir($uploads['basedir'] . "/classyfrieds/" . $current_user->user_login)) {
			if (!mkdir($uploads['basedir'] . "/classyfrieds/" . $current_user->user_login, 0775, TRUE)) {
				$error .= $cfl[nostor] ;
				}
			}
			if(empty($error)) {move_uploaded_file($_FILES["foto"]["tmp_name"], $uploads['basedir'] . "/classyfrieds/" . $current_user->user_login . "/" . str_replace(" ","-",$_FILES["foto"]["name"]) );}
			if(empty($error)) { if ($_FILES['foto2']['error'] == "0"){move_uploaded_file($_FILES["foto2"]["tmp_name"], $uploads['basedir'] . "/classyfrieds/" . $current_user->user_login . "/" . str_replace(" ","-",$_FILES["foto2"]["name"]) );} }
			if(empty($error)) { if ($_FILES['foto3']['error'] == "0"){move_uploaded_file($_FILES["foto3"]["tmp_name"], $uploads['basedir'] . "/classyfrieds/" . $current_user->user_login . "/" . str_replace(" ","-",$_FILES["foto3"]["name"]) );} }		 
			}
		 
		if(empty($error))
		   {   
			// check if paid content
			if (function_exists('classyfrieds_paid_content'))
				{
				// force post pending until paid
				$cfo[auto_publish] = 'pending';
				}
			
			// are we creating a new category ?
			if (!empty($_POST['create_cat']))
				{
				$newcat = wp_insert_term($_POST['create_cat'],'classycats' );
				$_POST['categorie'] = $newcat['term_id'];
				}
			
			
			$term = get_term_by( 'id', $_POST['categorie'], 'classycats' );
			//then get the term_id
			$term_id = $term->term_id;				
			// tags
			$tags = $_POST['sleutelwoorden'];
			$tags = str_replace(" ",",",$tags);
					
			 //create post			
			   $post = array(
			  'post_title' => $_POST['titel'],
			  'post_author' => $current_user->ID,
			  'post_content' => $_POST['omschrijving'],
			  'post_type' => 'classyfrieds',
			  'post_status' => $cfo[auto_publish],
			  'tags_input' => $tags,
			  'tax_input' => array('classycats' => $term_id)
			  );
			  
			  // create a WP post out of it			 
			  $the_new_id = wp_insert_post( $post);			  
			  
			  $uploads = wp_upload_dir();
			  if ($image == 1) 
				{
				 add_post_meta($the_new_id, 'foto', $uploads['baseurl'] . "/classyfrieds/" . $current_user->user_login . "/" . str_replace(" ","-",$_FILES["foto"]["name"]), TRUE);
				 if ($_FILES['foto2']['error'] == "0"){add_post_meta($the_new_id, 'foto2', $uploads['baseurl'] . "/classyfrieds/" . $current_user->user_login . "/" . str_replace(" ","-",$_FILES["foto2"]["name"]), TRUE);}
				 if ($_FILES['foto3']['error'] == "0"){add_post_meta($the_new_id, 'foto3', $uploads['baseurl'] . "/classyfrieds/" . $current_user->user_login . "/" . str_replace(" ","-",$_FILES["foto3"]["name"]), TRUE);}
				}
				
			add_post_meta($the_new_id, 'price', $_POST['prijs'] , TRUE);
			add_post_meta($the_new_id, 'cregion', $_POST['zip'] , TRUE);
			add_post_meta($the_new_id, 'country', $_POST['country'] , TRUE); 
			add_post_meta($the_new_id, 'version', $_POST['versie'] , TRUE); 
			add_post_meta($the_new_id, 'subtitle', $_POST['subtitle'] , TRUE); 
			add_post_meta($the_new_id, 'additional', $_POST['installatie'] , TRUE); 
			add_post_meta($the_new_id, 'url', $_POST['url'] , TRUE); 
			add_post_meta($the_new_id, 'cmail', $_POST['contactmail'] , TRUE); 			
			add_post_meta($the_new_id, 'cname', $_POST['contactname'] , TRUE);
			if (empty($_POST['expire'])) {$time = time() + $cfo[form_post_expiration];}
				else {$time = time() + $_POST['expire'];}
			add_post_meta($the_new_id, '_cf_expire', $time , TRUE);
			if (empty($cfo['feature_expiration'])) $feature_time = time() + (60*60*24*30); else $feature_time = time() + $cfo['feature_expiration'];
			if ($_POST['featured'] == 'yes') add_post_meta($the_new_id, '_cf_featured', $feature_time , TRUE);
			
			if('yes' == $cfo['new_listing_to_admin'])
				{
				add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
				$to = $cfo['admin_email'];
				$subject = 'your classyfrieds-plugin has notifications !';
				$message = "this is the classyfrieds-plugin from your blog : " . get_option('blogname') . " with a notification !
				<br />
				<strong>A new listing was added. Please moderate.</strong>
				";
				$headers = "From: wordpress classyfrieds <$to> \r\n";
				$mail = wp_mail($to, $subject, $message, $headers);
				$cfo = get_option('classyfrieds_options');
				$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " NEW LISTING recorded and email sent to admin. <br/>";
				update_option('classyfrieds_options', $cfo);
				}
			}   
	   }
	   
	if (!empty($error)){ 
		echo "<div style='width:80%;margin:3px auto;padding:10px;background-color:yellow;color:red;border:solid 2px black'><h3>" . $cfl[ereporting] . "</h3>$error</div>";
	}
	elseif (function_exists('classyfrieds_paid_content')) {
		echo "<div style='width:80%;margin:3px auto;padding:10px;background-color:lightgreen;color:green;border:solid 2px black'>" . $cfl[listadded_ppl] . "</div>";
		classyfrieds_paid_content('user_form',$the_new_id , $_POST['titel']);
	}
	else { 
		echo "<div style='width:80%;margin:3px auto;padding:10px;background-color:lightgreen;color:green;border:solid 2px black'>". $cfl[listadded] . "</div>";
		
		// paid content ? reroute to paypal		
	}  

				
	}
	
// show form	
if (! is_user_logged_in() && $cfo['allow_visitors'] == "no") 
{
echo "<div class='classyfrieds_menu'>";
echo $cfl[mustregister];  
echo "<br/>";
wp_login_form();
echo "<br/>";
wp_register();
echo "</div>";
}  
else
{
?>
  
<script language="javascript"> 
 function tog(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>
<form method="post" enctype="multipart/form-data" class="formy">
<h2><?PHP echo $cfl[uf_welcome] . $current_user->user_login; ?> !</h2><?PHP echo $cfl[uf_welcome_sub]; ?><br />
<?PHP
if (function_exists('classyfrieds_paid_content')) 
	{
	?>
	<div class="rowy">		
		<?PHP echo $cfch[explain]; ?>
		<div class="cf_clearclear"></div>
	</div>
	<?PHP
	}
?>	
<div class="rowy">
	<div class="lefty">
		<?PHP echo $cfl[f_title]; ?>
	</div>
	<div class="righty">
		<input type="text" name="titel" class="inputy" maxlength="50" required value="<?PHP echo $_POST['titel']; ?>" >
	</div>
	<div class="cf_clearclear"></div>
</div>
  
<?PHP if ($cfo['form_subtitle'] =="on") { ?>
<div class="rowy">  
	<div class="lefty">
		<?PHP echo $cfl[f_subtitle]; ?>
	</div>
	<div class="righty">
		<input type="text" name="subtitle" class="inputy" maxlength="50" value="<?PHP echo $_POST['subtitle']; ?>">
	</div>
	<div class='charge'><?PHP echo $cfch[subtitle] . $cfch[epilogue]; ?></div> 
	<div class="cf_clearclear"></div>
</div>
<?PHP } ?>
  
<div class="rowy">  
	<?php /*
		// switching to wysiwyg editor
	<div class="lefty">
		<?PHP echo $cfl[f_descr]; ?>
	</div>
	<div class="righty">
		<textarea name="omschrijving" class="texty" rows="8" required ><?PHP echo $_POST['omschrijving']; ?></textarea>		
	</div>
	*/
	?>
	<div style='float:left;width:91%'>	
	<?PHP echo $cfl[f_descr]; ?>
	<style>
	.switch-html{display:none}
	.mceIframeContainer{background-color:white}
	.editor_class{
	}
	</style>
	<?php 
	$editorstyle = '<style type="text/css">
           body{margin:0;padding:0;background-color:white}
           </style>';    
	wp_editor($descr, 'omschrijving', array('media_buttons' => false,'textarea_name' => 'omschrijving', 'textarea_rows' => 10 ,'editor_css' => $editorstyle, 'editor_class' => 'wpeditor') ); ?>
	</div>
	
	
	<div class='charge'></div> 
	<div class="cf_clearclear"></div>
</div>
 
<?PHP if ($cfo['form_country'] =="on") { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_country]; ?>
		</div>
		<div class="righty">
			<select id="country" name="country" class="inputy" maxlength="49">
			<option value="">..... ? .....</option>
			<option value="Afghanistan">Afghanistan</option>
			<option value="Åland_Islands">Åland Islands</option>
			<option value="Albania">Albania</option>
			<option value="Algeria">Algeria</option>
			<option value="American_Samoa">American Samoa</option>
			<option value="Andorra">Andorra</option>
			<option value="Angola">Angola</option>
			<option value="Anguilla">Anguilla</option>
			<option value="Antarctica">Antarctica</option>
			<option value="Antigua_And_Barbuda">Antigua and Barbuda</option>
			<option value="Argentina">Argentina</option>
			<option value="Armenia">Armenia</option>
			<option value="Aruba">Aruba</option>
			<option value="Australia">Australia</option>
			<option value="Austria">Austria</option>
			<option value="Azerbaijan">Azerbaijan</option>
			<option value="Bahamas">Bahamas</option>
			<option value="Bahrain">Bahrain</option>
			<option value="Bangladesh">Bangladesh</option>
			<option value="Barbados">Barbados</option>
			<option value="Belarus">Belarus</option>
			<option value="Belgium">Belgium</option>
			<option value="Belize">Belize</option>
			<option value="Benin">Benin</option>
			<option value="Bermuda">Bermuda</option>
			<option value="Bhutan">Bhutan</option>
			<option value="Bolivia">Bolivia</option>
			<option value="Bosnia_And_Herzegovina">Bosnia and Herzegovina</option>
			<option value="Botswana">Botswana</option>
			<option value="Bouvet_Island">Bouvet Island</option>
			<option value="Brazil">Brazil</option>
			<option value="British_Indian_Ocean_Territory">British Indian Ocean Territory</option>
			<option value="Brunei_Darussalam">Brunei Darussalam</option>
			<option value="Bulgaria">Bulgaria</option>
			<option value="Burkina_Faso">Burkina Faso</option>
			<option value="Burundi">Burundi</option>
			<option value="Cambodia">Cambodia</option>
			<option value="Cameroon">Cameroon</option>
			<option value="Canada">Canada</option>
			<option value="Cape_Verde">Cape Verde</option>
			<option value="Cayman_Islands">Cayman Islands</option>
			<option value="Central_African_Republic">Central African Republic</option>
			<option value="Chad">Chad</option>
			<option value="Chile">Chile</option>
			<option value="China">China</option>
			<option value="Christmas_Island">Christmas Island</option>
			<option value="Cocos_(Keeling)_Islands">Cocos (Keeling) Islands</option>
			<option value="Colombia">Colombia</option>
			<option value="Comoros">Comoros</option>
			<option value="Congo">Congo</option>
			<option value="Congo,_The_Democratic_Republic_Of_The">Congo, The Democratic Republic of The</option>
			<option value="Cook_Islands">Cook Islands</option>
			<option value="Costa_Rica">Costa Rica</option>
			<option value="Cote_D'ivoire">Cote D'ivoire</option>
			<option value="Croatia">Croatia</option>
			<option value="Cuba">Cuba</option>
			<option value="Cyprus">Cyprus</option>
			<option value="Czech_Republic">Czech Republic</option>
			<option value="Denmark">Denmark</option>
			<option value="Djibouti">Djibouti</option>
			<option value="Dominica">Dominica</option>
			<option value="Dominican_Republic">Dominican Republic</option>
			<option value="Ecuador">Ecuador</option>
			<option value="Egypt">Egypt</option>
			<option value="El_Salvador">El Salvador</option>
			<option value="Equatorial_Guinea">Equatorial Guinea</option>
			<option value="Eritrea">Eritrea</option>
			<option value="Estonia">Estonia</option>
			<option value="Ethiopia">Ethiopia</option>
			<option value="Falkland_Islands_(Malvinas)">Falkland Islands (Malvinas)</option>
			<option value="Faroe_Islands">Faroe Islands</option>
			<option value="Fiji">Fiji</option>
			<option value="Finland">Finland</option>
			<option value="France">France</option>
			<option value="French_Guiana">French Guiana</option>
			<option value="French_Polynesia">French Polynesia</option>
			<option value="French_Southern_Territories">French Southern Territories</option>
			<option value="Gabon">Gabon</option>
			<option value="Gambia">Gambia</option>
			<option value="Georgia">Georgia</option>
			<option value="Germany">Germany</option>
			<option value="Ghana">Ghana</option>
			<option value="Gibraltar">Gibraltar</option>
			<option value="Greece">Greece</option>
			<option value="Greenland">Greenland</option>
			<option value="Grenada">Grenada</option>
			<option value="Guadeloupe">Guadeloupe</option>
			<option value="Guam">Guam</option>
			<option value="Guatemala">Guatemala</option>
			<option value="Guernsey">Guernsey</option>
			<option value="Guinea">Guinea</option>
			<option value="Guinea-bissau">Guinea-bissau</option>
			<option value="Guyana">Guyana</option>
			<option value="Haiti">Haiti</option>
			<option value="Heard_Island_And_Mcdonald_Islands">Heard Island and Mcdonald Islands</option>
			<option value="Holy_See_(Vatican_City_State)">Holy See (Vatican City State)</option>
			<option value="Honduras">Honduras</option>
			<option value="Hong_Kong">Hong Kong</option>
			<option value="Hungary">Hungary</option>
			<option value="Iceland">Iceland</option>
			<option value="India">India</option>
			<option value="Indonesia">Indonesia</option>
			<option value="Iran,_Islamic_Republic_Of">Iran, Islamic Republic of</option>
			<option value="Iraq">Iraq</option>
			<option value="Ireland">Ireland</option>
			<option value="Isle_Of_Man">Isle of Man</option>
			<option value="Israel">Israel</option>
			<option value="Italy">Italy</option>
			<option value="Jamaica">Jamaica</option>
			<option value="Japan">Japan</option>
			<option value="Jersey">Jersey</option>
			<option value="Jordan">Jordan</option>
			<option value="Kazakhstan">Kazakhstan</option>
			<option value="Kenya">Kenya</option>
			<option value="Kiribati">Kiribati</option>
			<option value="Korea,_Democratic_People's_Republic_Of">Korea, Democratic People's Republic of</option>
			<option value="Korea,_Republic_Of">Korea, Republic of</option>
			<option value="Kuwait">Kuwait</option>
			<option value="Kyrgyzstan">Kyrgyzstan</option>
			<option value="Lao_People's_Democratic_Republic">Lao People's Democratic Republic</option>
			<option value="Latvia">Latvia</option>
			<option value="Lebanon">Lebanon</option>
			<option value="Lesotho">Lesotho</option>
			<option value="Liberia">Liberia</option>
			<option value="Libyan_Arab_Jamahiriya">Libyan Arab Jamahiriya</option>
			<option value="Liechtenstein">Liechtenstein</option>
			<option value="Lithuania">Lithuania</option>
			<option value="Luxembourg">Luxembourg</option>
			<option value="Macao">Macao</option>
			<option value="Macedonia,_The_Former_Yugoslav_Republic_Of">Macedonia, The Former Yugoslav Republic of</option>
			<option value="Madagascar">Madagascar</option>
			<option value="Malawi">Malawi</option>
			<option value="Malaysia">Malaysia</option>
			<option value="Maldives">Maldives</option>
			<option value="Mali">Mali</option>
			<option value="Malta">Malta</option>
			<option value="Marshall_Islands">Marshall Islands</option>
			<option value="Martinique">Martinique</option>
			<option value="Mauritania">Mauritania</option>
			<option value="Mauritius">Mauritius</option>
			<option value="Mayotte">Mayotte</option>
			<option value="Mexico">Mexico</option>
			<option value="Micronesia,_Federated_States_Of">Micronesia, Federated States of</option>
			<option value="Moldova,_Republic_Of">Moldova, Republic of</option>
			<option value="Monaco">Monaco</option>
			<option value="Mongolia">Mongolia</option>
			<option value="Montenegro">Montenegro</option>
			<option value="Montserrat">Montserrat</option>
			<option value="Morocco">Morocco</option>
			<option value="Mozambique">Mozambique</option>
			<option value="Myanmar">Myanmar</option>
			<option value="Namibia">Namibia</option>
			<option value="Nauru">Nauru</option>
			<option value="Nepal">Nepal</option>
			<option value="Netherlands">Netherlands</option>
			<option value="Netherlands_Antilles">Netherlands Antilles</option>
			<option value="New_Caledonia">New Caledonia</option>
			<option value="New_Zealand">New Zealand</option>
			<option value="Nicaragua">Nicaragua</option>
			<option value="Niger">Niger</option>
			<option value="Nigeria">Nigeria</option>
			<option value="Niue">Niue</option>
			<option value="Norfolk_Island">Norfolk Island</option>
			<option value="Northern_Mariana_Islands">Northern Mariana Islands</option>
			<option value="Norway">Norway</option>
			<option value="Oman">Oman</option>
			<option value="Pakistan">Pakistan</option>
			<option value="Palau">Palau</option>
			<option value="Palestinian_Territory,_Occupied">Palestinian Territory, Occupied</option>
			<option value="Panama">Panama</option>
			<option value="Papua_New_Guinea">Papua New Guinea</option>
			<option value="Paraguay">Paraguay</option>
			<option value="Peru">Peru</option>
			<option value="Philippines">Philippines</option>
			<option value="Pitcairn">Pitcairn</option>
			<option value="Poland">Poland</option>
			<option value="Portugal">Portugal</option>
			<option value="Puerto_Rico">Puerto Rico</option>
			<option value="Qatar">Qatar</option>
			<option value="Reunion">Reunion</option>
			<option value="Romania">Romania</option>
			<option value="Russian_Federation">Russian Federation</option>
			<option value="Rwanda">Rwanda</option>
			<option value="Saint_Helena">Saint Helena</option>
			<option value="Saint_Kitts_And_Nevis">Saint Kitts and Nevis</option>
			<option value="Saint_Lucia">Saint Lucia</option>
			<option value="Saint_Pierre_And_Miquelon">Saint Pierre and Miquelon</option>
			<option value="Saint_Vincent_And_The_Grenadines">Saint Vincent and The Grenadines</option>
			<option value="Samoa">Samoa</option>
			<option value="San_Marino">San Marino</option>
			<option value="Sao_Tome_And_Principe">Sao Tome and Principe</option>
			<option value="Saudi_Arabia">Saudi Arabia</option>
			<option value="Senegal">Senegal</option>
			<option value="Serbia">Serbia</option>
			<option value="Seychelles">Seychelles</option>
			<option value="Sierra_Leone">Sierra Leone</option>
			<option value="Singapore">Singapore</option>
			<option value="Slovakia">Slovakia</option>
			<option value="Slovenia">Slovenia</option>
			<option value="Solomon_Islands">Solomon Islands</option>
			<option value="Somalia">Somalia</option>
			<option value="South_Africa">South Africa</option>
			<option value="South_Georgia_And_The_South_Sandwich_Islands">South Georgia and The South Sandwich Islands</option>
			<option value="Spain">Spain</option>
			<option value="Sri_Lanka">Sri Lanka</option>
			<option value="Sudan">Sudan</option>
			<option value="Suriname">Suriname</option>
			<option value="Svalbard_And_Jan_Mayen">Svalbard and Jan Mayen</option>
			<option value="Swaziland">Swaziland</option>
			<option value="Sweden">Sweden</option>
			<option value="Switzerland">Switzerland</option>
			<option value="Syrian_Arab_Republic">Syrian Arab Republic</option>
			<option value="Taiwan,_Province_Of_China">Taiwan, Province of China</option>
			<option value="Tajikistan">Tajikistan</option>
			<option value="Tanzania,_United_Republic_Of">Tanzania, United Republic of</option>
			<option value="Thailand">Thailand</option>
			<option value="Timor-leste">Timor-leste</option>
			<option value="Togo">Togo</option>
			<option value="Tokelau">Tokelau</option>
			<option value="Tonga">Tonga</option>
			<option value="Trinidad_And_Tobago">Trinidad and Tobago</option>
			<option value="Tunisia">Tunisia</option>
			<option value="Turkey">Turkey</option>
			<option value="Turkmenistan">Turkmenistan</option>
			<option value="Turks_And_Caicos_Islands">Turks and Caicos Islands</option>
			<option value="Tuvalu">Tuvalu</option>
			<option value="Uganda">Uganda</option>
			<option value="Ukraine">Ukraine</option>
			<option value="United_Arab_Emirates">United Arab Emirates</option>
			<option value="United_Kingdom">United Kingdom</option>
			<option value="United_States">United States</option>
			<option value="United_States_Minor_Outlying_Islands">United States Minor Outlying Islands</option>
			<option value="Uruguay">Uruguay</option>
			<option value="Uzbekistan">Uzbekistan</option>
			<option value="Vanuatu">Vanuatu</option>
			<option value="Venezuela">Venezuela</option>
			<option value="Viet_Nam">Viet Nam</option>
			<option value="Virgin_Islands,_British">Virgin Islands, British</option>
			<option value="Virgin_Islands,_U.S.">Virgin Islands, U.S.</option>
			<option value="Wallis_And_Futuna">Wallis and Futuna</option>
			<option value="Western_Sahara">Western Sahara</option>
			<option value="Yemen">Yemen</option>
			<option value="Zambia">Zambia</option>
			<option value="Zimbabwe">Zimbabwe</option>
			</select>
		</div>
		<div class='charge'></div> 
		<div class="cf_clearclear"></div>
		
		<?PHP // zip or region 
		if ($cfo['form_zip'] =="on") { ?>
		<div class="lefty">
			<?PHP echo $cfl[f_zip]; ?>
		</div>
		<div class="righty">
			<input type="text" name="zip" class="inputy" maxlength="50" value="<?PHP echo $_POST['zip']; ?>">
		</div>
		<div class='charge'><?PHP echo $cfch[zip] . $cfch[epilogue];?></div> 
		<div class="cf_clearclear"></div>
		<?PHP } ?>
		
	</div>
<?PHP } ?>
 
<?PHP // version
	if ($cfo['form_version'] =="on") { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_ymm]; ?>
		</div>
		<div class="righty">
			<input type="text" name="versie" class="inputy" maxlength="50" value="<?PHP echo $_POST['versie']; ?>">
		</div>
		<div class='charge'><?PHP echo $cfch[version] . $cfch[epilogue];?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>
  
<?PHP // system categories
	if ($cfo['form_cats'] =="on") { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_cat]; ?>
		</div>
		<div class="righty">
			<?php wp_dropdown_categories('hide_empty=0&name=categorie&id=type&class=inputy&show_count=1&hierarchical=1&taxonomy=classycats'); ?>
		</div>
		<div class='charge'><?PHP echo $cfch[category] . $cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
			<?PHP if ($cfo['allow_create_cats'] =="on") { ?>
				<div class="lefty">
					<?PHP echo $cfl[f_create_cat]; ?>
				</div>
				<div class="righty">
					<input type="text" name="create_cat" class="inputy" maxlength="49" value="<?PHP echo $_POST['create_cat']; ?>">
				</div>
				<div class='charge'><?PHP echo $cfch[create_category] . $cfch[epilogue]; ?></div> 
				<div class="cf_clearclear"></div>		
			<?PHP } ?>
	</div>
<?PHP } ?>
  
<?PHP // additional txt
	if ($cfo['form_additional_info'] =="on") { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_smpr]; ?>
		</div>
		<div class="righty">
			<textarea name="installatie" class="texty" rows="6" ><?PHP echo $_POST['installatie']; ?></textarea>
		</div>
		<div class='charge'><?PHP echo $cfch[additional] . $cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>
  
<?PHP if ($cfo['form_url'] =="on") { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_url]; ?>
		</div>
		<div class="righty">
			<input type="url" name="url" class="inputy" maxlength="50" value="<?PHP echo $_POST['url']; ?>">
		</div>
		<div class='charge'><?PHP echo $cfch[url] . $cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_pricing_field'] =="on") { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_price]; ?> 
		</div>
		<div class="righty">
			<input type="text" name="prijs" size="7" maxlength="7" value="<?PHP echo $_POST['prijs']; ?>"><?PHP echo $cfl[f_prexpl]; ?>
		</div>
		<div class='charge'><?PHP echo $cfch[price] . $cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_image_field'] == 'Allow_1_image' || $cfo['form_image_field'] == 'Allow_2_images' || $cfo['form_image_field'] == 'Allow_3_images' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_img]; ?>
		</div>
		<div class="righty">
			<input type="file" name="foto" ><?PHP echo $cfl[f_imgonly]; ?>
		</div>
		<div class='charge'><?PHP echo $cfch[image1] . $cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_image_field'] == 'Allow_2_images' || $cfo['form_image_field'] == 'Allow_3_images' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_img]; ?>
		</div>
		<div class="righty">
			<input type="file" name="foto2"><?PHP echo $cfl[f_imgonly]; ?>
		</div>
		<div class='charge'><?PHP echo $cfch[image2] .$cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_image_field'] == 'Allow_3_images' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_img]; ?>
		</div>
		<div class="righty">
			<input type="file" name="foto3"><?PHP echo $cfl[f_imgonly]; ?>
		</div>
		<div class='charge'><?PHP echo $cfch[image3].$cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_keywords_field'] == 'on' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_key]; ?>
		</div>
		<div class="righty">
			<input type="text" name="sleutelwoorden" class="inputy" maxlength="50" value="<?PHP echo $_POST['sleutelwoorden']; ?>" ><?PHP echo $cfl[f_keyexpl]; ?>
		</div>
		<div class='charge'><?PHP echo $cfch[keywords].$cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_contactname'] == 'on' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_contactname].$cfch[epilogue]; ?>
		</div>
		<div class="righty">
			<input type="text" name="contactname" class="inputy" maxlength="50" value="<?PHP if (!empty($current_user->user_login)) echo $current_user->user_login; else echo $_POST['contactname']; ?>" >
		</div>
		<div class='charge'></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_contactmail'] == 'on' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_contactmail]; ?>
		</div>
		<div class="righty">
			<input type="text" name="contactmail" class="inputy" maxlength="50" value="<?PHP if (!empty($current_user->user_email)) echo $current_user->user_email; else echo $_POST['contactmail']; ?>" >
		</div>
		<div class='charge'><?PHP echo $cfch[contactmail].$cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>

<?PHP if ($cfo['form_featured_field'] == 'on' ) { ?>
	<div class="rowy">  
		<div class="lefty">
			<?PHP echo $cfl[f_featured]; ?>
		</div>
		<div class="righty">
			<select name="featured" style="width:60%;margin:10px auto">
			<option value="yes" > Yes, feature this listing </option>
			<option value="no" > NO, make it a normal listing </option>
			</select>
		</div>
		<div class='charge'><?PHP echo $cfch[featured].$cfch[epilogue]; ?></div> 
		<div class="cf_clearclear"></div>
	</div>
<?PHP } ?>
 
 <?php wp_nonce_field('classy_nonce','classyfried-field'); ?>
  <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
  
  <br>
   <div id="progress" style="display:none;text-align:center"><img src='<?PHP echo plugins_url('/images/wait.gif', __FILE__); ?>' title='please hold'></div>
  <div class="rowy"><?PHP echo $cfl[f_disclaimer]; ?><input type="submit" name="toevoegen" value="<?PHP echo $cfl[f_submit]; ?>" onclick="setVisibility('progress', 'inline');" class="submit"></div>
  </form>

<?PHP 
if (wp_verify_nonce($_POST['classyfried-delme'],'classy_nonce2') )
	{	
	if (!wp_delete_post( $_POST['delme'], TRUE )) echo "<div class='classyfrieds_menu'>FAILED TO DELETE - come back later</div>";
	else echo "<div class='classyfrieds_menu'>POST ".$_POST['delme']." WAS PERMANENTLY DELETED !</div>";
	}
// get all posts from this author
global $wpdb;
$curus = $current_user->ID;
if ($curus != "0")
	{
	$numposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_author = $curus");
	echo "<div class='classyfrieds_menu'>My active listings</div>";
	foreach ($numposts as $numpost) 
		{
		//print_R($numpost);
			if ($numpost->post_type == 'classyfrieds')
				{
				$img = get_post_meta($numpost->ID, 'foto',TRUE);
				?>
				<div style='width:80%;border:2px solid #CCC;margin:4px auto;padding:4px'>
				<div style='float:left;width:20%;height:100px;overflow:hidden'>
					<a href='<?PHP echo $numpost->guid; ?>' title='GO TO THIS LISTING'><img src='<?PHP echo $img; ?>' width='80'></a>
				</div>
				
				<div style='float:left;width:70%;height:100px;overflow:hidden'>
				<a href='<?PHP echo $numpost->guid; ?>' title='GO TO THIS LISTING'><h3><?PHP echo $numpost->post_title; ?></h3>
				<?PHP echo $cfl[f_visit]; ?></a><br />
					<form method='POST'>
					<input type='hidden' value='<?PHP echo $numpost->ID; ?>' name='delme'>
					<?php wp_nonce_field('classy_nonce2','classyfried-delme'); ?>
					<input type='submit' value='delete this listing'>
					</form>
				</div>
				<div style='clear:both'></div>
				</div>
				<?PHP
				}
		}
	}
else
	{
	echo "<div class='classyfrieds_menu'>". $cfl[noreg] . "</div>";
	}










} // end if user is logged in
?> 				
</div><!-- #primary -->


<?php 
if ($cfo['cf_sidebars'] != 'off' )
include('add_listing_sidebar.php'); 
?>


<div class="cf_classyclear"></div>
<?php get_footer(); ?>