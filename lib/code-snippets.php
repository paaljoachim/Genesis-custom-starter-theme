<?php

/*---- Various Code snippets that should be placed into an external custom functions plugin.---*/


/*--------- The shorter codes -----*/


/******* For starting up a new site!
*
* By default show Kitchen Sink in WYSIWYG Editor...https://core.trac.wordpress.org/ticket/12207 */
function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );


/** For starting up a new site! - content lasts for an hour.
 * Adds Lorem Ipsum text to blank pages
 *
 * https://www.wp-code.com/wordpress-snippets/automatically-add-lorem-ipsum-text-to-blank-wordpress-pages/ 
 * @param string $content - the page's current contents
 * @return string
 */
 
 function emw_custom_filter_the_content ($content) {
     if ($content == '') {
         if ($c = get_transient ('lipsum'))
             return $c;
         $content = wp_remote_get ('http://www.lipsum.com/feed/json');
         if (!is_wp_error($content)) {
             $content = json_decode (str_replace ("\n", '</p><p>', $content['body']));
             $content = '<p>'.$content->feed->lipsum.'</p>';
             set_transient ('lipsum', $content, 3600); // Cache the text for one hour
             return $content;
         }
     } else
         return $content;
 }

add_filter ('the_content', 'emw_custom_filter_the_content');



//* Create a shortcode to display a custom Go to top link
add_shortcode('footer_custombacktotop', 'set_footer_custombacktotop');
function set_footer_custombacktotop($atts) {
   return '
     <a href=”#” class=”back-to-top” style=”display: inline;”>
     <i class=”fa fa-arrow-circle-up”></i>
     </a>
   ';
}
add_action('wp_footer', 'go_to_top');
function go_to_top() { ?>
     <script type="text/javascript">
        jQuery(function($) {
          $('.tooltip').click(function() {
             $('html, body').animate({scrollTop:0}, 'slow');
             return false;
          });
        });
     </script>
<?php }





/************ NOT SURE IF THIS WORKS------?
* Automatic update of themes, plugins and major WP versions.
*
* http://www.wpwhitesecurity.com/wordpress-tutorial/guide-configuring-wordpress-automatic-updates/
*
*************************/

/* Allow major updates to be automatic updated */
add_filter('allow_major_auto_core_updates', '__return_true' );

/* Allow themes to be automatic updated */
add_filter( 'auto_update_theme', '__return_true');

/* Allow plugins to be automatic updated */
add_filter( 'auto_update_plugin', '__return_true' );


/********************************************************************
* replace WordPress Howdy
* http://www.trickspanda.com/2014/01/change-howdy-text-wordpress-admin-bar/
*
********************************************************************/
function change_howdy($translated, $text, $domain) {

    if (!is_admin() || 'default' != $domain)
        return $translated;
    if (false !== strpos($translated, 'Howdy'))
        return str_replace('Howdy', 'Welcome to the Backend of WordPress', $translated);
    return $translated;
}
add_filter('gettext', 'change_howdy', 10, 3);


/*-----------------
*
* Bottom of backend Admin screen -  Custom admin footer credits https://github.com/gregreindel/greg_html5_starter 
*
---------------*/

add_filter( 'admin_footer_text', create_function( '$a', 'return \'<span id="footer-thankyou">Site managed by <a href="http://www.easywebdesigntutorials.com" target="_blank">Paal Joachim Romdahl </a><span> | Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>\';' ) );



/*---------- The longer codes ----*/


/********** DUPLICATE POST 
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 *******************************/
function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
 
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
 
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );
 
		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
 
		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
function rd_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
}
 
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );


