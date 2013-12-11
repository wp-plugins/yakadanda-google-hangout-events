<?php
/*
 * [google+events]
 * [google+events type="hangout" limit="6" past="8"]
 * type = all, normal, or hangout, default is all
 * limit = number of events to show, it limited to 20
 * past = number of months, to display past events in X months ago
 * author = self, or all, default is all
 * id = Event identifier (string), e.g. https://plus.google.com/events/cXXXXX XXXXX is event identifier
 * filter_out = Filter out certain events by event identifiers, seperated by comma
 * search = Text search terms (string) to display events that match these terms in any field, except for extended properties
 * attendees = show, show_all, or hide, default is hide
 * timeZone = Time zone used in the response, optional. Default is time zone based on location (hangout event not have location) if not have location it will use google account/calendar time zone. Supported time zones at http://www.php.net/manual/en/timezones.php (string)
 * countdown = true, or false, by default countdown is false
 */
function googleplushangoutevent_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'type' => 'all',
    'limit' => 20,
    'past' => null,
    'author' => 'all',
    'id' => null,
    'filter_out' => array(),
    'search' => null,
    'attendees' => 'hide',
    'timezone' => null,
    'countdown' => false
  ), $atts ) );
    
  if ($limit > 20) $limit = 20;
  
  if ($id) {
    $events = googleplushangoutevent_response( null, $id, null, $timezone );
  } else {
    $events = googleplushangoutevent_response( $past, null, $search, $timezone );
    // Sorting events
    if ( $past ) uasort( $events , 'googleplushangoutevent_sort_events_desc' );
    else uasort( $events , 'googleplushangoutevent_sort_events_asc' );
  }
  
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  $token = get_option('yakadanda_googleplus_hangout_event_access_token');
  
  $output = null;
  $i = 0;
  $filter = true;
  $creator = 1;
  $http_status = isset($events['error']['code']) ? $events['error']['code'] : null;
  
  if ($events && !$http_status ) {
    
    // filter out by event identifiers
    if ($filter_out) {
      $filter_out = explode(',', str_replace(' ', '', $filter_out));
    }
    
    if ($id) {
      // Events get
      $event = $events;
      
      $visibility = isset($event['visibility']) ? $event['visibility'] : 'public';

      if ( $visibility != 'private' ) {
        $used_timezone = isset($event['timeZoneLocation']) ? $event['timeZoneLocation'] : $event['timeZoneCalendar'];
        $used_timezone = ($timezone) ? $timezone : $used_timezone;
        
        $output .= '<div class="yghe-event">';
        
        $output .= '<div class="yghe-organizer">' . googleplushangoutevent_organizer($event);
        $output .= googleplushangoutevent_ago($event['created'], $event['updated']) . '</div>';
        
        $output .= '<div class="yghe-event-title"><a href="' . $event['htmlLink'] . '" title="' . $event['summary'] . '">' . $event['summary'] . '</a></div>';

        $start_event = isset($event['start']['dateTime']) ? $event['start']['dateTime'] : $event['start']['date'];
        $end_event = isset($event['end']['dateTime']) ? $event['end']['dateTime'] : $event['end']['date'];

        $output .= '<div class="yghe-event-time">' . googleplushangoutevent_time($start_event, $end_event, $used_timezone, 'shortcode') . '</div>';

        if ( isset($event['location']) ) {
          $output .= '<div class="yghe-event-location" title="Location"><a href="http://maps.google.com/?q=' . $event['location'] . '" title="' . $event['location'] . '">' . $event['location'] . '</a></div>';
        } else {
          $onair = googleplushangoutevent_onair($start_event, $end_event);
          if ( $onair ) $output .= '<div class="yghe-event-onair" title="On Air">';
          else $output .= '<div class="yghe-event-hangout" title="Hangout">';
          
          if ( isset($event['hangoutLink']) ) $output .= '<a href="' . $event['hangoutLink'] . '" title="Google+ Hangout">Google+ Hangout</a>';
          $output .= '</div>';
        }
        
        $description = isset($event['description']) ? nl2br( $event['description'] ) : null;
        $output .= '<div class="yghe-event-description">' . $description . '</div>';
        
        if ( ($attendees == 'show') || ($attendees == 'show_all') ) {
          $guests = isset($event['attendees']) ? $event['attendees'] : null;
          $output .= '<div class="yghe-event-attendees">'. googleplushangoutevent_get_attendees( $guests, $attendees ) . '</div>';
        }
        
        if ($countdown == 'true') {
          $time = googleplushangoutevent_start_time($start_event, $used_timezone);
          $output .= '<div id="' . uniqid() . '" class="yghe-shortcode-countdown" time="' . $time . '">' . $time . '</div>';
        }
        
        $output .= '</div>';
      }
      
    } else {
      // Events lists
      foreach ($events as $event) {
        $hangoutlink = isset($event['hangoutLink']) ? $event['hangoutLink'] : false;
        $visibility = isset($event['visibility']) ? $event['visibility'] : 'public';

        if ($type == 'normal') $filter = !$hangoutlink;
        elseif ($type == 'hangout') $filter = $hangoutlink;

        if ( $author == 'self' ) {
          if ( isset($event['creator']['self']) )
            $creator = $event['creator']['self'];
          else
            $creator = ($event['creator']['email'] == $data['calendar_id']) ? 1 : 0;
        }
        
        if ( $filter && $creator && ($visibility != 'private') && !in_array($event['id'], $filter_out) ) { $i++;
          $used_timezone = isset($event['timeZoneLocation']) ? $event['timeZoneLocation'] : $event['timeZoneCalendar'];
          $used_timezone = ($timezone) ? $timezone : $used_timezone;
          
          $output .= '<div class="yghe-event">';
          
          $output .= '<div class="yghe-organizer">' . googleplushangoutevent_organizer($event);
          $output .= googleplushangoutevent_ago($event['created'], $event['updated']) . '</div>';
          
          $output .= '<div class="yghe-event-title"><a href="' . $event['htmlLink'] . '" title="' . $event['summary'] . '">' . $event['summary'] . '</a></div>';
          
          $start_event = isset($event['start']['dateTime']) ? $event['start']['dateTime'] : $event['start']['date'];
          $end_event = isset($event['end']['dateTime']) ? $event['end']['dateTime'] : $event['end']['date'];
          
          $output .= '<div class="yghe-event-time">' . googleplushangoutevent_time($start_event, $end_event, $used_timezone,'shortcode') . '</div>';
          
          if ( isset($event['location']) ) {
            $output .= '<div class="yghe-event-location" title="Location"><a href="http://maps.google.com/?q=' . $event['location'] . '" title="' . $event['location'] . '">' . $event['location'] . '</a></div>';
          } else {
            $onair = googleplushangoutevent_onair($start_event, $end_event);
            if ( $onair ) $output .= '<div class="yghe-event-onair" title="On Air">';
            else $output .= '<div class="yghe-event-hangout" title="Hangout">';
          
            if ( isset($event['hangoutLink']) ) $output .= '<a href="' . $event['hangoutLink'] . '" title="Google+ Hangout">Google+ Hangout</a>';
            $output .= '</div>';
          }
          
          $description = isset($event['description']) ? nl2br( $event['description'] ) : null;
          $output .= '<div class="yghe-event-description">'. $description . '</div>';
          
          if ( ($attendees == 'show') || ($attendees == 'show_all') ) {
            $guests = isset($event['attendees']) ? $event['attendees'] : null;
            $output .= '<div class="yghe-event-attendees">'. googleplushangoutevent_get_attendees( $guests, $attendees ) . '</div>';
          }
          
          if ( $countdown == 'true' ) {
            $time = googleplushangoutevent_start_time( $start_event, $used_timezone );
            $output .= '<div id="' . uniqid() . '" class="yghe-shortcode-countdown" time="' . $time . '">' . $time . '</div>';
          }
          
          $output .= '</div>';

          if ($limit == $i) break;
        }
      }
    }
  }
  
  if ( ($output == null) && !$http_status ) {
    $message = 'No event and hangout event yet.';
    if ($type == 'normal') $message = 'No event yet.';
    elseif ($type == 'hangout') $message = 'No hangout event yet.';
    $output = ($token) ? $message : 'Not Connected.';
  }
  
  // Error 403 message
  if ($http_status) {
    $message = isset($events['error']['message']) ? $events['error']['message'] : null;
    $output = $http_status . ' ' . $message . '.';
  }
  
  return $output;
}
add_shortcode( 'google+events', 'googleplushangoutevent_shortcode' );

