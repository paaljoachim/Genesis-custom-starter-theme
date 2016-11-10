<?php

/* Custom login code */

// CHANGES the WordPress login image for another image....
// https://github.com/JiveDig/baseline/blob/master/functions.php
/**
 * Change login logo
 * Max image width should be 320px
 * @link http://andrew.hedges.name/experiments/aspect_ratio/ 
 */
add_action('login_head',  'tsm_custom_dashboard_logo');
function tsm_custom_dashboard_logo() {
	echo '<style  type="text/css">
		/* NB! Remove the comment tags to add a logo!!
		 .login h1 a {
			background-image:url(' . get_stylesheet_directory_uri() . '/images/osf-logo.jpg)  !important;
			background-size: 300px auto !important;
			width: 100% !important;
			height: 120px !important;
		}*/
		
		html,
		body {
		 background-image:url(' . get_stylesheet_directory_uri() . '/images/Blue-bubbles.jpg)  !important;
		 background-size: cover;
		}
		
		.login form {
		  box-shadow: 0px 1px 3px #444 !important;
		  border-radius: 7px;
		}
		
		.login #backtoblog a,
		.login #nav a{
		  color: #edeaea; 
		  font-size: 16px;
		}
		
	</style>';
}
