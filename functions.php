<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'genesis-sample', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'genesis-sample' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis Sample' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.2.4' );

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style( 'genesis-sample-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	wp_enqueue_script( 'genesis-sample-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	$output = array(
		'mainMenu' => __( 'Menu', 'genesis-sample' ),
		'subMenu'  => __( 'Menu', 'genesis-sample' ),
	);
	wp_localize_script( 'genesis-sample-responsive-menu', 'genesisSampleL10n', $output );
	
	/* Global script */
	wp_enqueue_script( 'genesis-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0.0', true );
	
	wp_enqueue_script( 'genesis-back-to-top', get_stylesheet_directory_uri() . '/js/back-to-top.js', array( 'jquery' ), '', true );
	
}


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add Image Sizes
add_image_size( 'featured-image', 720, 400, TRUE );

//* Rename primary and secondary navigation menus
add_theme_support( 'genesis-menus' , array( 'primary' => __( 'After Header Menu', 'genesis-sample' ), 'secondary' => __( 'Footer Menu', 'genesis-sample' ) ) );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

//* Modify size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

//* Modify size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

/*--------- CUSTOM -----*/

//* Sticky/fixed Footer Functions: http://easywebdesigntutorials.com/creating-a-sticky-footer-in-a-genesis-child-theme/
add_action( 'genesis_before_header', 'stickyfoot_wrap_begin');
function stickyfoot_wrap_begin() {
 echo '<div class="page-wrap">';
}
 
add_action( 'genesis_before_footer', 'stickyfoot_wrap_end');
function stickyfoot_wrap_end() {
 echo '</div><!-- page-wrap -->';
}



// Add support for editor stylesheet - using twenty Sixteens editor stylesheet.
add_editor_style( 'css/editor-style.css' );

// Remove the site description (tagline)
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Remove archive title and archive description in the blog page - https://wpbeaches.com/remove-archive-description-title-from-blog-page-in-genesis/
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );

//* Remove page titles from all single posts & pages (requires HTML5 theme support)
add_action( 'get_header', 'child_remove_titles' );
function child_remove_titles() {
   if ( is_singular() ){
       remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
   }
}

// Adds back in the default author box: https://wpbeaches.com/author-box-genesis/
add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );



// INCLUDE - external php pages.
//include_once( get_stylesheet_directory() . '/lib/code-snippets.php' ); // Various code snippets.
//include_once( get_stylesheet_directory() . '/lib/genesis-code-snippets.php' ); // Various Genesis code snippets.
include_once( get_stylesheet_directory() . '/lib/widgets.php' );	   // Widget areas	
include_once( get_stylesheet_directory() . '/lib/comments-meta.php' );  
//include_once( get_stylesheet_directory() . '/lib/custom-login.php' );  // Custom Login

//Another way: include_once( 'lib/custom-login.php' );

/* Add custom CSS stylesheets 
add_action( 'wp_enqueue_scripts', 'load_custom_style_sheet' );
function load_custom_style_sheet() {
	wp_enqueue_style( 'widgets-stylesheet', get_stylesheet_directory() . '/widgets.css', array(), 1.0);
	wp_enqueue_style( 'custom-stylesheet', get_stylesheet_directory() . '/custom.css' , array(), 1.0);
	
	// For adjusting the top admin toolbar -- to be placed into a custom functions plugin.
	// wp_enqueue_style( 'top-admin-bar-stylesheet', get_stylesheet_directory()  . '/lib/top-admin-bar-icons.css', array(), 1.0 );
}*/

/* Change the footer text */
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] &middot; <a href="http://easywebdesigntutorials.com">By Easy Web Design Tutorials </a> &middot; [footer_loginout] &middot;';
	return $creds;
}


/* https://github.com/srikat/genesis-sample/blob/master/functions.php */
// Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav' );

// Remove Header Right widget area
unregister_sidebar( 'header-right' );



/*-----------*/


