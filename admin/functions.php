<?php
function googleplushangoutevent_google_lib() {
  require_once realpath(dirname(__FILE__) . '/../src/Google/autoload.php');
}

function googleplushangoutevent_callback($buffer) {
  return $buffer;
}

add_action('init', 'googleplushangoutevent_add_ob_start');
function googleplushangoutevent_add_ob_start() {
  ob_start("googleplushangoutevent_callback");
}

add_action('wp_footer', 'googleplushangoutevent_flush_ob_end');
function googleplushangoutevent_flush_ob_end() {
  ob_end_flush();
}

function googleplushangoutevent_event_init() {
  return true;
}

function googleplushangoutevent_get_page() {
  $requet_uri = str_replace('/wp-admin/', '', $_SERVER['REQUEST_URI']);
  
  return ($requet_uri) ? $requet_uri : 'index.php';
}

function googleplushangoutevent_is_page() {
  $url = googleplushangoutevent_get_page();
  
  $pluginPages = array(
      'admin.php?page=googleplushangoutevent-all-events',
      'admin.php?page=googleplushangoutevent-settings'
    );
  
  $response = false;
  
  foreach ($pluginPages as $pluginPage) {
    if (strpos($url, $pluginPage) !== false) {
      $response = true;
    }
  }
  
  return $response;
}

// Register menu page
add_action('admin_menu', 'googleplushangoutevent_register_menu_page');
function googleplushangoutevent_register_menu_page() {
  add_menu_page(__('All Events', 'yakadanda-google-hangout-events'), __('Events', 'yakadanda-google-hangout-events'), 'add_users', 'googleplushangoutevent-all-events', 'googleplushangoutevent_page_all_events', 'none', 918276354);
  
  $events_page = add_submenu_page('googleplushangoutevent-all-events', __('All Events', 'yakadanda-google-hangout-events'), __('All Events', 'yakadanda-google-hangout-events'), 'manage_options', 'googleplushangoutevent-all-events', 'googleplushangoutevent_page_all_events');
  add_action('load-' . $events_page, 'googleplushangoutevent_admin_add_help_tab');
  
  $settings_page = add_submenu_page('googleplushangoutevent-all-events', __('Settings', 'yakadanda-google-hangout-events'), __('Settings', 'yakadanda-google-hangout-events'), 'manage_options', 'googleplushangoutevent-settings', 'googleplushangoutevent_page_settings');
  add_action('load-' . $settings_page, 'googleplushangoutevent_admin_add_help_tab');
}

function googleplushangoutevent_page_all_events() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'yakadanda-google-hangout-events'));
  }

  $message = null;

  // extend event
  if ( isset($_POST['extend_event']) ) {
    $image_src = get_option('googleplushangoutevent_' . $_GET['id']);
    if ($_POST['image_location'] != $image_src) {
      update_option('googleplushangoutevent_' . $_GET['id'], $_POST['image_location']);
      $message = array('class' => 'updated', 'msg' => __('Event updated.', 'yakadanda-google-hangout-events') );
    }
  }
  
  $event = null;
  if (isset($_GET['action']) && ($_GET['action'] == 'delete') && isset($_GET['id'])) {
    googleplushangoutevent_delete_event($_GET['id']);
  } elseif (isset($_GET['action']) && ($_GET['action'] == 'extend') && isset($_GET['id'])) {
    $event = googleplushangoutevent_extend_event($_GET['id']);
    $image_src = get_option('googleplushangoutevent_' . $event['id']);
  }
  
  $title = __('Extend Event', 'yakadanda-google-hangout-events');
  if ( !isset($_GET['action']) || ($_GET['action'] != 'extend') ) {
    $listTable = new googleplushangoutevent_List_Table();
    $listTable->prepare_items();
    $title = __('All Events', 'yakadanda-google-hangout-events');
  }
  
  include('page-events.php');
}

