<?php
/**
 * Google+ Events as 1st widget
 */
class googlePlusEvents extends WP_Widget {
  //Register widget with WordPress.
  public function __construct() {
    parent::__construct(
      'googleplus_events', // Base ID
      'Google+ Events', // Name
      array('description' => __('A countdown function to time of the Google+ events', 'text_domain'),) // Args
    );
  }
  
  // Front-end display of widget.
  public function widget( $args, $instance ) {
    $events = googleplushangoutevent_response();
    $i = 0;
    $display = isset( $instance['display'] ) ? $instance['display'] : 1;
    $start_times = googleplushangoutevent_start_times($events, $display, 'normal');
    $creator = 1;
    $author = isset($instance['author']) ? $instance['author'] : 'self';
    $countdown = isset($instance['countdown']) ? $instance['countdown'] : 'first';
    
    extract( $args );
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Google+ Events' ) : $instance['title'], $instance, $this->id_base );
    
    echo $before_widget;
    if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
    
    ?>
      <div id="ghe-1st-widget">
        <input id="ghe-start-times-1st" name="ghe-start-times-1st" type="hidden" value="<?php echo $start_times; ?>"/>
        <?php if ($events): ?>
          <?php foreach ( $events as $event ):
            $hangoutlink = isset($event['hangoutLink']) ? $event['hangoutLink'] : false;
            $visibility = isset($event['visibility']) ? $event['visibility'] : 'public';
          
            if ( $author == 'self' ) $creator = isset( $event['creator']['self'] ) ? $event['creator']['self'] : 0;
            if ( !$hangoutlink && $creator && ($visibility != 'private') ): 
          ?>
            <div class="ghe-vessel">
              <h4 class="ghe-title"><?php echo $event['summary']; ?></h4>
              <div class="ghe-time"><?php echo googleplushangoutevent_time($event['start']['dateTime'], $event['end']['dateTime'], 'widget'); ?></div>
              <div class="ghe-detail"><?php echo $event['description']; ?></div>
              
              <ul class="ghe-icons">
                <li><a href="#" target="_blank" onclick="return false;">Event</a></li>
              </ul>
              
              <?php if ( ($countdown == 'first') && ($i==0) ): ?>
                <div id="ghe-countdown-1st-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
              <?php elseif ( $countdown == 'all' ): ?>
                <div id="ghe-countdown-1st-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
              <?php endif; ?>
              <div class="ghe-button"><a href="<?php echo $event['htmlLink'] ?>" target="_blank">View Event on Google+</a></div>
            </div>
            
            <?php $i++; if ( $i == $display ) break; ?>
            
          <?php endif; endforeach; ?>
        <?php endif; if ($i == 0): ?>
          <div class="ghe-vessel"><p><?php googleplushangoutevent_widget_message('normal'); ?></p></div>
        <?php endif; ?>
      </div>
      
    <?php
    
    echo $after_widget;
	}
  
