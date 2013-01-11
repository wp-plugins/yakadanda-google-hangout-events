<?php
// Google+ Hangout Events 1st widget
class googlePlusHangoutEventsSingle extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'googleplus_hangout_events_single', // Base ID
			'Google+ Hangout Event', // Name
			array( 'description' => __( 'A countdown function to time of the Google+ hangout event', 'text_domain' ), ) // Args
		);
	}
  
  /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
    $data = get_option('yakadanda_googleplus_hangout_event_options');
    
    $events = googleplushangoutevent_response();
    $i = 0;
    $start_times = googleplushangoutevent_start_times($events, 1);
    
    if ($events):
      foreach ( $events as $event ): if ($event['hangoutLink']):
        
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? $event['summary'] : $instance['title'], $instance, $this->id_base );
        
        echo $before_widget;
        
        $before_title = $before_title . '<a href="' . $event['htmlLink'] . '" target="_blank">';
        $after_title = $after_title . '</a>';
        
        if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
        
        googleplushangoutevent_css( $data );
        
    ?>
        <div id="ghe-widget-single">
          <input id="ghe-start-times-single" name="ghe-start-times-single" type="hidden" value="<?php echo $start_times; ?>"/>
          <div class="ghe-vessel">
            <!-- <h4 class="ghe-title"><a href="<?php //echo $event['htmlLink']; ?>" target="_blank"><?php //echo $event['summary']; ?></a></h4> -->
            <div class="ghe-icon"></div>
            <div id="ghe-time-single-<?php echo $i; ?>" class="ghe-time"></div>
            <div class="ghe-detail"><?php echo $event['description']; ?></div>
            <div class="ghe-border"></div>
            <div id="ghe-countdown-single-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
          </div>

          <?php $i++; if ( $i == 1 ) break; ?>
        </div>
      <?php endif; endforeach; ?>
    <?php else: ?>
      <div id="ghe-widget-single">
        <div class="ghe-vessel"><p><?php googleplushangoutevent_widget_message(); ?></p></div>
      </div>
    <?php endif;
    
		echo $after_widget;
	}

	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

  /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
		?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>
		<?php 
	}

}// end of googlePlusHangoutEventsSingle class
add_action( 'widgets_init', create_function( '', 'register_widget( "googlePlusHangoutEventsSingle" );' ) );

// Google+ Hangout Events 2nd widget
class googlePlusHangoutEventsExtra extends WP_Widget {
  //Register widget with WordPress.
  public function __construct() {
    parent::__construct(
      'googleplus_hangout_events_extra', // Base ID
      'Google+ Hangout Events', // Name
      array('description' => __('A countdown function to time of the Google+ hangout events', 'text_domain'),) // Args
    );
  }
  
  // Front-end display of widget.
  public function widget( $args, $instance ) {
    $data = get_option('yakadanda_googleplus_hangout_event_options');
    
    $events = googleplushangoutevent_response();
    $i = 0;
    $i_last = googleplushangoutevent_i_last($events);
    $display = empty( $instance['display'] ) ? 1 : $instance['display'];
    $start_times = googleplushangoutevent_start_times($events, $display);
    
    extract( $args );
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Google+ Hangout Events' ) : $instance['title'], $instance, $this->id_base );
    
    echo $before_widget;
    if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
    
    googleplushangoutevent_css( $data );
    
    ?>
      <div id="ghe-widget-extra">
        <input id="ghe-start-times-extra" name="ghe-start-times-extra" type="hidden" value="<?php echo $start_times; ?>"/>
        <?php if ($events): ?>
          <?php foreach ( $events as $event ): if ($event['hangoutLink']): ?>
            <div class="ghe-vessel">
              <h4 class="ghe-title"><a href="<?php echo $event['htmlLink']; ?>" target="_blank"><?php echo $event['summary']; ?></a></h4>
              <div class="ghe-icon"></div>
              <div id="ghe-time-extra-<?php echo $i; ?>" class="ghe-time"></div>
              <div class="ghe-detail"><?php echo $event['description']; ?></div>
              <div class="ghe-border"></div>
              
              <?php if ( ($instance['countdown'] == 'first') && ($i==0) || empty($instance['countdown']) ): ?>
                <div id="ghe-countdown-extra-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
              <?php elseif ( $instance['countdown'] == 'all' ): ?>
                <div id="ghe-countdown-extra-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
              <?php endif; ?>
              
            </div>
            
            <?php
              $i++;
              if ( $i == $display ) break;
              if ( ($i>0) && ($i!=$i_last) ):
            ?>
              <div class="ghe-dotted"></div>
            <?php endif; ?>
            
          <?php endif; endforeach; ?>
        <?php else: ?>
          <div class="ghe-vessel"><p><?php googleplushangoutevent_widget_message(); ?></p></div>
        <?php endif; ?>
      </div>
      
    <?php
    
    echo $after_widget;
	}
  