function googleplushangoutevent_page_settings() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'yakadanda-google-hangout-events'));
  }

  $message = null;

  /* postData */
  if ( isset($_POST['update_settings']) ) {
    $data = googleplushangoutevent_get_settings();
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
      'icon_hover' => $_POST['icon_hover'],
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
    
    update_option('yakadanda_googleplus_hangout_event_options', $value);
    $message = array('class' => 'updated', 'msg' => __('Settings updated.', 'yakadanda-google-hangout-events'));
    
    $granted = false;
    if ( ($data['calendar_id'] != $_POST['calendar_id']) || ($data['api_key'] != $_POST['api_key']) || ($data['client_id'] != $_POST['client_id']) || ($data['client_secret'] != $_POST['client_secret']) ) $granted = true;
    if ( empty($token) && $data['calendar_id'] && $data['api_key'] && $data['client_id'] && $data['client_secret'] ) $granted = true;
    
    if ($granted) {
      // load google library
      googleplushangoutevent_google_lib();
      
      $client = new Google_Client();
      $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
      
      // Visit https://code.google.com/apis/console?api=calendar to generate your
      // client id, client secret, and to register your redirect uri.
      $client->setClientId( $_POST['client_id'] );
      $client->setClientSecret( $_POST['client_secret'] );
      $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
      
      $scopes = array('https://www.googleapis.com/auth/calendar');
      
      $client->setScopes( $scopes );
      $client->setDeveloperKey( $_POST['api_key'] );
      
      //http://stackoverflow.com/questions/8942340/get-refresh-token-google-api
      //http://stackoverflow.com/questions/22268134/cant-get-an-offline-access-token-with-the-google-php-sdk-1-0-0
      //https://developers.google.com/accounts/docs/OAuth2WebServer#offline
      $client->setApprovalPrompt('force');
      $client->setAccessType('offline');
      
      wp_redirect( $client->createAuthUrl() ); exit;
    }
  }
  /* endPostData*/
  
  /* OAuth2Callback */
  if (isset($_GET['code'])) {
    // make null the token from database
    update_option('yakadanda_googleplus_hangout_event_access_token', null);
    
    // load google library
    googleplushangoutevent_google_lib();
    
    $data = get_option('yakadanda_googleplus_hangout_event_options');
    
    $client = new Google_Client();
    $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
    
    // Visit https://code.google.com/apis/console?api=calendar to generate your
    // client id, client secret, and to register your redirect uri.
    $client->setClientId( $data['client_id'] );
    $client->setClientSecret( $data['client_secret'] );
    $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
    
    $scopes = array('https://www.googleapis.com/auth/calendar');
    
    $client->setScopes( $scopes );
    $client->setDeveloperKey( $data['api_key'] );
    
    $service = new Google_Service_Calendar($client);
    
    $client->authenticate($_GET['code']);
    $option = 'yakadanda_googleplus_hangout_event_access_token';
    
    $client->setAccessToken($client->getAccessToken());
    
    $calendar_list = googleplushangoutevent_calendar_list($service);
    $calendar_ids = array();
    
    foreach ( $calendar_list as $calendar) { $calendar_ids[] = $calendar['id']; }
    
    $is_calendar_id = in_array($data['calendar_id'], $calendar_ids);
    
    if ($is_calendar_id) {
      update_option( $option, $client->getAccessToken() );
      $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => __('Connection to Google API succeeded.', 'yakadanda-google-hangout-events')));
    } else {
      $message = maybe_serialize(array('cookie' => 1, 'class' => 'error', 'msg' => sprintf(__('Please login as %s.', 'yakadanda-google-hangout-events'), $data['calendar_id'])));
    }
    
    setcookie('googleplushangoutevent_message', $message, time()+1, '/');
    
    wp_redirect( admin_url('admin.php?page=googleplushangoutevent-settings') ); exit;
  }
  /* endOAuth2Callback */
  
  $data = googleplushangoutevent_get_settings();
  
  // message
  if (isset($_COOKIE['googleplushangoutevent_message'])) { $message = maybe_unserialize(stripslashes($_COOKIE['googleplushangoutevent_message'])); }
  
  include('page-settings.php');
}

function googleplushangoutevent_admin_add_help_tab() {
  $screen = get_current_screen();
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-setup',
      'title' => __('Setup', 'yakadanda-google-hangout-events'),
      'content' => googleplushangoutevent_section_setup()
  ));
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-embedded-posts',
      'title' => __('Embedded Posts', 'yakadanda-google-hangout-events'),
      'content' => googleplushangoutevent_section_embedded_posts()
  ));
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-shortcode',
      'title' => __('Shortcode', 'yakadanda-google-hangout-events'),
      'content' => googleplushangoutevent_section_shortcode()
  ));
  
  
}

