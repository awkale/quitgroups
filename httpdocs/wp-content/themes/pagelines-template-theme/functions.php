<?php
// Setup  -- Probably want to keep this stuff...

/**
 * Hello and welcome to Base! First, lets load the PageLines core so we have access to the functions
 */
require_once( dirname(__FILE__) . '/setup.php' );

// For advanced customization tips & code see advanced file.
	//--> require_once(STYLESHEETPATH . "/advanced.php");

// ====================================================
// = YOUR FUNCTIONS - Where you should add your code  =
// ====================================================


// ABOUT HOOKS --------//
	// Hooks are a way to easily add custom functions and content to PageLines. There are hooks placed strategically throughout the theme
	// so that you insert code and content with ease.


// ABOUT FILTERS ----------//

	// Filters allow data modification on-the-fly. Which means you can change something after it was read and compiled from the database,
	// but before it is shown to your visitor. Or, you can modify something a visitor sent to your database, before it is actually written there.

// FILTERS EXAMPLE ---------//

	// The following filter will add the font  Ubuntu into the font array $thefoundry.
	// This makes the font available to the framework and the user via the admin panel.


// Change the text on the signup page
//add_filter( ‘bp_registration_needs_activation’, ‘__return_false’ );

//function my_disable_activation( $user, $user_email, $key, $meta = ” ) {
// Activate the user
//bp_core_activate_signup( $key );

// Return false so no email sent
//return false;
//}
//add_filter( ‘wpmu_signup_user_notification’, ‘my_disable_activation’, 10, 4 );

//Disable new blog notification email for multisite
//remove_filter( ‘wpmu_signup_blog_notification’, ‘bp_core_activation_signup_blog_notification’, 1, 7 );
//add_filter( ‘wpmu_signup_blog_notification’, ‘__return_false’ );

// disable sending activation emails for multisite
//remove_filter( ‘wpmu_signup_user_notification’, ‘bp_core_activation_signup_user_notification’, 1, 4 );
//add_filter( ‘wpmu_signup_user_notification’, ‘__return_false’, 1, 4 );


function disable_validation( $user_id ) {
  global $wpdb;

  $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET user_status = 0 WHERE ID = %d", $user_id ) );
}
add_action( 'bp_core_signup_user', 'disable_validation' );

function fix_signup_form_validation_text() {
  return false;
}
add_filter( 'bp_registration_needs_activation', 'fix_signup_form_validation_text' );