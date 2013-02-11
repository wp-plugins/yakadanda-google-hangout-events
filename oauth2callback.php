<?php
include_once dirname( __FILE__ ) . "/../../../wp-load.php";
require_once( dirname( __FILE__ ) . '/src/Google_Client.php');
require_once( dirname( __FILE__ ) . '/src/contrib/Google_CalendarService.php');

$data = get_option('yakadanda_googleplus_hangout_event_options');

if( !defined('GPLUS_HANGOUT_EVENT_URL') ) {
  define( 'GPLUS_HANGOUT_EVENT_URL', plugins_url(null, __FILE__) );
}

$client = new Google_Client();
$client->setApplicationName("Yakadanda GooglePlus Hangout Event");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId( $data['client_id'] );
$client->setClientSecret( $data['client_secret'] );
$client->setRedirectUri( GPLUS_HANGOUT_EVENT_URL . '/oauth2callback.php' );
$client->setScopes( 'https://www.googleapis.com/auth/calendar.readonly' );
$client->setDeveloperKey( $data['api_key'] );

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $option = 'yakadanda_googleplus_hangout_event_access_token';
  update_option( $option, $client->getAccessToken() );
}

wp_redirect( admin_url( 'options-general.php?page=googleplus-hangout-events' ) ); exit;
