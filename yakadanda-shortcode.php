<?php
/*
 * [google+events]
 * [google+events type="all" limit="6" past="8"]
 * type = all, normal, or hangout, default is all
 * limit = number of events to show, it limited to 20
 * past = number of months, to display past events in X months ago
 * author = self, or all, default is self
 * id = Event identifier (string), e.g. https://plus.google.com/events/cXXXXX XXXXX is event identifier
 * filter_out = Filter out certain events by event identifiers, seperated by comma
 * search = Text search terms (string) to display events that match these terms in any field, except for extended properties
 */
function googleplushangoutevent_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'type' => 'all',
    'limit' => 20,
    'past' => null,
    'author' => 'self',
    'id' => null,
    'filter_out' => array(),
    'search' => null
  ), $atts ) );
    
  if ($limit > 20) $limit = 20;
  
  if ($id) {
    $events = googleplushangoutevent_response( null, $id );
  } else {
    $events = googleplushangoutevent_response( $past, null, $search );
    // Sorting events
    if ( $past ) uasort( $events , 'googleplushangoutevent_sort_events_desc' );
    else uasort( $events , 'googleplushangoutevent_sort_events_asc' );
  }
  
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  $output = 'No Event.';
  $i = 0;
  $filter = true;
  $creator = 1;
  $http_status = isset($events['error']['code']) ? $events['error']['code'] : null;
  
  if ($events && !$http_status ) {
    $output = null;
    
    // filter out by event identifiers
    if ($filter_out) {
      $filter_out = explode(',', $filter_out);
    }
    
    if ($id) {
      // Events get
      $event = $events;
      $visibility = isset($event['visibility']) ? $event['visibility'] : 'public';

      if ( $visibility != 'private' ) {
        $output .= '<div class="yghe-event">';
        
        $output .= '<div class="yghe-organizer">' . googleplushangoutevent_organizer($event);
        $output .= googleplushangoutevent_ago($event['created'], $event['updated']) . '</div>';
        
        $output .= '<div class="yghe-event-title"><a href="' . $event['htmlLink'] . '" title="' . $event['summary'] . '">' . $event['summary'] . '</a></div>';

        $start_event = isset($event['start']['dateTime']) ? $event['start']['dateTime'] : $event['start']['date'];
        $end_event = isset($event['end']['dateTime']) ? $event['end']['dateTime'] : $event['end']['date'];

        $output .= '<div class="yghe-event-time">' . googleplushangoutevent_time($start_event, $end_event, 'shortcode') . '</div>';

        if ($event['location']) {
          $output .= '<div class="yghe-event-location"><a href="http://maps.google.com/?q=' . $event['location'] . '" title="' . $event['location'] . '">' . $event['location'] . '</a></div>';
        } else {
          $output .= '<div class="yghe-event-hangout">';
          if ( isset($event['hangoutLink']) ) $output .= '<a href="' . $event['hangoutLink'] . '" title="Google+ Hangout">Google+ Hangout</a>';
          $output .= '</div>';
        }

        $output .= '<div class="yghe-event-description">'. $event['description'] . '</div>';
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
          $output .= '<div class="yghe-event">';
          
          $output .= '<div class="yghe-organizer">' . googleplushangoutevent_organizer($event);
          $output .= googleplushangoutevent_ago($event['created'], $event['updated']) . '</div>';
          
          $output .= '<div class="yghe-event-title"><a href="' . $event['htmlLink'] . '" title="' . $event['summary'] . '">' . $event['summary'] . '</a></div>';

          $start_event = isset($event['start']['dateTime']) ? $event['start']['dateTime'] : $event['start']['date'];
          $end_event = isset($event['end']['dateTime']) ? $event['end']['dateTime'] : $event['end']['date'];

          $output .= '<div class="yghe-event-time">' . googleplushangoutevent_time($start_event, $end_event, 'shortcode') . '</div>';

          if ($event['location']) {
            $output .= '<div class="yghe-event-location"><a href="http://maps.google.com/?q=' . $event['location'] . '" title="' . $event['location'] . '">' . $event['location'] . '</a></div>';
          } else {
            $output .= '<div class="yghe-event-hangout">';
            if ( isset($event['hangoutLink']) ) $output .= '<a href="' . $event['hangoutLink'] . '" title="Google+ Hangout">Google+ Hangout</a>';
            $output .= '</div>';
          }

          $output .= '<div class="yghe-event-description">'. $event['description'] . '</div>';
          $output .= '</div>';

          if ($limit == $i) break;
        }
      }
    }
  }
  
  if ( $output == null ) $output = 'No Event.';
  
  // Error 403 message
  if ($http_status) {
    $message = isset($events['error']['message']) ? $events['error']['message'] : null;
    $output = $http_status . ' ' . $message . '.';
  }
  
  return $output;
}
add_shortcode( 'google+events', 'googleplushangoutevent_shortcode' );

