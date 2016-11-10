<?php

/*---- Various Genesis Code snippets that should be placed into an external custom Genesis functions plugin.---*/


// Enqueue To Top script - http://janhoek.com/a-simple-smooth-back-to-top-button-for-genesis/
add_action( 'wp_enqueue_scripts', 'to_top_script' );
function to_top_script() {
    wp_enqueue_script( 'to-top', get_stylesheet_directory_uri() . '/js/back-to-top.js', array( 'jquery' ), '1.0', true );
}
// Add To Top button
add_action( 'genesis_before', 'genesis_to_top');
	function genesis_to_top() {
	 echo '<a href="#0" class="to-top" title="Back To Top">Top</a>';
}



// Add previous and next link to post - https://wpbeaches.com/add-post-navigation-links-in-genesis/
add_action( 'genesis_entry_footer', 'genesis_prev_next_post_nav' );


// https://github.com/srikat/genesis-sample/blob/master/functions.php
/* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
function genesis_sample_secondary_menu_args( $args ) {
	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}
	$args['depth'] = 1;
	return $args;
} */




/* ---------- REMOVE page archive and page blog -----*/

/**
 * Remove Genesis Page Templates
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 * @param array $page_templates
 * @return array
 */
function be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'be_remove_genesis_page_templates' );



/*------ Remove unused Genesis profile options ------*/

// Remove Genesis widgets
//add_action( 'widgets_init', 'gregr_remove_genesis_widgets', 20 );

// User Permissions
remove_action( 'show_user_profile', 'genesis_user_options_fields' );
remove_action( 'edit_user_profile', 'genesis_user_options_fields' );

// Author Archive Settings
remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );

// Author Archive SEO Settings
remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );

// Layout Settings
remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

// Remove Genesis layout options
//genesis_unregister_layout( 'sidebar-content' );
//genesis_unregister_layout( 'content-sidebar-sidebar' );
//genesis_unregister_layout( 'sidebar-sidebar-content' );
//genesis_unregister_layout( 'sidebar-content-sidebar' );
//genesis_unregister_layout( 'content-sidebar' );
//genesis_unregister_layout( 'full-width-content' );

// Remove Genesis menu link
//remove_theme_support( 'genesis-admin-menu' );


/* Add Contact Methods in User Profile - https://codex.wordpress.org/Plugin_API/Filter_Reference/user_contactmethods */

function add_user_contact_methods( $user_contact ) {
  $user_contact['facebook'] = __( 'Facebook URL' );
  $user_contact['skype']   = __( 'Skype Username'   );
 // $user_contact['googleplus'] = __( 'Google +' );
  $user_contact['twitter'] = __( 'Twitter Handle' );
  $user_contact['youtube'] = __( 'Youtube Channel' );
  $user_contact['linkedin'] = __( 'LinkedIn' );
  $user_contact['pinterest'] = __( 'Pinterest' );
  $user_contact['github'] = __( 'Github profile' ); 
  
  return $user_contact;
  
}
add_filter( 'user_contactmethods', 'add_user_contact_methods' );


//Load Fontawesome - Original tutorial: http://wpbeaches.com/author-box-genesis/ updated by http://easywebdesigntutorials.com/create-your-own-author-bio-box-in-wordpress-without-a-plugin/
function themeprefix_fontawesome_styles() {
	wp_register_style( 'fontawesome' , 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', '' , '4.4.0', 'all' );
	wp_enqueue_style( 'fontawesome' );
}
add_action( 'wp_enqueue_scripts', 'themeprefix_fontawesome_styles' ); 


//Create New Author Box
function themeprefix_alt_author_box() {
    if( is_single( '' ) ) {
//author box code goes here
			?>
	   			<div class="author-box">
	   			
	   			<!-- Gravatar image and 90px size --->
	   			<?php echo get_avatar( get_the_author_meta( 'ID' ), '90' ); ?>
	   				   			
	   			<!-- Adjusted: get_the_author() TO get_the_author_meta('first_name')-->
                <div class="about-author"><h4>About <?php echo get_the_author_meta('first_name'); ?></h4> 
                 
                <!-- Description added to the profile screen. --->                
                <p><?php echo get_the_author_meta( 'description' ); ?> 
            </div>
            
            <div class="all-posts"><a href="<?php echo get_author_posts_url(  get_the_author_meta( 'ID' )); ?>">View my posts <!--<?php echo  get_the_author(); ?>---> </a></div>

               <ul class="social-links"> 
	                
                <?php if ( get_the_author_meta( 'facebook' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'facebook' ); ?>" title="Facebook" class="tooltip"><i class="fa fa-facebook"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'skype' ) != '' ): ?>
                <li><a href="<?php echo get_the_author_meta( 'skype' ); ?>" title="Twitter" class="tooltip"><i class="fa fa-skype"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'twitter' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'twitter' ); ?>" title="Twitter" class="tooltip"><i class="fa fa-twitter"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'linkedin' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'linkedin' ); ?>" title="LinkedIn" class="tooltip"><i class="fa fa-linkedin"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'youtube' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'youtube' ); ?>" title="Youtube" class="tooltip"><i class="fa fa-youtube"></i></a></li>
                <?php endif; ?>
                              
                <?php if ( get_the_author_meta( 'github' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'github' ); ?>" title="Github" class="tooltip"><i class="fa fa-github"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'pinterest' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'pinterest' ); ?>" title="Pinterest" class="tooltip"><i class="fa fa-pinterest"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'googleplus' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'googleplus' ); ?>" title="Google+" class="tooltip"><i class="fa fa-google-plus"></i></a></li>
                <?php endif; ?>
                
                <?php if ( get_the_author_meta( 'user_email' ) != '' ): ?>
                    <li><a href="mailto:<?php echo get_the_author_meta( 'user_email' ); ?>" title="E-mail" class="tooltip"><i class="fa fa-envelope-o"></i></a></li>
                <?php endif; ?>
                
                <?php  if ( get_the_author_meta( 'user_url' ) != '' ): ?>
                    <li><a href="<?php echo get_the_author_meta( 'user_url' ); ?>" title="My web site" class="tooltip"><i class="fa fa-laptop"></i> </a></li>
                <?php endif; ?>
                
                </ul>
         </div>
    <?php 
    }
}

remove_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
add_action( 'genesis_entry_footer', 'themeprefix_alt_author_box', 10 );