  // Sanitize widget form values as they are saved.
  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['display'] = strip_tags($new_instance['display']);
    $instance['countdown'] = strip_tags($new_instance['countdown']);
    return $instance;
  }
  
  // Back-end widget form.
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
    if ( isset( $instance[ 'display' ] ) ) $display = $instance[ 'display' ];
    if ( isset( $instance[ 'countdown' ] ) ) $countdown = $instance[ 'countdown' ];
    ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
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
  
}// end of googlePlusHangoutEventsExtra class
add_action( 'widgets_init', create_function( '', 'register_widget( "googlePlusHangoutEventsExtra" );' ) );

function googleplushangoutevent_css( $data ) {
  ?>
    <style type="text/css">
      .widget_googleplus_hangout_events_single #ghe-widget-single .ghe-vessel,
      .widget_googleplus_hangout_events_extra #ghe-widget-extra .ghe-vessel {
        background: <?php echo $data['event_background'];?>;
      }
      .widget_googleplus_hangout_events_single h4.ghe-title a:link, .widget_googleplus_hangout_events_single h4.ghe-title a:visited, .widget_googleplus_hangout_events_single h4.ghe-title a:active, .widget_googleplus_hangout_events_single h4.ghe-title a:hover,
      .widget_googleplus_hangout_events_extra h4.ghe-title a:link, .widget_googleplus_hangout_events_extra h4.ghe-title a:visited, .widget_googleplus_hangout_events_extra h4.ghe-title a:active, .widget_googleplus_hangout_events_extra h4.ghe-title a:hover {
        color: <?php echo $data['title_color'];?>;
        font-family: <?php echo $data['title_theme'];?>;
        font-size: <?php echo $data['title_size'];?>px;
        <?php echo ( $data['title_style'] != 'italic' ) ? 'font-weight:' . $data['title_style'] : 'font-style:' . $data['title_style']; ?>;
      }
      .widget_googleplus_hangout_events_single .ghe-time,
      .widget_googleplus_hangout_events_extra .ghe-time {
        color: <?php echo $data['date_color'];?>;
        font-family: <?php echo $data['date_theme'];?>;
        font-size: <?php echo $data['date_size'];?>px;
        <?php echo ( $data['date_style'] != 'italic' ) ? 'font-weight:' . $data['date_style'] : 'font-style:' . $data['date_style']; ?>;
      }
      .widget_googleplus_hangout_events_single .ghe-detail,
      .widget_googleplus_hangout_events_extra .ghe-detail {
        color: <?php echo $data['detail_color'];?>;
        font-family: <?php echo $data['detail_theme'];?>;
        font-size: <?php echo $data['detail_size'];?>px;
        <?php echo ( $data['detail_style'] != 'italic' ) ? 'font-weight:' . $data['detail_style'] : 'font-style:' . $data['detail_style']; ?>;
      }
      .widget_googleplus_hangout_events_single .ghe-countdown,
      .widget_googleplus_hangout_events_extra .ghe-countdown {
        background: <?php echo $data['countdown_background'];?>;
        color: <?php echo $data['countdown_color'];?>;
        font-family: <?php echo $data['countdown_theme'];?>;
        font-size: <?php echo $data['countdown_size'];?>px;
        <?php echo ( $data['countdown_style'] != 'italic' ) ? 'font-weight:' . $data['countdown_style'] : 'font-style:' . $data['countdown_style']; ?>;
      }
      .widget_googleplus_hangout_events_single .ghe-countdown span,
      .widget_googleplus_hangout_events_extra .ghe-countdown span {
        font-size: <?php echo $data['countdown_size']-6;?>px;
      }
    </style>
  <?php
}

function googleplushangoutevent_response() {
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

    $today = date('c');

    $args = array(
      'orderBy' => 'startTime',
      'singleEvents' => true,
      'timeMin' => $today
    );
    
    $events = $service->events->listEvents( $data['calendar_id'], $args );

    $output = $events['items'];
  }
  
  return $output;
}

function googleplushangoutevent_i_last($events) {
  $summary = 0;
  if ($events) {
    foreach ( $events as $event ) {
      if ($event['hangoutLink']) {
        $summary = ++$summary;
      }
    }
  }
  return $summary;
}

function googleplushangoutevent_start_times($events, $display) {
  $output = null;
  $i = 0;
  $i_last = googleplushangoutevent_i_last($events);
  if ($events) {
    foreach ( $events as $event ) {
      if ($event['hangoutLink']) {
        $output .= $event['start']['dateTime'];

        $i++;
        if ( $i == $display ) break;
        if ( ($i>0) && ($i!=$i_last) ) $output .= ';';
      }
    }
  }
  
  return $output;
}

function googleplushangoutevent_widget_message() {
  $message = 'Not Connected.';
  $token = get_option('yakadanda_googleplus_hangout_event_access_token');
  if ($token) $message = 'No hangout event yet.';
  echo $message;
}
