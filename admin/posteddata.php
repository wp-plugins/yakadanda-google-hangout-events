<?php
include_once dirname( __FILE__ ) . "/../../../../wp-load.php";
require_once( dirname( __FILE__ ) . '/../src/Google_Client.php');
require_once( dirname( __FILE__ ) . '/../src/contrib/Google_CalendarService.php');

if ($_GET['logout']) {
  $option = 'yakadanda_googleplus_hangout_event_access_token';
  $value = null;
  update_option( $option, $value );
} elseif( ($_POST['calendar_id'] != null) && ($_POST['api_key'] != null) && ($_POST['client_id'] != null) && ($_POST['client_secret'] != null)) {
  
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  $token = get_option('yakadanda_googleplus_hangout_event_access_token');

  $value = array(
      'calendar_id' => $_POST['calendar_id'],
      'api_key' => $_POST['api_key'],
      'client_id' => $_POST['client_id'],
      'client_secret' => $_POST['client_secret'],
      'widget_border' => $_POST['widget_border'],
      'widget_background' => $_POST['widget_background'],
      'title_color' => $_POST['title_color'],
      'title_theme' => $_POST['title_theme'],
      'title_size' => $_POST['title_size'],
      'title_style' => $_POST['title_style'],
      'date_color' => $_POST['date_color'],
      'date_theme' => $_POST['date_theme'],
      'date_size' => $_POST['date_size'],
      'date_style' => $_POST['date_style'],
      'detail_color' => $_POST['detail_color'],
      'detail_theme' => $_POST['detail_theme'],
      'detail_size' => $_POST['detail_size'],
      'detail_style' => $_POST['detail_style'],
      'icon_border' => $_POST['icon_border'],
      'icon_background' => $_POST['icon_background'],
      'icon_color' => $_POST['icon_color'],
      'icon_theme' => $_POST['icon_theme'],
      'icon_size' => $_POST['icon_size'],
      'icon_style' => $_POST['icon_style'],
      'countdown_background' => $_POST['countdown_background'],
      'countdown_color' => $_POST['countdown_color'],
      'countdown_theme' => $_POST['countdown_theme'],
      'countdown_size' => $_POST['countdown_size'],
      'countdown_style' => $_POST['countdown_style'],
      'event_button_background' => $_POST['event_button_background'],
      'event_button_hover' => $_POST['event_button_hover'],
      'event_button_color' => $_POST['event_button_color'],
      'event_button_theme' => $_POST['event_button_theme'],
      'event_button_size' => $_POST['event_button_size'],
      'event_button_style' => $_POST['event_button_style']
    );

  $option = 'yakadanda_googleplus_hangout_event_options';
  update_option( $option, $value );

  if ( ($data['api_key'] != $_POST['api_key']) || ($data['client_id'] != $_POST['client_id']) || ($data['client_secret'] != $_POST['client_secret']) || !$token ) {
    $client = new Google_Client();
    $client->setApplicationName("Yakadanda GooglePlus Hangout Event");

    // Visit https://code.google.com/apis/console?api=calendar to generate your
    // client id, client secret, and to register your redirect uri.
    $client->setClientId( $_POST['client_id'] );
    $client->setClientSecret( $_POST['client_secret'] );
    $client->setRedirectUri( GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/oauth2callback.php' );
    $client->setScopes( 'https://www.googleapis.com/auth/calendar' );
    $client->setDeveloperKey( $_POST['api_key'] );

    // make null the token from database
    $option = 'yakadanda_googleplus_hangout_event_access_token';
    $value = null;
    update_option( $option, $value );

    wp_redirect( $client->createAuthUrl() ); exit;
  }

}

wp_redirect( admin_url( 'options-general.php?page=googleplus-hangout-events' ) ); exit;