  // Sanitize widget form values as they are saved.
  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['author'] = strip_tags($new_instance['author']);
    $instance['display'] = strip_tags($new_instance['display']);
    $instance['countdown'] = strip_tags($new_instance['countdown']);
    return $instance;
  }
  
  // Back-end widget form.
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
    if ( isset( $instance[ 'author' ] ) ) $author = $instance[ 'author' ];
    if ( isset( $instance[ 'display' ] ) ) $display = $instance[ 'display' ];
    if ( isset( $instance[ 'countdown' ] ) ) $countdown = $instance[ 'countdown' ];
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Author:' ); ?></label><br/>
        <label title="Self">
          <input type="radio" value="self" name="<?php echo $this->get_field_name( 'author' ); ?>" <?php echo ( ( $author == 'self' ) || empty( $author ) ) ? 'checked="checked"' : null; ?>>
          <span>Self</span>
        </label>
        <br/>
        <label title="All">
          <input type="radio" value="all" name="<?php echo $this->get_field_name( 'author' ); ?>" <?php echo ( $author == 'all' ) ? 'checked="checked"' : null; ?>>
          <span>All</span>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e( 'Display:' ); ?></label><br/>
        <select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
          <option value="1" <?php echo ($display == 1) ? 'selected="selected"': null; ?>>1&nbsp;</option>
          <option value="2" <?php echo ($display == 2) ? 'selected="selected"': null; ?>>2&nbsp;</option>
          <option value="3" <?php echo ($display == 3) ? 'selected="selected"': null; ?>>3&nbsp;</option>
          <option value="4" <?php echo ($display == 4) ? 'selected="selected"': null; ?>>4&nbsp;</option>
          <option value="5" <?php echo ($display == 5) ? 'selected="selected"': null; ?>>5&nbsp;</option>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'countdown' ); ?>"><?php _e( 'Countdown:' ); ?></label><br/>
        <label title="Display countdown clock on first only">
          <input type="radio" value="first" name="<?php echo $this->get_field_name( 'countdown' ); ?>" <?php echo ( ($countdown == 'first') || empty($countdown) ) ? 'checked="checked"' : null; ?>>
          <span>Display countdown clock on first only</span>
        </label>
        <br/>
        <label title="Display countdown clock on all">
          <input type="radio" value="all" name="<?php echo $this->get_field_name( 'countdown' ); ?>" <?php echo ($countdown == 'all') ? 'checked="checked"' : null; ?>>
          <span>Display countdown clock on all</span>
        </label>
        <br/>
        <label title="Display countdown clock on none">
          <input type="radio" value="none" name="<?php echo $this->get_field_name( 'countdown' ); ?>" <?php echo ($countdown == 'none') ? 'checked="checked"' : null; ?>>
          <span>Display countdown clock on none</span>
        </label>
      </p>
    <?php 
  }
  
}/* end of googlePlusHangoutEvents class */
add_action( 'widgets_init', create_function( '', 'register_widget( "googlePlusEvents" );' ) );

/**
 * Google+ Hangout Events as 2nd widget
 */
class googlePlusHangoutEvents extends WP_Widget {
  //Register widget with WordPress.
  public function __construct() {
    parent::__construct(
      'googleplus_hangout_events', // Base ID
      'Google+ Hangout Events', // Name
      array('description' => __('A countdown function to time of the Google+ hangout events', 'text_domain'),) // Args
    );
  }
  
  // Front-end display of widget.
  public function widget( $args, $instance ) {
    $events = googleplushangoutevent_response();
    $i = 0;
    $display = isset( $instance['display'] ) ? $instance['display'] : 1;
    $start_times = googleplushangoutevent_start_times($events, $display, 'hangout');
    $creator = 1;
    $author = isset($instance['author']) ? $instance['author'] : 'self';
    $countdown = isset($instance['countdown']) ? $instance['countdown'] : 'first';
    
    extract( $args );
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Google+ Hangout Events' ) : $instance['title'], $instance, $this->id_base );
    
    echo $before_widget;
    if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
    
    ?>
      <div id="ghe-2nd-widget">
        <input id="ghe-start-times-2nd" name="ghe-start-times-2nd" type="hidden" value="<?php echo $start_times; ?>"/>
        <?php if ($events): ?>
          <?php foreach ( $events as $event ):
            $hangoutlink = isset($event['hangoutLink']) ? $event['hangoutLink'] : false;
            $visibility = isset($event['visibility']) ? $event['visibility'] : 'public';
            
            if ( $author == 'self' ) $creator = isset( $event['creator']['self'] ) ? $event['creator']['self'] : 0;
            if ( $hangoutlink && $creator && ($visibility != 'private') ):
          ?>
            <?php $onair = googleplushangoutevent_onair($event['start']['dateTime'], $event['end']['dateTime']); ?>
            <div class="ghe-vessel">
              <h4 class="ghe-title"><?php echo $event['summary']; ?></h4>
              <div class="ghe-time"><?php echo googleplushangoutevent_time($event['start']['dateTime'], $event['end']['dateTime'], 'widget'); ?></div>
              <div class="ghe-detail"><?php echo $event['description']; ?></div>
              
              <ul class="ghe-icons">
                <li><a href="#" target="_blank" onclick="return false;">Event</a></li>
                <li><a href="#" target="_blank" onclick="return false;">Hangout</a></li>
                <?php if ($onair): ?>
                  <li><a href="#" target="_blank" onclick="return false;">On Air</a></li>
                <?php endif; ?>
              </ul>
              
              <?php if ( ($countdown == 'first') && ($i==0) ): ?>
                <div id="ghe-countdown-2nd-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
              <?php elseif ( $countdown == 'all' ): ?>
                <div id="ghe-countdown-2nd-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
              <?php endif; ?>
              
              <div class="ghe-button"><a href="<?php echo $event['htmlLink'] ?>" target="_blank">View Event on Google+</a></div>
            </div>
            
            <?php $i++; if ( $i == $display ) break; ?>
            
          <?php endif; endforeach; ?>
        <?php endif; if ($i == 0): ?>
          <div class="ghe-vessel"><p><?php googleplushangoutevent_widget_message('hangout'); ?></p></div>
        <?php endif; ?>
      </div>
      
    <?php
    
    echo $after_widget;
	}
  