function googleplushangoutevent_time($startdate, $finishdate, $timezone, $type) {
  $startdate = new DateTime( $startdate );
  $finishdate = new DateTime( $finishdate );
  
  $dateTimeZone = new DateTimeZone( $timezone );
  
  $startdate->setTimezone($dateTimeZone);
  $startdate = $startdate->format('c');
  
  $finishdate->setTimezone($dateTimeZone);
  $finishdate = $finishdate->format('c');
  
  $diff = round(abs(strtotime($finishdate)-strtotime($startdate))/86400);
  
  $begindate = str_split($startdate, 19);
  $year_event = date('Y', strtotime($begindate[0]));
  $year_current = date('Y');
  $years = $year_event - $year_current;
  
  $output = null;
  
  if ( $type == 'shortcode'  ) {
    $timezone_abbreviations = googleplushangoutevent_timezone_abbreviations( $timezone );
    
    $output = date('D, F d, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone_abbreviations;
    if ($years > 0) $output = date('D, F d Y, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone_abbreviations;
  } elseif ( $type == 'widget' ) {
    $timezone_abbreviations = googleplushangoutevent_timezone_abbreviations( $timezone );
    
    $enddate = str_split($finishdate, 19);
    $output = '<span>' . date('F jS Y', strtotime($begindate[0])) . '</span><br><span>' . date('g:i a', strtotime($begindate[0])) . '&nbsp;-&nbsp;' . date('g:i a', strtotime($enddate[0])) . '&nbsp;' . $timezone_abbreviations . '</span>';
  }
  
  if ( $diff >= 1 ) {
    $timezone_abbreviations = googleplushangoutevent_timezone_abbreviations( $timezone );
    
    $enddate = str_split($finishdate, 19);
    if ( $type == 'shortcode' ) {
      $output = date('D, F d, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone_abbreviations . ' - ' . date('D, F d, g:i A', strtotime($enddate[0])) . '&nbsp;' . $timezone_abbreviations;
      if ($years > 0) $output = date('D, F d Y, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone_abbreviations . ' - ' . date('D, F d Y, g:i A', strtotime($enddate[0])) . '&nbsp;' . $timezone_abbreviations;
      
      if ( !isset($timezone_abbreviations) ) {
        $output = date('D, F d, ', strtotime($begindate[0])) . 'All day';
        if ($years > 0) $output = date('D, F d Y, ', strtotime($begindate[0])) . 'All day';
      }
    } elseif ( $type == 'widget' ) {
      $output = '<span>' . date('F jS Y g:i a', strtotime($begindate[0])) . '&nbsp;to</span><br><span>' . date('F jS Y g:i a', strtotime($enddate[0])) . '&nbsp;' . $timezone_abbreviations . '</span>';
      if ( !isset($timezone_abbreviations) ) {
        $output = '<span>' . date('F jS Y', strtotime($begindate[0])) . '</span><br><span>All day</span>';
      }
    }
  }
  
  return $output;
}

function googleplushangoutevent_ago($datetime1, $datetime2) {
  $i = strtotime($datetime1);
  $info = null;
  
  // difference in minutes
  $diff = (strtotime($datetime2) - strtotime($datetime1)) / 60;
  if ($diff > 1) {
    $i = strtotime($datetime2);
    $info = '&nbsp;(updated)';
  }
  
  $m = time()-$i; $o='just now';
  $t = array('year'=>31556926,'month'=>2629744,'week'=>604800, 'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
  foreach($t as $u=>$s){
    if ($s<=$m) {$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
  }
  
  $o = $o . $info;
  
  return $o;
}

function googleplushangoutevent_timezone_abbreviations( $timezone = null ) {
  $output = null;
  
  if ( $timezone ) {
    $dateTime = new DateTime();
    $dateTime->setTimeZone(new DateTimeZone( $timezone ));
    $output = $dateTime->format('T');
  }
  
  return $output;
}

function googleplushangoutevent_organizer($event) {
  if ( isset($event['organizer']['id']) ) {
    $output = '<a href="https://plus.google.com/' . $event['organizer']['id'] . '" title="Organizer">' . $event['organizer']['displayName'] . '</a> ';
  } else {
    if ( strpos($event['organizer']['email'], '.calendar.') !== false ) {
      $output = '<a href="mailto:' . $event['creator']['email'] . '" title="Calendar">' . $event['organizer']['displayName'] . '</a> ';
    } else {
      if ($event['organizer']['displayName']) {
        $output = '<a href="mailto:' . $event['organizer']['email'] . '" title="Organizer">' . $event['organizer']['displayName'] . '</a> ';
      } else {
        $display_name = googleplushangoutevent_display_name( $event );
        if ( $display_name )
          $output = '<a href="mailto:' . $event['organizer']['email'] . '" title="Coworker\'s Calendar">' . $display_name . '</a> ';
      }
    }
  }
  
  return $output;
}

function googleplushangoutevent_display_name( $event ) {
  $output = null;
  foreach ( $event['attendees'] as $attendee ) {
    if ( $attendee['email'] == $event['organizer']['email'] ) {
      $output = ( $attendee['displayName'] ) ? $attendee['displayName'] : null;
      break;
    }
  }
  
  return $output;
}

function googleplushangoutevent_get_attendees( $guests, $view ) {
  $output = null;
  $i = $j = $k = 0;
  
  if ( $guests ) {
    $accepted = $tentative = $needsAction = $accepted_title = $tentative_title = $needsAction_title = null;
    
    foreach ( $guests as $guest ) {
      if ( $guest['responseStatus'] == 'accepted' ) { ++$i;
        $display_name = isset($guest['displayName']) ? $guest['displayName'] : $guest['email'];
        $pass = ( ($view == 'show') && ($i >= 5) ) ? false : true;
        
        if ( $pass ) {
          if ( isset($guest['id']) ) {
            $accepted .= '<a href="https://plus.google.com/' . $guest['id'] . '">' . $display_name . '</a>';
          } else {
            $accepted .= '<a href="mailto:' . $guest['email'] . '">' . $display_name . '</a>';
          }
          $accepted .= ', ';
        } else { $accepted_title .= $display_name . ', '; }
      } elseif ( $guest['responseStatus'] == 'tentative' ) { ++$j;
        $display_name = isset($guest['displayName']) ? $guest['displayName'] : $guest['email'];
        $pass = ( ($view == 'show') && ($j >= 5) ) ? false : true;
        
        if ($pass) {
          if ( isset($guest['id']) ) {
            $tentative .= '<a href="https://plus.google.com/' . $guest['id'] . '">' . $display_name . '</a>';
          } else {
            $tentative .= '<a href="mailto:' . $guest['email'] . '">' . $display_name . '</a>';
          }
          $tentative .= ', ';
        } else { $tentative_title .= $display_name . ', '; }
      } elseif ( $guest['responseStatus'] == 'needsAction' ) { ++$k;
        $display_name = isset($guest['displayName']) ? $guest['displayName'] : $guest['email'];
        $pass = ( ($view == 'show') && ($k >= 5) ) ? false : true;
        
        if ( $pass ) {
          if ( isset($guest['id']) ) {
            $needsAction .= '<a href="https://plus.google.com/' . $guest['id'] . '">' . $display_name . '</a>';
          } else {
            $needsAction .= '<a href="mailto:' . $guest['email'] . '">' . $display_name . '</a>';
          }
          $needsAction .= ', ';
        } else { $needsAction_title .= $display_name . ', '; }
      }
    }
    
    if ($accepted) {
      $output .= '<p>Going (' . $i . ')</p>' . substr_replace($accepted ,"",-2);
      if ( ($view == 'show') && ($i>4) ) $output .= ', <span title="' . substr_replace($accepted_title ,"",-2) . '">...</span>';
    }
    if ($tentative) {
      $output .= '<p>Maybe (' . $j . ')</p>' . substr_replace($tentative ,"",-2);
      if ( ($view == 'show') && ($j>4) ) $output .= ', <span title="' . substr_replace($tentative_title ,"",-2) . '">...</span>';
    }
    if ($needsAction) {
      $output .= '<p>Unknown (' . $k . ')</p>' . substr_replace($needsAction ,"",-2);
      if ( ($view == 'show') && ($k>4) ) $output .= ', <span title="' . substr_replace($needsAction_title ,"",-2) . '">...</span>';
    }
  }
  
  return $output;
}

function googleplushangoutevent_fetch_data($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}

function googleplushangoutevent_google_geocoding( $address=null ) {
  $output = null;
  
  if ( $address ) {
    $address = str_replace( ' ', '+', $address );
    
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false';
    $responses = googleplushangoutevent_fetch_data($url);
    $data = json_decode( $responses );
    
    if ( $data->status == 'OK' ) {
      $lat = $data->results[0]->geometry->location->lat;
      $lng = $data->results[0]->geometry->location->lng;
      
      $output = $lat . ',' . $lng;
    }
  }
  
  return $output;
}

function googleplushangoutevent_location_timezone( $location=null, $time=null) {
  $output = null;
  
  if ( $location && $time ) {
    $timestamp = strtotime($time);
    $url = 'https://maps.googleapis.com/maps/api/timezone/json?location=' . $location . '&timestamp=' . $timestamp . '&sensor=false';
    $responses = googleplushangoutevent_fetch_data($url);
    $data = json_decode( $responses );
    
    if ( $data->status == 'OK' ) $output = $data->timeZoneId;
  }
  
  return $output;
}
