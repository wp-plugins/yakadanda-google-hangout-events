<?php
/*
Plugin Name: Yakadanda Google+ Hangout Events
Plugin URI: http://www.yakadanda.com/plugins/yakadanda-google-hangout-events/
Description: A countdown function to time of the Google+ Hangout Events.
Version: 0.0.3
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

if( !defined('GPLUS_HANGOUT_EVENT_URL') ) {
  define( 'GPLUS_HANGOUT_EVENT_URL', plugins_url(null, __FILE__) );
}

// Register scripts & styles
add_action( 'init', 'googleplushangoutevent_register' );
function googleplushangoutevent_register() {
  /* Styles */
  // Yakadanda GooglePlus Hangout Event style
  wp_register_style( 'googleplushangoutevent-style', GPLUS_HANGOUT_EVENT_URL . '/css/style.css', false, '0.0.3', 'all' );
  
  /* Scripts */
  // Countdown timer jQuery Plugin
  wp_register_script( 'googleplushangoutevent-countdown', GPLUS_HANGOUT_EVENT_URL . '/js/jquery.jcountdown.min.js', array('jquery'), '1.4.2', true );
  // Moment.js A lightweight (4.3k) javascript date library for parsing, manipulating, and formatting dates.
  wp_register_script( 'googleplushangoutevent-moment', GPLUS_HANGOUT_EVENT_URL . '/js/moment.min.js', array('jquery'), '1.7.2', true );
  // Google+ Hangout Event script
  wp_register_script( 'googleplushangoutevent-script', GPLUS_HANGOUT_EVENT_URL . '/js/script.js', array('googleplushangoutevent-countdown', 'googleplushangoutevent-moment'), '0.0.2', true );
}

// Call stylesheets
function googleplushangoutevent_admin_enqueue_styles() {
  wp_enqueue_style( 'farbtastic' );
}
add_action( 'wp_enqueue_scripts', 'googleplushangoutevent_wp_enqueue_styles' );
function googleplushangoutevent_wp_enqueue_styles() {
  wp_enqueue_style( 'googleplushangoutevent-style' );
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
  wp_enqueue_script( 'googleplushangoutevent-moment' );
  wp_enqueue_script( 'googleplushangoutevent-script' );
}

require_once( dirname( __FILE__ ) . '/admin/functions.php');
require_once( 'yakadanda-widgets.php' );
