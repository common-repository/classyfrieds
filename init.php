<?PHP

// initialising classyfrieds by Pete Scheepens

function classyfrieds_database() {	
	/*
	// function removed in V 2.6 in favor of hidden meta values
	global $wpdb;
	$table_name = $wpdb->prefix . "classyfrieds";
      
	$sql = "CREATE TABLE $table_name (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	post_id mediumint(9) NOT NULL,
	author_id mediumint(9) NOT NULL,
	time_in int(11),
	time_expire int(11),
	pay_cents mediumint(9) NOT NULL,
	UNIQUE KEY id (id)
	);";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   */
}

// create the custom posts
function create_classyfrieds_type() 
	{		
	if(!post_type_exists('classyfrieds')) 
		{	
			register_post_type('classyfrieds', 
			array(	
			'description' => 'classified listings for the classyfried system',
			'public' => true,
			// 'menu_position' => 6, PS 22-08-2012  http://wordpress.org/support/topic/plugin-classyfrieds-no-link-in-admin-ui?replies=6
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'query_var' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes',),
			'taxonomies' => array('post_tag'),
			'labels' => array (
			  'name' => 'classyfrieds',
			  'singular_name' => 'classyfried',
			  'menu_name' => 'classyfrieds',
			  'add_new' => 'Add classyfried',
			  'add_new_item' => 'Add New classyfried',
			  'edit' => 'Edit',
			  'edit_item' => 'Edit classyfried',
			  'new_item' => 'New classyfried',
			  'view' => 'View classyfried',
			  'view_item' => 'View classyfried',
			  'search_items' => 'Search classyfrieds',
			  'not_found' => 'No classyfrieds Found',
			  'not_found_in_trash' => 'No classyfrieds Found in Trash',
			  'parent' => 'Parent classyfried',
			),) );
		}
	
	if (!taxonomy_exists('classycats'))	
		{
		 // Add new taxonomy, make it hierarchical (like categories)
		  $labels = array(
			'name' => _x( 'classycats', 'taxonomy general name' ),
			'singular_name' => _x( 'classycat', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search classycats' ),
			'all_items' => __( 'All classycats' ),
			'parent_item' => __( 'Parent classycat' ),
			'parent_item_colon' => __( 'Parent classycat:' ),
			'edit_item' => __( 'Edit classycats' ), 
			'update_item' => __( 'Update classycats' ),
			'add_new_item' => __( 'Add New classycats' ),
			'new_item_name' => __( 'New classycats Name' ),
			'menu_name' => __( 'classycats' ),
		  ); 
		  
		// and tie the new taxo to the posts
		  register_taxonomy('classycats','classyfrieds', array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'classycats' ),
		  ));
		}
	}

// add the custom posts to the regular post queries BUT ONLY IF ARCHIVE (e.g. tags)
add_filter( 'pre_get_posts', 'my_get_posts' );
	
function my_get_posts( $query ) {
	if ( ( is_tag() || is_author() ) && empty( $query->query_vars['suppress_filters'] ) )
		$query->set( 'post_type', array( 'post', 'classyfrieds' ) );
	return $query;
}

// add classyfrieds listings to the general search results
add_filter( 'the_search_query', 'classy_search' );

function classy_search( $query ) {
	if ( $query->is_search ) {
		$query->set( 'post_type', array( 'post', 'page', 'feed', 'classyfrieds' ));
	}
	return $query;
}


function create_classypages() 
	{
		$cfo = get_option('classyfrieds_options');
		if(!get_page_by_title('listings') && empty($cfo[listings_permalink]) )	{
			// create pages
			$classyfried_listingpage = array(
			'post_type' => 'page',
			'post_title' => 'listings',
			'post_content' => '',
			'comment_status' => 'closed',
			'post_status' => 'publish',
			'post_author' => 1,  );
			// Insert the post into the database
			$classyf2 = wp_insert_post( $classyfried_listingpage );
			// write permalink to options	
			$cfo = get_option('classyfrieds_options');
			$cfo[listings_permalink] = get_permalink( $classyf2 );
			$cfo[listings_slug] = 'listings';
			update_option('classyfrieds_options', $cfo);
		}
		
		if(!get_page_by_title('add_a_listing') && empty($cfo[add_items_permalink]) )	{
			// create pages
			$classyfried_listingpage = array(
			'post_type' => 'page',
			'post_title' => 'add_a_listing',
			'post_content' => '',
			'comment_status' => 'closed',
			'post_status' => 'publish',
			'post_author' => 1,  );
			// Insert the post into the database
			$classyf3 = wp_insert_post( $classyfried_listingpage );
			// write permalink to options
			$cfo = get_option('classyfrieds_options');
			$cfo[add_items_permalink] = get_permalink( $classyf3 );
			$cfo[add_items_slug] = 'add_a_listing';
			update_option('classyfrieds_options', $cfo);
		}
	}