/**************************  https://github.com/cosmic/cosmic-tinymce-excerpt
  * Plugin Name: Cosmic TinyMCE Excerpt
  * Description: TinyMCE pour les extraits
  * Author: Agence Cosmic
  * Author URI: http://agencecosmic.com/
  * Version: 1.0
  ****************************/
 
 function cosmic_activate_page_excerpt() {
   add_post_type_support('page', array('excerpt'));
 }
 add_action('init', 'cosmic_activate_page_excerpt');
 
 # Removes default extracts and replaces them with new blocks
 function cosmic_replace_post_excerpt() {
   foreach (array("post", "page") as $type) {
     remove_meta_box('postexcerpt', $type, 'normal');
     add_meta_box('postexcerpt', __('Excerpt'), 'cosmic_create_excerpt_box', $type, 'normal');
   }
 }
 add_action('admin_init', 'cosmic_replace_post_excerpt');
 
 function cosmic_create_excerpt_box() {
   global $post;
   $id = 'excerpt';
   $excerpt = cosmic_get_excerpt($post->ID);
 
   wp_editor($excerpt, $id);
 }
 
 function cosmic_get_excerpt($id) {
   global $wpdb;
   $row = $wpdb->get_row("SELECT post_excerpt FROM $wpdb->posts WHERE id = $id");
   return $row->post_excerpt;
 }
 
 /************** http://wpbeaches.com/force-read-link-excerpts-wordpress/
 Forces the read more link to the bottom of the post preview excerpt. */
 // Read More Button For Excerpt
 function themeprefix_excerpt_read_more_link( $output ) {
 	global $post;
 	return $output . ' <a href="' . get_permalink( $post->ID ) . '" class="more-link" title="Read More">Read More</a>';
 }
 add_filter( 'the_excerpt', 'themeprefix_excerpt_read_more_link' );
 

/***** ADD EXCERPT TO A PAGE ****/
function add_excerpts_to_pages() {
add_post_type_support(‘page’, ‘excerpt’);
}
add_action(‘init’, ‘add_excerpts_to_pages’);



/*--------- Adjust top admin toolbar ----*/

//* Remove WP logo
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
} 
// Adds links to the site name bar
 add_action( 'admin_bar_menu', 'add_nodes_to_admin_bar',999 );
  function add_nodes_to_admin_bar($wp_admin_bar) {  
  
  global $wp_admin_bar;
  	if ( is_admin() ) {
  	
  		// Remove Visit site link
  		$wp_admin_bar->remove_node( 'view-site' );
  		
  		//Empty, we don't need the options on the admin side :)  		
  		} else if ( current_user_can( 'read' ) ) { 				
  				// We're on the front end
  					
  					// Posts  					
  					$wp_admin_bar->add_menu( array(
  						'parent' => 'site-name',
  						'id'     => 'posts',
  						'title'  => __( 'Posts' ) ,
  						'href'   => admin_url('edit.php'),
  						'meta'	=>  array('rel' => 'dashicons-wordpress')
  						
  					) );
  				  				
  					//Media
  					$wp_admin_bar->add_menu( array(
  						'parent' => 'site-name',
  						'id'     => 'media',
  						'title'  => __( 'Media' ),
  						'href'   => admin_url('upload.php'),
  					) );
  				
  				
  					// Pages
  					$wp_admin_bar->add_menu( array(
  						'parent' => 'site-name',
  						'id'     => 'pages',
  						'title'  => __( 'Pages' ),
  						'href'   => admin_url('edit.php?post_type=page'),
  					) );
  				
						  					
  					
  					// Comments - places itself right after the Dashboard link
  						$wp_admin_bar->add_menu( array(
  							'parent' => 'site-name',
  							'id'     => 'comments-options',
  							'title'  => __( 'Comments' ),
  							'href'   => admin_url('edit-comments.php'),
  						) );
  					
  					
  					//* Appearance
  					$wp_admin_bar->add_menu( array(
  								'parent' => 'site-name',
  								'id'     => 'appearance1',
  								'title'  => __( 'Appearance' ),
  								'href'   => admin_url('themes.php'),
  							) );
  						
  									
  						
  					// Plugins
  					$wp_admin_bar->add_menu( array(
  						'parent' => 'site-name',
  						'id'     => 'plugins',
  						'title'  => __( 'Plugins' ),
  						'href'   => admin_url('plugins.php'),
  					) );
  				
  				
  					// Users
  					$wp_admin_bar->add_menu( array(
  						'parent' => 'site-name',
  						'id'     => 'users',
  						'title'  => __( 'Users' ),
  						'href'   => admin_url('users.php'),
  					) );
  				  				
  					// Tools.
  					$wp_admin_bar->add_node( array(
  							'parent' => 'site-name',
  							'id'     => 'tools', 
  							'title'  => __( 'Tools' ),
  							'href'   => admin_url( 'tools.php' ),	
  					));	 
  					  				
  				
  					// Settings
  					$wp_admin_bar->add_menu( array(
  						'parent' => 'site-name',
  						'id'     => 'options',
  						'title'  => __( 'Settings' ),
  						'href'   => admin_url('options-general.php'),
  					) );			
  } }		