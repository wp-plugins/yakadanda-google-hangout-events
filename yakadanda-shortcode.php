<?php
/*
 * [google+events]
 * [google+events type="all" limit="5"]
 * type = all, normal, hangout
 * limit = number, it limited to 20
 */
function googleplushangoutevent_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'type' => 'all',
    'limit' => 20
  ), $atts ) );
  
  if ($limit > 20) $limit = 20;
  
  $events = googleplushangoutevent_response();
  $output = 'No Event.';
  $i = 0;
  $filter = true;
  
  if ($events) {
    $output = null;
    foreach ($events as $event) {
      if ($type == 'normal') $filter = !$event['hangoutLink'];
      elseif ($type == 'hangout') $filter = $event['hangoutLink'];
      
      if ( $filter && ($event['visibility'] != 'private') ) { $i++;
        $output .= '<div class="yghe-event">';
        $output .= '<div class="yghe-creator"><a href="https://plus.google.com/' . $event['creator']['id'] . '" title="' . $event['creator']['displayName'] . '">' . $event['creator']['displayName'] . '</a> ' . googleplushangoutevent_ago($event['created'], $event['updated']) . '</div>';
        $output .= '<div class="yghe-event-title"><a href="' . $event['htmlLink'] . '" title="' . $event['summary'] . '">' . $event['summary'] . '</a></div>';
        $output .= '<div class="yghe-event-time">' . googleplushangoutevent_time($event['start']['dateTime'], $event['end']['dateTime'], 'shortcode') . '</div>';

        if ($event['location']) {
          $output .= '<div class="yghe-event-location"><a href="http://maps.google.com/?q=' . $event['location'] . '" title="' . $event['location'] . '">' . $event['location'] . '</a></div>';
        } else {
          $output .= '<div class="yghe-event-hangout"><a href="' . $event['hangoutLink'] . '" title="Google+ Hangout">Google+ Hangout</a></div>';
        }

        $output .= '<div class="yghe-event-description">'. $event['description'] . '</div>';
        $output .= '</div>';

        if ($limit == $i) break;
      }
    }
  }
  
  return $output;
}
add_shortcode( 'google+events', 'googleplushangoutevent_shortcode' );

function googleplushangoutevent_time($startdate, $finishdate, $type) {
  $diff = round(abs(strtotime($finishdate)-strtotime($startdate))/86400);
  
  $begindate = str_split($startdate, 19);
  
  $output = null;
  
  if ( $type == 'shortcode'  ) {
    $timezone = googleplushangoutevent_timezone( $begindate[1] );
    $output = date('D, F d, g:i A', strtotime($begindate[0])) . '&nbsp;' . $timezone;
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
    } elseif ( $type == 'widget' ) {
      $output = '<span>' . date('F jS Y g:i a', strtotime($begindate[0])) . '&nbsp;to</span><br><span>' . date('F jS Y g:i a', strtotime($enddate[0])) . '&nbsp;' . $timezone . '</span>';
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