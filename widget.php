<?php
//Adds Google+ Hangout Event widget.
class GooglePlus_Hangout_Event extends WP_Widget {
  
	//Register widget with WordPress.
	public function __construct() {
		parent::__construct(
	 		'googleplus_hangout_event', // Base ID
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
    
		extract( $args );
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Google+ Hangout Events' ) : $instance['title'], $instance, $this->id_base );
    
		echo $before_widget;
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
    
    $events = googleplushangoutevent_response();
    $i = 0;
    $i_last = googleplushangoutevent_i_last($events);
    $start_times = googleplushangoutevent_start_times($events, $data['display']);
    
    ?>
      <style type="text/css">
        .widget_googleplus_hangout_event #ghe-widget .ghe-vessel {
          background: <?php echo $data['event_background'];?>;
        }
        .widget_googleplus_hangout_event h4.ghe-title a:link, .widget_googleplus_hangout_event h4.ghe-title a:visited, .widget_googleplus_hangout_event h4.ghe-title a:active, .widget_googleplus_hangout_event h4.ghe-title a:hover {
          color: <?php echo $data['title_color'];?>;
          font-family: <?php echo $data['title_theme'];?>;
          font-size: <?php echo $data['title_size'];?>px;
          <?php echo ( $data['title_style'] != 'italic' ) ? 'font-weight:' . $data['title_style'] : 'font-style:' . $data['title_style']; ?>;
        }
        .widget_googleplus_hangout_event .ghe-time {
          color: <?php echo $data['date_color'];?>;
          font-family: <?php echo $data['date_theme'];?>;
          font-size: <?php echo $data['date_size'];?>px;
          <?php echo ( $data['date_style'] != 'italic' ) ? 'font-weight:' . $data['date_style'] : 'font-style:' . $data['date_style']; ?>;
        }
        .widget_googleplus_hangout_event .ghe-detail {
          color: <?php echo $data['detail_color'];?>;
          font-family: <?php echo $data['detail_theme'];?>;
          font-size: <?php echo $data['detail_size'];?>px;
          <?php echo ( $data['detail_style'] != 'italic' ) ? 'font-weight:' . $data['detail_style'] : 'font-style:' . $data['detail_style']; ?>;
        }
        .widget_googleplus_hangout_event .ghe-countdown {
          background: <?php echo $data['countdown_background'];?>;
          color: <?php echo $data['countdown_color'];?>;
          font-family: <?php echo $data['countdown_theme'];?>;
          font-size: <?php echo $data['countdown_size'];?>px;
          <?php echo ( $data['countdown_style'] != 'italic' ) ? 'font-weight:' . $data['countdown_style'] : 'font-style:' . $data['countdown_style']; ?>;
        }
        .widget_googleplus_hangout_event .ghe-countdown span {
          font-size: <?php echo $data['countdown_size']-6;?>px;
        }
      </style>
      
      <div id="ghe-widget">
        <input id="ghe-start-times" name="ghe-start-times" type="hidden" value="<?php echo $start_times; ?>"/>
        <?php if ($events): ?>
          <?php foreach ( $events as $event ): if ($event['hangoutLink']): ?>
            <div class="ghe-vessel">
              <h4 class="ghe-title"><a href="<?php echo $event['htmlLink']; ?>" target="_blank"><?php echo $event['summary']; ?></a></h4>
              <div class="ghe-icon"></div>
              <div id="ghe-time-<?php echo $i; ?>" class="ghe-time"></div>
              <div class="ghe-detail"><?php echo $event['description']; ?></div>
              <div class="ghe-border"></div>
              <div id="ghe-countdown-<?php echo $i; ?>" class="ghe-countdown"><?php echo $event['start']['dateTime']; ?></div>
            </div>

            <?php
              $i++;
              if ( $i == $data['display'] ) break;
              if ( ($i>0) && ($i!=$i_last) ):
            ?>
              <div class="ghe-dotted"></div>
            <?php endif; ?>

          <?php endif; endforeach; ?>
        <?php else: ?>
          <div class="ghe-vessel"><p>No hangout event yet.</p></div>
        <?php endif; ?>
      </div>
      
    <?php
    
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

} // class GooglePlus_Hangout_Event

// register GooglePlus_Hangout_Event_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "googleplus_hangout_event" );' ) );

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
  $client->setAccessToken($token);
  
  $service = new Google_CalendarService($client);
  
  $today = date('c');
  
  $args = array(
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => $today
  );
  
  $events = $service->events->listEvents( $data['calendar_id'], $args );
  
  return $events['items'];
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