function classyfrieds_redirect() {
    global $wp;
	$cfo = get_option('classyfrieds_options');
	
	// force redirect to listings when requested
	if (($cfo[reroute_MAIN] == 'INTERCEPTING_FRONT_PAGE') && ( is_home() ) ){
	wp_redirect( $cfo['listings_permalink'], 302 );}
 
	
	
    $plugindir = dirname( __FILE__ );

	// route 'category' (classycats) calls
    if (!empty($wp->query_vars["classycats"])) {
        $templatefilename = 'cats-listing.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        classy_redirect($return_template);
	}
		
	// route single view
	if ( $wp->query_vars["post_type"] == 'classyfrieds') {
		$templatefilename = 'single-classyfrieds.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        classy_redirect($return_template);
	}
		
	// route listing view
	if ($wp->query_vars["pagename"] == $cfo[listings_slug] ) {
        $templatefilename = 'classyfried_listings.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        classy_redirect($return_template);
    }
	
	// route add listing page
	if ($wp->query_vars["pagename"] == $cfo[add_items_slug] ) {
        $templatefilename = 'page-classyfried_add_listing.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        classy_redirect($return_template);
    }
}

function force_main_redirect() {
	if ( is_home() ) {
	?>
	<script type="text/javascript">
	   <!--
		  window.location= <?php echo "'" . $cfo['listings_permalink'] . "'"; ?>;
	   //-->
	   </script>
	<?PHP ;
	}
	/*
	$plugindir = dirname( __FILE__ );
	if (is_front_page() )
		{
		$templatefilename = 'classyfried_listings.php';
		if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
			$return_template = TEMPLATEPATH . '/' . $templatefilename;
		} else {
			$return_template = $plugindir . '/themefiles/' . $templatefilename;
		}
		classy_redirect($return_template);
		}
	*/
}


function classy_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}	