function googleplushangoutevent_time($startdate, $finishdate, $type) {
  $diff = round(abs(strtotime($finishdate)-strtotime($startdate))/86400);
  
  $begindate = str_split($startdate, 19);
  $year_event = date('Y', strtotime($begindate[0]));
  $year_current = date('Y');
  $years = $year_event - $year_current;
  
  $output = null;
  
  if ( $type == 'shortcode'  ) {
    $timezone = googleplushangoutevent_timezone( $begindate[1] );
    $output = date('D, F d, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone;
    if ($years > 0) $output = date('D, F d Y, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone;
  } elseif ( $type == 'widget' ) {
    $timezone = googleplushangoutevent_timezone( $begindate[1] );
    $enddate = str_split($finishdate, 19);
    $output = '<span>' . date('F jS Y', strtotime($begindate[0])) . '</span><br><span>' . date('g:i a', strtotime($begindate[0])) . '&nbsp;-&nbsp;' . date('g:i a', strtotime($enddate[0])) . '&nbsp;' . $timezone . '</span>';
  }
  
  if ( $diff >= 1 ) {
    $timezone = googleplushangoutevent_timezone( $begindate[1] );
    $enddate = str_split($finishdate, 19);
    if ( $type == 'shortcode' ) {
      $output = date('D, F d, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone . ' - ' . date('D, F d, g:i A', strtotime($enddate[0])) . '&nbsp;' . $timezone;
      if ($years > 0) $output = date('D, F d Y, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone . ' - ' . date('D, F d Y, g:i A', strtotime($enddate[0])) . '&nbsp;' . $timezone;
      
      if ( !isset($timezone) ) {
        $output = date('D, F d, ', strtotime($begindate[0])) . 'All day';
        if ($years > 0) $output = date('D, F d Y, ', strtotime($begindate[0])) . 'All day';
      }
    } elseif ( $type == 'widget' ) {
      $output = '<span>' . date('F jS Y g:i a', strtotime($begindate[0])) . '&nbsp;to</span><br><span>' . date('F jS Y g:i a', strtotime($enddate[0])) . '&nbsp;' . $timezone . '</span>';
      if ( !isset($timezone) ) {
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

function googleplushangoutevent_timezone( $time ) {
  $output = null;
  
  if ( $time == '+01:00' ) $output = 'ECT'; // European Central Time
  elseif ( $time == '+02:00' ) $output = 'EET'; // Eastern European Time
  //elseif ( $time == '+02:00' ) $output = 'ART'; // (Arabic) Egypt Standard Time
  elseif ( $time == '+03:00' ) $output = 'EAT'; // Eastern African Time
  elseif ( $time == '+03:30' ) $output = 'MET'; // Middle East Time
  elseif ( $time == '+04:00' ) $output = 'NET'; // Near East Time
  elseif ( $time == '+05:00' ) $output = 'PLT'; // Pakistan Lahore Time
  elseif ( $time == '+05:30' ) $output = 'IST'; // India Standard Time
  elseif ( $time == '+06:00' ) $output = 'BST'; // Bangladesh Standard Time
  elseif ( $time == '+07:00' ) $output = 'VST'; // Vietnam Standard Time
  elseif ( $time == '+08:00' ) $output = 'CTT'; // China Taiwan Time
  elseif ( $time == '+09:00' ) $output = 'JST'; // Japan Standard Time
  elseif ( $time == '+09:30' ) $output = 'ACT'; // Australia Central Time
  elseif ( $time == '+10:00' ) $output = 'AET'; // Australia Eastern Time
  elseif ( $time == '+11:00' ) $output = 'SST'; // Solomon Standard Time
  elseif ( $time == '+12:00' ) $output = 'NST'; // New Zealand Standard Time
  elseif ( $time == '-11:00' ) $output = 'MIT'; // Midway Islands Time
  elseif ( $time == '-10:00' ) $output = 'HST'; // Hawaii Standard Time
  elseif ( $time == '-09:00' ) $output = 'AST'; // Alaska Standard Time
  elseif ( $time == '-08:00' ) $output = 'PST'; // Pacific Standard Time
  elseif ( $time == '-07:00' ) $output = 'PNT'; // Phoenix Standard Time
  //elseif ( $time == '-07:00' ) $output = 'MST'; // Mountain Standard Time
  elseif ( $time == '-06:00' ) $output = 'CST'; // Central Standard Time
  elseif ( $time == '-05:00' ) $output = 'EST'; // Eastern Standard Time
  //elseif ( $time == '-05:00' ) $output = 'IET'; // Indiana Eastern Standard Time
  elseif ( $time == '-04:00' ) $output = 'PRT'; // Puerto Rico and US Virgin Islands Time
  elseif ( $time == '-03:30' ) $output = 'CNT'; // Canada Newfoundland Time
  elseif ( $time == '-03:00' ) $output = 'AGT'; // Argentina Standard Time
  //elseif ( $time == '-03:00' ) $output = 'BET'; // Brazil Eastern Time
  elseif ( $time == '-01:00' ) $output = 'CAT'; // Central African Time
  
  return $output;
}

function googleplushangoutevent_organizer($event) {
  if ( isset($event['organizer']['id']) ) {
    $output = '<a href="https://plus.google.com/' . $event['organizer']['id'] . '" title="Organizer">' . $event['organizer']['displayName'] . '</a> ';
  } else {
    if ( strpos($event['organizer']['email'], '.calendar.') !== false )
      $output = '<a href="mailto:' . $event['creator']['email'] . '" title="Calendar">' . $event['organizer']['displayName'] . '</a> ';
    else
      $output = '<a href="mailto:' . $event['organizer']['email'] . '" title="Organizer">' . $event['organizer']['displayName'] . '</a> ';
  }
  
  return $output;
}