  // Sanitize widget form values as they are saved.
  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['author'] = strip_tags($new_instance['author']);
    $instance['display'] = strip_tags($new_instance['display']);
    $instance['countdown'] = strip_tags($new_instance['countdown']);
    return $instance;
  }
  
  // Back-end widget form.
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
    if ( isset( $instance[ 'author' ] ) ) $author = $instance[ 'author' ];
    if ( isset( $instance[ 'display' ] ) ) $display = $instance[ 'display' ];
    if ( isset( $instance[ 'countdown' ] ) ) $countdown = $instance[ 'countdown' ];
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Author:' ); ?></label><br/>
        <label title="Self">
          <input type="radio" value="self" name="<?php echo $this->get_field_name( 'author' ); ?>" <?php echo ( ( $author == 'self' ) || empty( $author ) ) ? 'checked="checked"' : null; ?>>
          <span>Self</span>
        </label>
        <br/>
        <label title="All">
          <input type="radio" value="all" name="<?php echo $this->get_field_name( 'author' ); ?>" <?php echo ( $author == 'all' ) ? 'checked="checked"' : null; ?>>
          <span>All</span>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e( 'Display:' ); ?></label><br/>
        <select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
          <option value="1" <?php echo ($display == 1) ? 'selected="selected"': null; ?>>1&nbsp;</option>
          <option value="2" <?php echo ($display == 2) ? 'selected="selected"': null; ?>>2&nbsp;</option>
          <option value="3" <?php echo ($display == 3) ? 'selected="selected"': null; ?>>3&nbsp;</option>
          <option value="4" <?php echo ($display == 4) ? 'selected="selected"': null; ?>>4&nbsp;</option>
          <option value="5" <?php echo ($display == 5) ? 'selected="selected"': null; ?>>5&nbsp;</option>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'countdown' ); ?>"><?php _e( 'Countdown:' ); ?></label><br/>
        <label title="Display countdown clock on first only">
          <input type="radio" value="first" name="<?php echo $this->get_field_name( 'countdown' ); ?>" <?php echo ( ($countdown == 'first') || empty($countdown) ) ? 'checked="checked"' : null; ?>>
          <span>Display countdown clock on first only</span>
        </label>
        <br/>
        <label title="Display countdown clock on all">
          <input type="radio" value="all" name="<?php echo $this->get_field_name( 'countdown' ); ?>" <?php echo ($countdown == 'all') ? 'checked="checked"' : null; ?>>
          <span>Display countdown clock on all</span>
        </label>
        <br/>
        <label title="Display countdown clock on none">
          <input type="radio" value="none" name="<?php echo $this->get_field_name( 'countdown' ); ?>" <?php echo ($countdown == 'none') ? 'checked="checked"' : null; ?>>
          <span>Display countdown clock on none</span>
        </label>
      </p>
    <?php 
  }
  
}/* end of googlePlusHangoutEvents class */
add_action( 'widgets_init', create_function( '', 'register_widget( "googlePlusHangoutEvents" );' ) );