// fill default values
function classyfrieds_initialise() {
	// fill general data
	$cfo = get_option('classyfrieds_options');	
	if (empty($cfo[listings_slug])) $cfo[listings_slug] = 'listings';
	if (empty($cfo[add_items_slug])) $cfo[add_items_slug] = 'add_a_listing';
	if (empty($cfo[show_menu])) $cfo[show_menu] = 'yes';  // yes || no - shows menu above listings page
	if (empty($cfo['show_comments'])) $cfo['show_comments'] = 'no'; // show comments on classyfrieds ? yes || no
	if (empty($cfo['show_search_in_menu'])) $cfo['show_search_in_menu'] = 'yes';
	if (empty($cfo['cf_sidebars'])) $cfo['cf_sidebars'] = 'on'; // sidebars on || off
	if (empty($cfo[listings_per_page])) $cfo[listings_per_page] = "12"; // int 1-99 - amount of listings per page (3 columns)
	if (empty($cfo['listing_layout'])) $cfo['listing_layout'] = "listing_horizontal_bars"; // populates automatically from layout dir
	if (empty($cfo['listing_layout_single'])) $cfo['listing_layout_single'] = "single_default"; // populates automatically from layout dir
	if (empty($cfo['css_color'])) $cfo['css_color'] = "classyfrieds_blue"; // populates automatically from layout dir
	if (empty($cfo['cfo_featured_css'] )) $cfo['cfo_featured_css'] = "border:4px dashed yellow;background-color:lightyellow"; // css for featured
	if (empty($cfo['listing_layout_taxonomy'])) $cfo['listing_layout_taxonomy'] = 'taxonomy_default';
	if (empty($cfo['allow_visitors'])) $cfo['allow_visitors'] = 'no'; // yes || no - allows unregistered upload of listing
	if (empty($cfo['auto_publish'])) $cfo['auto_publish'] = 'publish'; // 'draft' | 'publish' | 'pending'| 'future' | 'private'
	if (strlen($cfo['language']) < 5) $cfo['language'] = 'lang_english'; // 	
	if (empty($cfo['form_subtitle'])) $cfo['form_subtitle'] = 'on';
	if (empty($cfo['form_version'])) $cfo['form_version'] = "on"; // on || off form-field -> version
	if (empty($cfo['form_country'])) $cfo['form_country'] = "on"; // on || off form-field -> country
	if (empty($cfo['form_zip'])) $cfo[form_zip] = 'on'; // on || off form-field -> zip
	if (empty($cfo['form_cats'])) $cfo['form_cats'] = "on"; // on || off form-field 
	if (empty($cfo['allow_create_cats'])) $cfo['allow_create_cats'] = "off";// on || off form-field 
	if (empty($cfo['form_additional_info'])) $cfo['form_additional_info'] = "on"; // on || off form-field 
	if (empty($cfo['form_url'])) $cfo['form_url'] = "on" ; // on || off URL field
	if (empty($cfo['form_pricing_field'])) $cfo['form_pricing_field'] = "on"; // on || off form-field 
	if (empty($cfo['form_image_field'])) $cfo['form_image_field'] = 'Allow_3_images'; // 1 || 2 || 3 || off
	if (empty($cfo['form_keywords_field'])) $cfo['form_keywords_field'] = 'on'; // on || off
	if (empty($cfo['form_featured_field'])) $cfo['form_featured_field'] = 'on'; // on || off
	if (empty($cfo['form_post_expiration'])) $cfo['form_post_expiration'] = '31536000'; // post expiration in seconds
	if (empty($cfo['form_featured_field'])) $cfo['form_featured_field'] = 'no'; 
	if (empty($cfo['show_extend'])) $cfo['show_extend'] = 'yes'; // bumpup
	if (empty($cfo['charge_extend'])) $cfo['charge_extend'] = 'no'; // bumpup
	if (empty($cfo['show_featured'])) $cfo['show_featured'] = 'yes'; // bumpup
	if (empty($cfo['charge_featured'])) $cfo['charge_featured'] = 'no'; // bumpup
	if (empty($cfo['show_bumpup'])) $cfo['show_bumpup'] = 'yes'; // bumpup
	if (empty($cfo['charge_bumpup'])) $cfo['charge_bumpup'] = 'no'; // bumpup
	if (empty($cfo['reroute_MAIN'])) $cfo['reroute_MAIN'] = 'no'; // no || INTERCEPTING_FRONT_PAGE  - intercept calls to front page
	if (empty($cfo['admin_email'])) $cfo['admin_email'] = get_bloginfo('admin_email');
	if (empty($cfo['log_to_admin'])) $cfo['log_to_admin'] = 'weekly'; // off/daily/weekly
	if (empty($cfo['new_listing_to_admin'])) $cfo['new_listing_to_admin'] = 'yes'; // no || yes
	
	$cfo['form_contactname'] = 'on';
	$cfo['form_contactmail'] = 'on';
	update_option('classyfrieds_options',$cfo);
	
	// fill language Array
	$cfl = array();
	include(plugin_dir_path(__FILE__) . '/themefiles/language/'. $cfo['language'] . '.php');
	update_option('classyfrieds_language',$cfl);
	
	// build the 2 classyfrieds pages (again) if not exists
	create_classypages();
	
	// create cpt if needed
	create_classyfrieds_type();
		
	//Ensure the $wp_rewrite global is loaded
	global $wp_rewrite;
	//Call flush_rules() as a method of the $wp_rewrite object
	$wp_rewrite->flush_rules();
	
	$cfo = get_option('classyfrieds_options');
	$cfo['error_log'] .= date("F j, Y, g:i a",time() ) . " full system flush recorded. <br/>";
	update_option('classyfrieds_options', $cfo);
	
}
	
register_sidebar(array(
  'name' => __( 'classyfrieds sidebar' ),
  'id' => 'classybar',
  'description' => __( 'Widgets in here are only shown in the classyfrieds listing system, typically next to the listings, categories and archives.' ),
  'before_title' => '<h1>',
  'after_title' => '</h1>',
  'before_widget' => '<div class="classy_box">',
  'after_widget'  => '</div>'
));

register_sidebar(array(
  'name' => __( 'classyfrieds add listing sidebar' ),
  'id' => 'add_listing_classybar',
  'description' => __( 'this sidebar only shows next to the ADD listings form in the classyfrieds system.' ),
  'before_title' => '<h1>',
  'after_title' => '</h1>',
  'before_widget' => '<div class="classy_box">',
  'after_widget'  => '</div>'
));