function googleplushangoutevent_section_setup() {
  $output = '<div class="google-web-starter-kit">';
  $output .= sprintf(__('<h1>How to get your Google Api Key, Client ID, and Client Secret</h1>', 'yakadanda-google-hangout-events'));
  $output .= '<ol>';

  $href = 'https://cloud.google.com/console/project';
  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-01.png';
  $output .= sprintf(__('<li>At <a href="%s" target="_blank">https://cloud.google.com/console/project</a> create new project.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $href, $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-02.png';
  $output .= sprintf(__('<li>Fill New Project modal dialog with your suitable information.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-03.png';
  $output .= sprintf(__('<li>On <u>Overview</u> submenu of your project, go to the <u>APIs</u> submenu under <u>APIS & auth</u> menu.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-04.png';
  $output .= sprintf(__('<li>Turn on Calendar API.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-05.png';
  $output .= sprintf(__('<li>On <u>Credentials</u> submenu, create new Client ID.<br/><img src="%s"/><br/>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-06.png';
  $output .= sprintf(__('Asking to configure consent screen. Leave APPLICATION TYPE with Web application.<br/><img src="%s"/><br/>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-07.png';
  $output .= sprintf(__('Just fill with your suitable information, and then click Save button.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $output .= sprintf(__('<li>Setup Create Client ID form.<br/>a. Leave APPLICATION TYPE with <span>Web application</span>.<br/>', 'yakadanda-google-hangout-events'));

  $url = home_url();
  $output .= sprintf(__('b. Fill AUTHORIZED JAVASCRIPT ORIGINS with <code>%s</code><br/>', 'yakadanda-google-hangout-events'), $url);

  $url = admin_url('admin.php?page=googleplushangoutevent-settings');
  $output .= sprintf(__('c. And AUTHORIZED REDIRECT URI with <code>%s</code><br/>', 'yakadanda-google-hangout-events'), $url);

  $output .= '<img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-08.png"/></li>';

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-09.png';
  $output .= sprintf(__('<li>Still on <u>Credentials</u> submenu, create new Key.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-10.png';
  $output .= sprintf(__('<li>Click Server key button.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-setup-11.png';
  $output .= sprintf(__('<li>Leave the textarea blank to make any IPs allowed, and then click Create button.<br/><img src="%s"/></li>', 'yakadanda-google-hangout-events'), $src);

  $output .= sprintf(__('<li>Well done, now you have CLIENT ID, and CLIENT SECRET below <strong>Client ID for web application</strong> and API KEY below <strong>Key for server applications</strong>.</li>', 'yakadanda-google-hangout-events'));

  $output .= '</ol>';
  $output .= '</div>';

  return $output;
}

function googleplushangoutevent_section_shortcode() {
  $output = '<div class="google-web-starter-kit">';
  $output .= '<h1>Shortcode</h1>';
  $output .= '<p><strong>' . __('Examples', 'yakadanda-google-hangout-events') . '</strong></p>';
  $output .= '<ul>';
  $output .= '<li><code>[google+events]</code></li>';
  $output .= '<li><code>[google+events type="hangout"]</code></li>';
  $output .= '<li><code>[google+events src="gplus"]</code></li>';
  $output .= '<li><code>[google+events limit="3"]</code></li>';
  $output .= '<li><code>[google+events past="2"]</code></li>';
  $output .= '<li><code>[google+events author="all"]</code></li>';
  $output .= '<li><code>[google+events limit="5" type="normal" past="1" author="all"]</code></li>';
  $output .= '<li><code>[google+events id="xxxxxxxxxxxxxxxxxxxxxxxxxx"]</code></li>';
  $output .= '<li><code>[google+events filter_out="xxxxxxxxxxxxxxxxxxxxxxxxxx,xxxxxxxxxxxxxxxxxxxxxxxxxx"]</code></li>';
  $output .= '<li><code>[google+events search="free text search terms"]</code></li>';
  $output .= '<li><code>[google+events attendees="show"]</code></li>';
  $output .= '<li><code>[google+events timezone="America/Los_Angeles"]</code></li>';
  $output .= '<li><code>[google+events countdown="true"]</code></li>';
  $output .= '</ul>';
  $output .= '<p><strong>' . __('Attributes', 'yakadanda-google-hangout-events') . '</strong></p>';
  $output .= '<table><tbody>';
  $output .= sprintf(__('<tr><td style="vertical-align: top;">type</td><td style="vertical-align: top;">=</td><td><span>all</span>, <span>normal</span>, or <span>hangout</span>, by default type is <span>all</span></td></tr>', 'yakadanda-google-hangout-events'));
  $output .= sprintf(__('<tr><td style="vertical-align: top;">src</td><td style="vertical-align: top;">=</td><td><span>all</span>, <span>gcal</span> (event from calendar), or <span>gplus</span> (event from google+), by default source is <span>all</span></td></tr>', 'yakadanda-google-hangout-events'));
  $output .= sprintf(__('<tr><td style="vertical-align: top;">limit</td><td style="vertical-align: top;">=</td><td>number of events to display (maximum is <span>20</span>)</td></tr>', 'yakadanda-google-hangout-events'));
  $output .= sprintf(__('<tr><td style="vertical-align: top;">past</td><td style="vertical-align: top;">=</td><td>number of months to display past events in <span>X</span> months ago, by default past is false</td></tr>', 'yakadanda-google-hangout-events'));
  $output .= sprintf(__('<tr><td style="vertical-align: top;">author</td><td style="vertical-align: top;">=</td><td><span>self</span>, <span>other</span>, or <span>all</span>, by default author is <span>all</span></td></tr>', 'yakadanda-google-hangout-events'));

  $url = 'https://plus.google.com/u/0/events/csnlc77gi4v519jom5gb28217so';
  $output .= sprintf(__('<tr><td style="vertical-align: top;">id</td><td style="vertical-align: top;">=</td><td>Event identifier (string). Single Event Example: <a href="%s" target="_blank">https://plus.google.com/u/0/events/c<u>snlc77gi4v519jom5gb28217so</u></a> To create a single event you would place in shortcode <code>[google+events id="snlc77gi4v519jom5gb28217so"]</code></td></tr>', 'yakadanda-google-hangout-events'), $url);

  $output .= sprintf(__('<tr><td style="vertical-align: top;">filter_out</td><td style="vertical-align: top;">=</td><td>Filter out certain events by event identifiers, seperated by comma</td></tr>', 'yakadanda-google-hangout-events'));
  $output .= sprintf(__('<tr><td style="vertical-align: top;">search</td><td style="vertical-align: top;">=</td><td>Text search terms (string) to display events that match these terms in any field, except for extended properties</td></tr>', 'yakadanda-google-hangout-events'));
  $output .= sprintf(__('<tr><td style="vertical-align: top;">attendees</td><td style="vertical-align: top;">=</td><td>Events can have attendees, the value can be <span>show</span>, <span>show_all</span>, or <span>hide</span>, the default value for attendees attribute is <span>hide</span></td></tr>', 'yakadanda-google-hangout-events'));

  $url = 'http://www.php.net/manual/en/timezones.php';
  $output .= sprintf(__('<tr><td style="vertical-align: top;">timezone</td><td style="vertical-align: top;">=</td><td>Time zone used in the response, optional. Default is time zone based on location (hangout event not have location) if not have location it will use google account/calendar time zone. Supported time zones at <a href="%s" target="_blank">http://www.php.net/manual/en/timezones.php</a> (string)</td></tr>', 'yakadanda-google-hangout-events'), $url);

  $output .= sprintf(__('<tr><td style="vertical-align: top;">countdown</td><td style="vertical-align: top;">=</td><td><span>true</span>, or <span>false</span>, by default countdown is <span>false</span></td></tr>', 'yakadanda-google-hangout-events'));

  $output .= '<tbody></table>';
  $output .= '</div>';

  return $output;
}

function googleplushangoutevent_section_embedded_posts() {
  $output = '<div class="google-web-starter-kit">';

  $output .= sprintf(__('<h1>Adding the embedded posts</h1>', 'yakadanda-google-hangout-events'));

  $href = 'plus.google.com';
  $src = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/gplus-post-menu-icon.png';
  $output .= sprintf(__('<p>Locate the post that you want to embed on <a href="%s" target="_blank">Google+</a> and click the <img src="%s" title="A downward pointing arrow that indicates the menu" alt="A downward pointing arrow that indicates the menu"/> menu icon and choose Embed post.</p>', 'yakadanda-google-hangout-events'), $href, $src);

  $output .= '<p><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-embedded-posts-1.png"/></p>';

  $embed_code = htmlentities('<div class="g-post" data-href="https://plus.google.com/116442957294662581658/posts/9Mu57w1iBFj"></div>');
  $output .= sprintf(__('<p>Copy the tag <code>%s</code> to post or page.</p>', 'yakadanda-google-hangout-events'), $embed_code);

  $output .= '<p><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/img/manual-embedded-posts-2.png"/></p>';

  $href = 'https://developers.google.com/+/web/embedded-post/';
  $output .= sprintf(__('<p>For more detail you can look at <a href="%s" target="_blank">https://developers.google.com/+/web/embedded-post/</a>.</p>', 'yakadanda-google-hangout-events'), $href);
  
  $output .= '</div>';
  
  return $output;
}

// echo font theme
function googleplushangoutevent_font_themes($id, $data) {
  include dirname(__FILE__) . '/google-fonts.php';
  $google_fonts = json_decode( $fonts );
  
  $defaultfonts = array('Arial', 'Arial Black', 'Book Antiqua', 'Courier New', 'Georgia', 'Helvetica', 'Impact', 'Lucida Console', 'Lucida Sans Unicode', 'Palatino Linotype', 'Times New Roman', 'Tahoma', 'Verdana');
  $webfonts = array();
  
  foreach ( $google_fonts->items as $font) {
    $webfonts[] = $font->family;
  }
  
  $the_fonts = array_merge($defaultfonts, $webfonts);
  sort ($the_fonts);
  
  ?>
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <?php foreach ($the_fonts as $the_font): ?>
        <option value="<?php echo $the_font; ?>"<?php echo ($the_font == $data) ? ' selected' : null; ?>><?php echo $the_font; ?>&nbsp;</option>
      <?php endforeach; ?>
    </select>
  <?php
}

// echo font size
function googleplushangoutevent_font_sizes($id, $data) {
  $sizes = array(8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72)
  ?>
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <?php foreach($sizes as $size): ?>
        <option value="<?php echo $size; ?>"<?php echo ($size == $data) ? ' selected' : null; ?>><?php echo $size; ?>&nbsp;</option>
      <?php endforeach; ?>
    </select>
  <?php
}

// echo font style
function googleplushangoutevent_font_styles($id, $data) {
  $styles = array('normal', 'bold', 'italic');
  ?>
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <?php foreach($styles as $style): ?>
        <option value="<?php echo $style; ?>"<?php echo ($style == $data) ? ' selected' : null; ?>><?php echo ucfirst($style); ?>&nbsp;</option>
      <?php endforeach; ?>
    </select>
  <?php
}

function googleplushangoutevent_google_fonts() {
  $data = (array) get_option('yakadanda_googleplus_hangout_event_options');
  
  $data['title_theme'] = isset($data['title_theme']) ? $data['title_theme'] : null;
  $data['date_theme'] = isset($data['date_theme']) ? $data['date_theme'] : null;
  $data['detail_theme'] = isset($data['detail_theme']) ? $data['detail_theme'] : null;
  $data['countdown_theme'] = isset($data['countdown_theme']) ? $data['countdown_theme'] : null;
  
  $fonts = array(
      $data['title_theme'],
      $data['date_theme'],
      $data['detail_theme'],
      $data['countdown_theme']
    );
  
  $unique_fonts = array_unique($fonts);
  $defaultfonts = array('Arial', 'Arial Black', 'Book Antiqua', 'Courier New', 'Georgia', 'Helvetica', 'Impact', 'Lucida Console', 'Lucida Sans Unicode', 'Palatino Linotype', 'Times New Roman', 'Tahoma', 'Verdana');
  
  $google_fonts = array_diff($unique_fonts, $defaultfonts);
  
  $output = null;
  
  if ($google_fonts) {
    sort($google_fonts);
  
    $i = 1;
    $j = count($google_fonts);
    
    foreach ($google_fonts as $google_font) {

      // Change whitespace to +
      $string = preg_replace("/[\s]/", "+", $google_font);

      $output .= $string;
      if ($i<$j) {
        $output .= '|';
        ++$i;
      }
    }
  }
  
  return $output;
}

if (version_compare(get_option('yakadanda_googleplus_hangout_event_ignore_notice'), GPLUS_HANGOUT_EVENTS_VER, '<')) add_action('admin_notices', 'googleplushangoutevent_admin_notice');
function googleplushangoutevent_admin_notice() {
  $url = basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING'];
  
  if ( ($url == 'admin.php?page=googleplushangoutevent-settings') ) {
    ?>
      <div class="updated googleplushangoutevent-notice">
        <p><a id="googleplushangoutevent-dismiss" href="#"><?php _e('Close', 'yakadanda-google-hangout-events'); ?></a></p>
        <?php
          $authorized_redirect_uri = admin_url('admin.php?page=googleplushangoutevent-settings');
          $url = 'https://cloud.google.com/console';
          echo sprintf(__('<p>Since 0.2.6, Authorized redirect URI changed to <code>%s</code>. You can change your Authorized redirect URI at <a href="%s" target="_blank">Google Cloud Console</a>. Ignore this notice if this the first time you\'re using this plugin. Thank you.</p>', 'yakadanda-google-hangout-events'), $authorized_redirect_uri, $url);
        ?>
      </div>
    <?php
  }
}

add_action('wp_ajax_googleplushangoutevent_dismiss', 'googleplushangoutevent_dismiss_callback');
function googleplushangoutevent_dismiss_callback() {
  update_option('yakadanda_googleplus_hangout_event_ignore_notice', GPLUS_HANGOUT_EVENTS_VER);
  die();
}

add_action('wp_ajax_googleplushangoutevent_logout', 'googleplushangoutevent_logout_callback');
function googleplushangoutevent_logout_callback() {
  // revoke on servers
  googleplushangoutevent_revoke_token(get_option('yakadanda_googleplus_hangout_event_access_token'));
  update_option( 'yakadanda_googleplus_hangout_event_access_token', null );
  
  $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => 'Disconnected.'));
  setcookie('googleplushangoutevent_message', $message, time()+1, '/');  
  
  die();
}

function googleplushangoutevent_revoke_token($token) {
  // load google library
  googleplushangoutevent_google_lib();
  
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  $client = new Google_Client();
  $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
  
  // Visit https://code.google.com/apis/console?api=calendar to generate your
  // client id, client secret, and to register your redirect uri.
  $client->setClientId( $data['client_id'] );
  $client->setClientSecret( $data['client_secret'] );
  $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
  
  $scopes = array('https://www.googleapis.com/auth/calendar');
  
  $client->setScopes( $scopes );
  $client->setDeveloperKey( $data['api_key'] );
  
  $client->revokeToken($token);
}

add_action('wp_ajax_googleplushangoutevent_restore_settings', 'googleplushangoutevent_restore_settings_callback');
function googleplushangoutevent_restore_settings_callback() {
  $token = get_option('yakadanda_googleplus_hangout_event_access_token');
  if ($token) {
    googleplushangoutevent_revoke_token($token);
    update_option('yakadanda_googleplus_hangout_event_access_token', null);
  }
  
  $action = update_option('yakadanda_googleplus_hangout_event_options', null);
  if ($action) {
    $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => __('Settings restored to default settings.', 'yakadanda-google-hangout-events')));
    setcookie('googleplushangoutevent_message', $message, time()+1, '/');
  }
  echo admin_url('admin.php?page=googleplushangoutevent-settings');
  die();
}

function googleplushangoutevent_response( $months = null, $event_id = null, $search = null, $timezone = null ) {
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  $output = array();
  if ($data) {
    googleplushangoutevent_google_lib();
    
    $client = new Google_Client();
    $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
    
    // Visit https://code.google.com/apis/console?api=calendar to generate your
    // client id, client secret, and to register your redirect uri.
    $client->setClientId( $data['client_id'] );
    $client->setClientSecret( $data['client_secret'] );
    $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
    
    $scopes = array('https://www.googleapis.com/auth/calendar');
    
    $client->setScopes( $scopes );
    $client->setDeveloperKey( $data['api_key'] );
    
    $token = get_option('yakadanda_googleplus_hangout_event_access_token');
    
    if ($token) {
      $client->setAccessToken($token);

      // http://stackoverflow.com/questions/11908420/trying-to-get-a-list-of-events-from-a-calendar-using-php
      //$client->setUseObjects(true);

      $service = new Google_Service_Calendar($client);

      $calendar_list = googleplushangoutevent_calendar_list( $service );

      // the date is today
      $timeMin = date('c');

      $args = array(
        'maxResults' => 20,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => $timeMin,
        'q' => $search,
        'timeZone' => $timezone
      );

      // Past Events
      if ( $months ) {
        // the today date minus by months
        $timeMin = date('c', strtotime("-" . $months . " month", strtotime($timeMin)));

        $args = array(
          'maxResults' => 20,
          'orderBy' => 'startTime',
          'singleEvents' => true,
          'timeMin' => $timeMin,
          'timeMax' => date('c'),
          'q' => $search,
          'timeZone' => $timezone
        );
      }

      if ( $event_id ) {
        // Events get
        $event = (array) $service->events->get( $data['calendar_id'], $event_id );

        $calendar = $service->calendars->get('primary');

        $timezonelocation = null;
        if ( isset($event['location']) ) {
          $lat_lng = googleplushangoutevent_google_geocoding($event['location']);

          if ( $lat_lng ) {
            $time = isset( $event["\0*\0modelData"]['start']['dateTime'] ) ? $event["\0*\0modelData"]['start']['dateTime'] : $event["\0*\0modelData"]['start']['date'];

            $timezonelocation = googleplushangoutevent_location_timezone( $lat_lng, $time );
          }
        }

        $the_event = array_merge( (array) $event, array( 'timeZoneCalendar' => $calendar['timeZone'] ) );

        if ( $timezonelocation ) $the_event = array_merge( (array) $the_event, array( 'timeZoneLocation' => $timezonelocation ) );

        if ( $timezone ) $the_event = array_merge( (array) $the_event, array( 'timeZoneRequest' => $timezone ) );

        $output = $the_event;
      } else {
        // Events list
        //$events = $service->events->listEvents( $data['calendar_id'], $args );

        foreach ( $calendar_list as $calendar ) {
          $events = $service->events->listEvents( $calendar['id'], $args );

          if ( isset($events['error']['code']) ) {
            $the_events = $events;
          } else {
            $the_events = array();
            foreach ( $events['items'] as $event ) {
              $timezonelocation = null;
              
              $event = (array) $event;
              
              if ( isset($event['location']) ) {
                $lat_lng = googleplushangoutevent_google_geocoding($event['location']);

                if ( $lat_lng ) {
                  $time = isset( $event["\0*\0modelData"]['start']['dateTime'] ) ? $event["\0*\0modelData"]['start']['dateTime'] : $event["\0*\0modelData"]['start']['date'];

                  $timezonelocation = googleplushangoutevent_location_timezone( $lat_lng, $time );
                }
              }

              $the_event = array_merge( $event, array( 'timeZoneCalendar' => $calendar['timeZone'] ) );

              if ( $timezonelocation ) $the_event = array_merge( (array) $the_event, array( 'timeZoneLocation' => $timezonelocation ) );

              if ( $timezone ) $the_event = array_merge( (array) $the_event, array( 'timeZoneRequest' => $timezone ) );

              $the_events[] = $the_event;
            }
          }

          $the_events = array_filter($the_events);
          if (!empty($the_events)) {
            $output = array_merge((array) $output, (array) $the_events);
          }

        }

        // Remove duplicate
        if ($output) {
          foreach ($output as $k => $v) {
            $result[$v['id']] = $v;
          }
          // Reset key
          $output = array_values($result);
        }
      }
    }
  }
  
  return $output;
}

function googleplushangoutevent_response_admin() {
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  $output = array();
  
  if ($data) {
    googleplushangoutevent_google_lib();
    
    $client = new Google_Client();
    $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
    
    // Visit https://code.google.com/apis/console?api=calendar to generate your
    // client id, client secret, and to register your redirect uri.
    $client->setClientId( $data['client_id'] );
    $client->setClientSecret( $data['client_secret'] );
    $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
    
    $scopes = array('https://www.googleapis.com/auth/calendar');
    
    $client->setScopes( $scopes );
    $client->setDeveloperKey( $data['api_key'] );
    
    $token = get_option('yakadanda_googleplus_hangout_event_access_token');
    
    if ($token) {
      $client->setAccessToken($token);
      
      // http://stackoverflow.com/questions/11908420/trying-to-get-a-list-of-events-from-a-calendar-using-php
      //$client->setUseObjects(true);
      
      $service = new Google_Service_Calendar($client);
      
      $calendar_list = googleplushangoutevent_calendar_list( $service );
      
      $args = array(
        'orderBy' => 'startTime',
        'singleEvents' => true,
      );
      
      foreach ( $calendar_list as $calendar ) {
        $events = $service->events->listEvents( $calendar['id'], $args );

        if ( isset($events['error']['code']) ) {
          $the_events = $events;
        } else {
          $the_events = array();
          foreach ( $events['items'] as $event ) {
            $filter = googleplushangoutevent_src_filter('gplus', $event['htmlLink']);
            
            if ($filter) {
              $timezonelocation = null;
              $event = (array) $event;
              if ( isset($event['location']) ) {
                $lat_lng = googleplushangoutevent_google_geocoding($event['location']);

                if ( $lat_lng ) {
                  $time = isset( $event["\0*\0modelData"]['start']['dateTime'] ) ? $event["\0*\0modelData"]['start']['dateTime'] : $event["\0*\0modelData"]['start']['date'];

                  $timezonelocation = googleplushangoutevent_location_timezone( $lat_lng, $time );
                }
              }

              $the_event = array_merge( $event, array( 'timeZoneCalendar' => $calendar['timeZone'] ) );

              if ( $timezonelocation ) $the_event = array_merge( (array) $the_event, array( 'timeZoneLocation' => $timezonelocation ) );

              $the_events[] = $the_event;
            }
          }
        }
        
        $the_events = array_filter($the_events);
        if (!empty($the_events)) {
          $output = array_merge((array) $output, (array) $the_events);
        }
      }
      
      // Remove duplicate
      if ($output) {
        foreach ($output as $k => $v) {
          $result[$v['id']] = $v;
        }
        // Reset key
        $output = array_values($result);
      }
    }
  }
  
  return $output;
}

function googleplushangoutevent_get_settings() {
  $default = array(
      'calendar_id' => null,
      'api_key' => null,
      'client_id' => null,
      'client_secret' => null,
      'widget_border' => '#D2D2D2',
      'widget_background' => '#FEFEFE',
      'title_color' => '#444444',
      'title_theme' => 'Arial',
      'title_size' => 14,
      'title_style' => 'bold',
      'date_color' => '#D64337',
      'date_theme' => 'Arial',
      'date_size' => 12,
      'date_style' => 'normal',
      'detail_color' => '#5F5F5F',
      'detail_theme' => 'Arial',
      'detail_size' => 12,
      'detail_style' => 'normal',
      'icon_border' => '#D2D2D2',
      'icon_background' => '#FFFFFF',
      'icon_hover' => '#D64337',
      'icon_color' => '#3366CC',
      'icon_theme' => 'Arial',
      'icon_size' => 12,
      'icon_style' => 'normal',
      'countdown_background' => '#3366CC',
      'countdown_color' => '#FFFFFF',
      'countdown_theme' => 'Arial',
      'countdown_size' => 11,
      'countdown_style' => 'normal',
      'event_button_background' => '#D64337',
      'event_button_hover' => '#C03C34',
      'event_button_color' => '#FFFFFF',
      'event_button_theme' => 'Arial',
      'event_button_size' => 14,
      'event_button_style' => 'normal'
    );
  $settings = wp_parse_args(get_option('yakadanda_googleplus_hangout_event_options'), $default);
  
  return $settings;
}

function googleplushangoutevent_delete_event($evenId) {
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  if ($data) {
    googleplushangoutevent_google_lib();
    
    $client = new Google_Client();
    $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
    
    // Visit https://code.google.com/apis/console?api=calendar to generate your
    // client id, client secret, and to register your redirect uri.
    $client->setClientId( $data['client_id'] );
    $client->setClientSecret( $data['client_secret'] );
    $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
    
    $scopes = array('https://www.googleapis.com/auth/calendar');
    
    $client->setScopes( $scopes );
    $client->setDeveloperKey( $data['api_key'] );
    
    $token = get_option('yakadanda_googleplus_hangout_event_access_token');
    
    if ($token) {
      $client->setAccessToken($token);
      
      // http://stackoverflow.com/questions/11908420/trying-to-get-a-list-of-events-from-a-calendar-using-php
      //$client->setUseObjects(true);
      
      $service = new Google_Service_Calendar($client);
      $service->events->delete('primary', $evenId);
    }
  }
}

function googleplushangoutevent_extend_event($evenId) {
  $data = get_option('yakadanda_googleplus_hangout_event_options');
  
  $event = null;
  
  if ($data) {
    googleplushangoutevent_google_lib();
    
    $client = new Google_Client();
    $client->setApplicationName("Yakadanda GooglePlus Hangout Event");
    
    // Visit https://code.google.com/apis/console?api=calendar to generate your
    // client id, client secret, and to register your redirect uri.
    $client->setClientId( $data['client_id'] );
    $client->setClientSecret( $data['client_secret'] );
    $client->setRedirectUri( admin_url('admin.php?page=googleplushangoutevent-settings') );
    
    $scopes = array('https://www.googleapis.com/auth/calendar');
    
    $client->setScopes( $scopes );
    $client->setDeveloperKey( $data['api_key'] );
    
    $token = get_option('yakadanda_googleplus_hangout_event_access_token');
    
    if ($token) {
      $client->setAccessToken($token);
      
      // http://stackoverflow.com/questions/11908420/trying-to-get-a-list-of-events-from-a-calendar-using-php
      //$client->setUseObjects(true);
      
      $service = new Google_Service_Calendar($client);
      $event = (array)$service->events->get('primary', $evenId);
      
      $timezonelocation = null;
      if ( isset($event['location']) ) {
        $lat_lng = googleplushangoutevent_google_geocoding($event['location']);

        if ( $lat_lng ) {
          $time = isset( $event["\0*\0modelData"]['start']['dateTime'] ) ? $event["\0*\0modelData"]['start']['dateTime'] : $event["\0*\0modelData"]['start']['date'];

          $timezonelocation = googleplushangoutevent_location_timezone( $lat_lng, $time );
        }
      }
      
      $calendar = $service->calendars->get('primary');
      
      $event = array_merge( (array)$event, array('timeZoneCalendar' => $calendar['timeZone']) );
      
      if ($timezonelocation) {
        $event = array_merge( (array)$event, array('timeZoneLocation' => $timezonelocation) );
      }
    }
  }
  
  return $event;
}

function googleplushangoutevent_bg_cl($hex) {
  list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
  $output = 'background-color: rgb(' . $r . ',' . $g . ',' . $b . ')';

  return $output;
}