function googleplushangoutevent_css() {
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  ?>
    <style type="text/css">
      .widget_googleplus_events #ghe-1st-widget .ghe-vessel,
      .widget_googleplus_hangout_events #ghe-2nd-widget .ghe-vessel {
        background: <?php echo $data['widget_background'];?>;
        border: 1px solid <?php echo $data['widget_border'];?>;
      }
      .widget_googleplus_events h4.ghe-title,
      .widget_googleplus_hangout_events h4.ghe-title {
        color: <?php echo $data['title_color'];?>;
        font-family: <?php echo $data['title_theme'];?>;
        font-size: <?php echo $data['title_size'];?>px;
        <?php echo ( $data['title_style'] != 'italic' ) ? 'font-weight:' . $data['title_style'] : 'font-style:' . $data['title_style']; ?>;
      }
      .widget_googleplus_events .ghe-time,
      .widget_googleplus_hangout_events .ghe-time {
        color: <?php echo $data['date_color'];?>;
        font-family: <?php echo $data['date_theme'];?>;
        font-size: <?php echo $data['date_size'];?>px;
        <?php echo ( $data['date_style'] != 'italic' ) ? 'font-weight:' . $data['date_style'] : 'font-style:' . $data['date_style']; ?>;
      }
      .widget_googleplus_events .ghe-detail,
      .widget_googleplus_hangout_events .ghe-detail {
        color: <?php echo $data['detail_color'];?>;
        font-family: <?php echo $data['detail_theme'];?>;
        font-size: <?php echo $data['detail_size'];?>px;
        <?php echo ( $data['detail_style'] != 'italic' ) ? 'font-weight:' . $data['detail_style'] : 'font-style:' . $data['detail_style']; ?>;
      }
      .widget_googleplus_events #ghe-1st-widget .ghe-vessel ul.ghe-icons li,
      .widget_googleplus_hangout_events #ghe-2nd-widget .ghe-vessel ul.ghe-icons li {
        background: <?php echo $data['icon_background'];?>;
        border: 1px solid <?php echo $data['icon_border'];?>;
        font-family: <?php echo $data['icon_theme'];?>;
        font-size: <?php echo $data['icon_size'];?>px;
        <?php echo ( $data['icon_style'] != 'italic' ) ? 'font-weight:' . $data['icon_style'] : 'font-style:' . $data['icon_style']; ?>;
      }
      .widget_googleplus_events #ghe-1st-widget .ghe-vessel ul.ghe-icons li a,
      .widget_googleplus_hangout_events #ghe-2nd-widget .ghe-vessel ul.ghe-icons li a {
        color: <?php echo $data['icon_color'];?>;
      }
      .widget_googleplus_events #ghe-1st-widget .ghe-vessel ul.ghe-icons li a:hover,
      .widget_googleplus_hangout_events #ghe-2nd-widget .ghe-vessel ul.ghe-icons li a:hover {
        color: #D64337;
      }
      .widget_googleplus_events .ghe-countdown,
      .widget_googleplus_hangout_events .ghe-countdown {
        background: <?php echo $data['countdown_background'];?>;
        color: <?php echo $data['countdown_color'];?>;
        font-family: <?php echo $data['countdown_theme'];?>;
        font-size: <?php echo $data['countdown_size'];?>px;
        <?php echo ( $data['countdown_style'] != 'italic' ) ? 'font-weight:' . $data['countdown_style'] : 'font-style:' . $data['countdown_style']; ?>;
      }
      .widget_googleplus_events .ghe-countdown span,
      .widget_googleplus_hangout_events .ghe-countdown span {
        font-size: <?php echo $data['countdown_size'];?>px;
      }
    </style>
  <?php
}
add_action('wp_head', 'googleplushangoutevent_css');

