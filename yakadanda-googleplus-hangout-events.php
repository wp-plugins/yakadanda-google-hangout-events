<?php
/*
Plugin Name: Yakadanda Google+ Hangout Events
Plugin URI: http://www.yakadanda.com/plugins/yakadanda-google-hangout-events/
Description: A countdown function to time of the Google+ Hangout Events.
Version: 0.1.0
Author: Peter Ricci
Author URI: http://www.yakadanda.com/
License: GPL2
*/

/* Put setup procedures to be run when the plugin is activated in the following function */
function googleplushangoutevent_activate() {
  if ( ! get_option('yakadanda_googleplus_hangout_event_options') )
    add_option('yakadanda_googleplus_hangout_event_options', null, false, false);
  if ( ! get_option('yakadanda_googleplus_hangout_event_access_token') )
    add_option('yakadanda_googleplus_hangout_event_access_token', null, false, false);
}
register_activation_hook( __FILE__, 'googleplushangoutevent_activate' );

// On deacativation, clean up anything your component has added.
function googleplushangoutevent_deactivate() {
	// You might want to delete any options or tables that your component created.
  
}
register_deactivation_hook( __FILE__, 'googleplushangoutevent_deactivate' );

if( !defined('GPLUS_HANGOUT_EVENTS_VER') ) { define('GPLUS_HANGOUT_EVENTS_VER', '0.1.0'); }
if( !defined('GPLUS_HANGOUT_EVENTS_PLUGIN_DIR') ) { define('GPLUS_HANGOUT_EVENTS_PLUGIN_DIR', plugin_dir_path(__FILE__) ); }
if( !defined('GPLUS_HANGOUT_EVENTS_PLUGIN_URL') ) { define('GPLUS_HANGOUT_EVENTS_PLUGIN_URL', plugins_url(null, __FILE__) ); }
if( !defined('GPLUS_HANGOUT_EVENTS_THEME_DIR') ) { define('GPLUS_HANGOUT_EVENTS_THEME_DIR', get_stylesheet_directory() ); }
if( !defined('GPLUS_HANGOUT_EVENTS_THEME_URL') ) { define('GPLUS_HANGOUT_EVENTS_THEME_URL', get_stylesheet_directory_uri() ); }

// Register scripts & styles
add_action( 'init', 'googleplushangoutevent_register' );
function googleplushangoutevent_register() {
  /* Styles */
  // Google web fonts
  $google_fonts = googleplushangoutevent_google_fonts();
  if ($google_fonts) wp_register_style( 'googleplushangoutevent-google-fonts', 'http://fonts.googleapis.com/css?family=' . $google_fonts, false, GPLUS_HANGOUT_EVENTS_VER, 'all' );
  // Yakadanda GooglePlus Hangout Event style
  if ( file_exists(GPLUS_HANGOUT_EVENTS_THEME_DIR . '/css/google-hangout-events.css' ) ) {
    wp_register_style( 'googleplushangoutevents-style', GPLUS_HANGOUT_EVENTS_THEME_URL . '/css/google-hangout-events.css', false, GPLUS_HANGOUT_EVENTS_VER, 'all' );
  } else {
    wp_register_style( 'googleplushangoutevents-style', GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/css/google-hangout-events.css', false, GPLUS_HANGOUT_EVENTS_VER, 'all' );
  }
  
  /* Scripts */
  // Countdown timer jQuery Plugin
  wp_register_script( 'googleplushangoutevent-countdown', GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/js/jquery.jcountdown.min.js', array('jquery'), '1.4.2', true );
  // Google+ Hangout Event script
  wp_register_script( 'googleplushangoutevent-script', GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/js/script.js', array('googleplushangoutevent-countdown'), GPLUS_HANGOUT_EVENTS_VER, true );
}

// Call stylesheets
function googleplushangoutevent_admin_enqueue_styles() {
  wp_enqueue_style( 'farbtastic' );
}
add_action( 'wp_enqueue_scripts', 'googleplushangoutevent_wp_enqueue_styles' );
function googleplushangoutevent_wp_enqueue_styles() {
  $google_fonts = googleplushangoutevent_google_fonts();
  if ($google_fonts) wp_enqueue_style( 'googleplushangoutevent-google-fonts' );
  wp_enqueue_style( 'googleplushangoutevents-style' );
}

// Call javascripts
function googleplushangoutevent_admin_enqueue_scripts() {
  wp_enqueue_script( 'farbtastic' );
  wp_enqueue_script( 'googleplushangoutevent-script' );
}
add_action( 'wp_enqueue_scripts', 'googleplushangoutevent_wp_enqueue_scripts' );
function googleplushangoutevent_wp_enqueue_scripts() {
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'googleplushangoutevent-countdown' );
  wp_enqueue_script( 'googleplushangoutevent-script' );
}

require_once( dirname( __FILE__ ) . '/admin/functions.php');
require_once( 'yakadanda-widgets.php' );
require_once( 'yakadanda-shortcode.php' );