// Add a new after Header Menu - https://wpbeaches.com/add-footer-menu-genesis-child-theme/
function themprefix_header_menu () {
 echo '<div class="after-header-menu-container">';
 $args = array(
 'theme_location' => 'after-header',
 'container' => 'nav',
 'container_class' => 'wrap',
 'menu_class' => 'menu genesis-nav-menu after-header',
 'depth' => 0, // For drop down menus change to 0 - For one level change to 1
 );
 wp_nav_menu( $args );
 echo '</div>';
}

add_theme_support ( 'genesis-menus' , array ( 'primary' => 'Site Header Navigation Menu' , 'after-header' => 'After Header Navigation Menu', 'secondary' => 'Footer Navigation Menu' , ) );

add_action('genesis_after_header', 'themprefix_header_menu', 5); /* NB! Changed before footer to in footer and changed priority to 5 so it comes before the copy right info text */




/* Breadcrumbs - http://easywebdesigntutorials.com/adding-breadcrumbs-to-a-genesis-child-theme/
*************************/
add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
function sp_breadcrumb_args( $args ) {
 $args['home'] = 'Home';
 $args['sep'] = ' ';
 $args['list_sep'] = ', '; // Genesis 1.5 and later
 $args['prefix'] = '<div class="breadcrumb">';
 $args['suffix'] = '</div>';
 $args['heirarchial_attachments'] = true; // Genesis 1.5 and later
 $args['heirarchial_categories'] = true; // Genesis 1.5 and later
 $args['display'] = true;
 $args['labels']['prefix'] = ' ';
 $args['labels']['author'] = 'Archives for ';
 $args['labels']['category'] = 'Section for '; // Genesis 1.6 and later
 $args['labels']['tag'] = 'Archives for ';
 $args['labels']['date'] = 'Archives for ';
 $args['labels']['search'] = 'Search for ';
 $args['labels']['tax'] = 'Archives for ';
 $args['labels']['post_type'] = 'Archives for ';
 $args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
return $args;
}

//* Replace breadcrumbs "Home" with Dashicons Home Icon
add_filter ( 'genesis_home_crumb', 'youruniqueprefix_breadcrumb_home_link' ); // Genesis >= 1.5
function youruniqueprefix_breadcrumb_home_link( $crumb ) {
 $crumb = '<a href="' . home_url() . '" title="' . get_bloginfo('name') . '"><i class="dashicons dashicons-admin-home"></i></a>';
 return $crumb;
}

//* Loading Dashicons
add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
wp_enqueue_style( 'dashicons' );
}


/*-------- Back To Top -------- */

add_action( 'wp_footer', 'back_to_top' );
 function back_to_top() {
 echo '<a id="totop" href="#" data-btn-alt="Top">⬆︎</a>';
 }

add_action( 'wp_head', 'back_to_top_style' );
 function back_to_top_style() {
 echo '<style type="text/css">
 #totop {
 position: fixed;
 right: 30px;
 bottom: 30px;
 display: none;
 outline: none;
 text-decoration: none;
 font-size: 26px;
 background: rgba(42, 64, 67, 0.4);
 padding: 3px 12px 3px 12px;
 border-radius: 5px;
 box-shadow: 0 0 1px #000;
 color: #fff;
 z-index: 100;
 }
 
 #totop:hover {
 background: rgba(42, 64, 67, 1);
 }
 
 #totop:hover:after{
 content: attr(data-btn-alt);
 font-size: 16px;
 color: #fff;
 padding-left: 5px;
 }
 </style>';
 
 }

add_action( 'wp_footer', 'back_to_top_script' );
 function back_to_top_script() {
 echo '<script type="text/javascript">
 jQuery(document).ready(function($){
 $(window).scroll(function () {
 if ( $(this).scrollTop() > 1500 ) 
 $("#totop").fadeIn();
 else
 $("#totop").fadeOut();
 });

$("#totop").click(function () {
 $("body,html").animate({ scrollTop: 0 }, 800 );
 return false;
 });
 });
 </script>';
 }