function googleplushangoutevent_response( $months = null ) {
  require_once( dirname( __FILE__ ) . '/src/Google_Client.php');
  require_once( dirname( __FILE__ ) . '/src/contrib/Google_CalendarService.php');
  
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  $client = new Google_Client();
  $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
  
  // Visit https://code.google.com/apis/console?api=calendar to generate your
  // client id, client secret, and to register your redirect uri.
  $client->setClientId( $data['client_id'] );
  $client->setClientSecret( $data['client_secret'] );
  $client->setRedirectUri( GPLUS_HANGOUT_EVENT_URL . '/oauth2callback.php' );
  $client->setScopes( 'https://www.googleapis.com/auth/calendar.readonly' );
  $client->setDeveloperKey( $data['api_key'] );
  
  $token = get_option('yakadanda_googleplus_hangout_event_access_token');
  
  $output = null;
  if ($token) {
    $client->setAccessToken($token);
    
    $service = new Google_CalendarService($client);
    
    // the date is today
    $timeMin = date('c');
    
    if ( $months ) {
      // the today date minus by months
      $timeMin = date('c', strtotime("-" . $months . " month", strtotime($timeMin)));
    }
    
    $args = array(
      'maxResults' => 20,
      'orderBy' => 'startTime',
      'singleEvents' => true,
      'timeMin' => $timeMin
    );
    
    $events = $service->events->listEvents( $data['calendar_id'], $args );
    
    $output = $events['items'];
  }
  
  return $output;
}

function googleplushangoutevent_i_last($events, $option = 'all') {
  $summary = 0;
  $event_filter = true;
  if ($events) {
    foreach ( $events as $event ) {
      $hangoutlink = isset($event['hangoutLink']) ? $event['hangoutLink'] : false;
      
      if ($option == 'hangout') $event_filter = $hangoutlink;
      elseif ($option == 'normal') $event_filter = !$hangoutlink;
      
      if ($event_filter) {
        $summary = ++$summary;
      }
    }
  }
  return $summary;
}

function googleplushangoutevent_start_times($events, $display, $option = 'all') {
  $output = null;
  $event_filter = true;
  $i = 0;
  $i_last = googleplushangoutevent_i_last($events, $option);
  if ($events) {
    foreach ( $events as $event ) {
      $hangoutlink = isset($event['hangoutLink']) ? $event['hangoutLink'] : false;
      $visibility = isset($event['visibility']) ? $event['visibility'] : 'public';
      
      if ($option == 'hangout') $event_filter = $hangoutlink;
      elseif ($option == 'normal') $event_filter = !$hangoutlink;
      
      if ( $event_filter && ($visibility != 'private') ) {
        $output .= $event['start']['dateTime'];

        $i++;
        if ( $i == $display ) break;
        if ( ($i>0) && ($i!=$i_last) ) $output .= ';';
      }
    }
  }
  
  return $output;
}

function googleplushangoutevent_widget_message($type) {
  $message = 'Not Connected.';
  $token = get_option('yakadanda_googleplus_hangout_event_access_token');
  if ($token) {
    if ($type == 'normal') $message = 'No event yet.';
    else $message = 'No hangout event yet.';
  }
  echo $message;
}

function googleplushangoutevent_onair($datetime1, $datetime2) {
  $output = false;
  $today = date('c');
  $start = (strtotime($datetime1) - strtotime($today)) / 60;
  $finish = (strtotime($datetime2) - strtotime($today)) / 60;
  
  if ( ($start <= 0) && ($finish >= 0) ) {
    $output = true;
  }
  
  return $output;
}
