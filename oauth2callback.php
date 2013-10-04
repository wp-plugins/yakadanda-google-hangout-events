<?php
include_once dirname( __FILE__ ) . "/../../../wp-load.php";
require_once( dirname( __FILE__ ) . '/src/Google_Client.php');
require_once( dirname( __FILE__ ) . '/src/contrib/Google_CalendarService.php');

$data = get_option('yakadanda_googleplus_hangout_event_options');

if( !defined('GPLUS_HANGOUT_EVENTS_PLUGIN_URL') ) {
  define( 'GPLUS_HANGOUT_EVENTS_PLUGIN_URL', plugins_url(null, __FILE__) );
}

$client = new Google_Client();
$client->setApplicationName("Yakadanda GooglePlus Hangout Event");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId( $data['client_id'] );
$client->setClientSecret( $data['client_secret'] );
$client->setRedirectUri( GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/oauth2callback.php' );
$client->setScopes( 'https://www.googleapis.com/auth/calendar' );
$client->setDeveloperKey( $data['api_key'] );

$service = new Google_CalendarService($client);

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $option = 'yakadanda_googleplus_hangout_event_access_token';
  
  $client->setAccessToken($client->getAccessToken());
  
  $calendar_list = googleplushangoutevent_calendar_list($service);
  $calendar_ids = array();
  foreach ( $calendar_list as $calendar) $calendar_ids[] = $calendar['id'];
  $is_calendar_id = in_array($data['calendar_id'], $calendar_ids);
  
  $response = '&calendar_id=false';
  if ($is_calendar_id) {
    update_option( $option, $client->getAccessToken() );
    $response = null;
  }
  
}

wp_redirect( admin_url( 'options-general.php?page=googleplus-hangout-events' . $response ) ); exit;
