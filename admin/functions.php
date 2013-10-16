<?php
/*
 * Functions for Google Hangout Event plugin in Admin area
 */

// Register menu page
add_action('admin_menu', 'googleplushangoutevent_register_menu_page');
function googleplushangoutevent_register_menu_page() {
  // Call stylesheets
  add_action( 'admin_enqueue_scripts', 'googleplushangoutevent_admin_enqueue_styles' );
  // Call javascripts
  add_action( 'admin_enqueue_scripts', 'googleplushangoutevent_admin_enqueue_scripts' );
  
  $settings_page = add_submenu_page( 'options-general.php', 'Google+ Hangout Events', 'Google+ Hangout Events', 'manage_options', 'googleplus-hangout-events', 'googleplushangoutevent_page' );
  
  add_action( 'load-' . $settings_page, 'googleplushangoutevent_admin_add_help_tab' );
}

function googleplushangoutevent_page() {
  if (!current_user_can('manage_options'))
    wp_die( __('You do not have sufficient permissions to access this page.') );
  
  $data = (array) get_option( 'yakadanda_googleplus_hangout_event_options' );
  
  $response = null;
  if (isset($_GET["calendar_id"])) {
    $response = array('class' => 'error', 'msg' => 'Please login as ' . $data['calendar_id']);
  }
  
  include('page.php');
}

function googleplushangoutevent_admin_add_help_tab() {
  $screen = get_current_screen();
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-setup',
      'title' => __('Setup'),
      'content' => googleplushangoutevent_section_setup()
  ));
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-shortcode',
      'title' => __('Shortcode'),
      'content' => googleplushangoutevent_section_shortcode()
  ));
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-embedded-posts',
      'title' => __('Google+ Embedded Posts'),
      'content' => googleplushangoutevent_section_embedded_posts()
  ));
}

function googleplushangoutevent_section_setup() {
  $output = '<h1>How to get your Google Api Key, Client ID, and Client Secret</h1>';
  $output .= '<div id="setup_tabs">';
  $output .= '<ul>';
  $output .= '<li><a href="#setup-tabs-1">Google APIs Console</a></li>';
  $output .= '<li><a href="#setup-tabs-2">Google Cloud Console</a></li>';
  $output .= '</ul>';
  $output .= '<div id="setup-tabs-1">';
  $output .= '<ol>';
  $output .= '<li>At <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a> create new project.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a1.png"/><br/>Enter your project name, e.g. <span>Yakadanda Google+ Hangout Events</span></li>';
  $output .= '<li>Turn on Calendar API service.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a2.png"/><br/><br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a3.png"/></li>';
  $output .= '<li>On API Access menu of your project, create an OAuth 2.0 client ID.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a4.png"/></li>';
  $output .= '<li>Fill Branding Information form as you want and then click <strong>Next</strong>.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a5.png"/></li>';
  $output .= '<li>Setup Client ID Settings form.<br/><br/>a. Choose <span>Web application</span> for Application type<br/>';
  $output .= 'b. Select <span>http://</span> for Your site or hostname<br/>';
  $output .= 'c. Click (more options) link<br/>';
  $output .= '<img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a6.png"/><br/><br/>';
  $output .= 'd. Fill Authorized Redirect URIs textarea with <span>' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/oauth2callback.php</span><br/>';
  $output .= 'e. Fill Authorized JavaScript Origins textarea with <span>' . home_url() . '</span><br/>';
  $output .= '<img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a7.png"/><br/><br/>';
  $output .= 'Finally, click <strong>Create client ID</strong> button to finish.';
  $output .= '</li>';
  $output .= '<li>Now you have API key, Client ID, and Client secret.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-a8.png"/></li>';
  $output .= '</ol>';
  $output .= '</div>';
  $output .= '<div id="setup-tabs-2">';
  $output .= '<ol>';
  $output .= '<li>At <a href="https://cloud.google.com/console" target="_blank">https://cloud.google.com/console</a> create new project.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b1.png"/></li>';
  $output .= '<li>Fill Project name textbox with your suitable information.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b2.png"/></li>';
  $output .= '<li>On Overview menu of your project, click APIs & auth menu.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b3.png"/></li>';
  $output .= '<li>Turn on Calendar API.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b4.png"/></li>';
  $output .= '<li>On Registered apps menu, please register new application by click REGISTER APP button.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b5.png"/></li>';
  $output .= '<li>Fill Name textbox, and choose <span>Web Application</span> as a Platform.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b6.png"/></li>';
  $output .= '<li>On your app web application click OAuth 2.0 Client ID for setup and to get Client ID and Client Secret. And click Server Key to get Api Key.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b7.png"/></li>';
  $output .= '<li>Setup OAuth 2.0 Client ID.<br/><br/>a. Fill WEB ORIGIN textbox with <span>' . home_url() . '</span><br/> b. And REDIRECT URI textbox with <span>' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/oauth2callback.php</span><br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b8.png"/></li>';
  $output .= '<li>Your API Key on Server Key.<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-b9.png"/></li>';
  $output .= '</ol>';
  $output .= '</div>';
  $output .= '</div>';
  
  return $output;
}

function googleplushangoutevent_section_shortcode() {
  $output = '<p><strong>Shortcode Examples</strong></p>';
  $output .= '<ul class="sc_examples">';
  $output .= '<li>[google+events]</li>';
  $output .= '<li>[google+events type="hangout"]</li>';
  $output .= '<li>[google+events limit="3"]</li>';
  $output .= '<li>[google+events past="2"]</li>';
  $output .= '<li>[google+events author="all"]</li>';
  $output .= '<li>[google+events limit="5" type="normal" past="1" author="all"]</li>';
  $output .= '<li>[google+events id="xxxxxxxxxxxxxxxxxxxxxxxxxx"]</li>';
  $output .= '<li>[google+events filter_out="xxxxxxxxxxxxxxxxxxxxxxxxxx,xxxxxxxxxxxxxxxxxxxxxxxxxx"]</li>';
  $output .= '<li>[google+events search="free text search terms"]</li>';
  $output .= '<li>[google+events attendees="show"]</li>';
  $output .= '<li>[google+events timezone="America/Los_Angeles"]</li>';
  $output .= '</ul>';
  $output .= '<p><strong>Attributes</strong></p>';
  $output .= '<table class="sc_key"><tbody>';
  $output .= '<tr><td style="vertical-align: top;">type</td><td style="vertical-align: top;">=</td><td><span>all</span>, <span>normal</span>, or <span>hangout</span>, by default type is <span>all</span></td></tr>';
  $output .= '<tr><td style="vertical-align: top;">limit</td><td style="vertical-align: top;">=</td><td>number of events to display (maximum is <span>20</span>)</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">past</td><td style="vertical-align: top;">=</td><td>number of months to display past events in <span>X</span> months ago, by default past is false</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">author</td><td style="vertical-align: top;">=</td><td><span>self</span>, or <span>all</span>, by default author is <span>all</span></td></tr>';
  $output .= '<tr><td style="vertical-align: top;">id</td><td style="vertical-align: top;">=</td><td>Event identifier (string). Single Event Example: <a href="https://plus.google.com/u/0/events/csnlc77gi4v519jom5gb28217so" target="_blank">https://plus.google.com/u/0/events/c<u>snlc77gi4v519jom5gb28217so</u></a> To create a single event you would place in shortcode <span>[google+events id="snlc77gi4v519jom5gb28217so"]</span></td></tr>';
  $output .= '<tr><td style="vertical-align: top;">filter_out</td><td style="vertical-align: top;">=</td><td>Filter out certain events by event identifiers, seperated by comma</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">search</td><td style="vertical-align: top;">=</td><td>Text search terms (string) to display events that match these terms in any field, except for extended properties</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">attendees</td><td style="vertical-align: top;">=</td><td>Events can have attendees, the value can be <span>show</span>, <span>show_all</span>, or <span>hide</span>, the default value for attendees attribute is <span>hide</span></td></tr>';
  $output .= '<tr><td style="vertical-align: top;">timezone</td><td style="vertical-align: top;">=</td><td>Time zone used in the response, optional. Default is time zone based on location (hangout event not have location) if not have location it will use google account/calendar time zone. Supported time zones at <a href="http://www.php.net/manual/en/timezones.php" target="_blank">http://www.php.net/manual/en/timezones.php</a> (string)</td></tr>';
  $output .= '<tbody></table>';
  
  return $output;
}

function googleplushangoutevent_section_embedded_posts() {
  $output = '<p><strong>Adding the embedded posts</strong></p>';
  $output .= '<p>Locate the post that you want to embed on <a href="plus.google.com" target="_blank">Google+</a> and click the <img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/gplus-post-menu-icon.png" title="A downward pointing arrow that indicates the menu" alt="A downward pointing arrow that indicates the menu"/> menu icon and choose Embed post.</p>';
  $output .= '<p><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-embedded-posts-1.png"/></p>';
  $output .= '<p>Copy the tag <span>' . htmlentities('<div class="g-post" data-href="https://plus.google.com/116442957294662581658/posts/9Mu57w1iBFj"></div>') . '</span> to post or page.</p>';
  $output .= '<p><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-embedded-posts-2.png"/></p>';
  
  $output .= '<p>For more detail you can look at <a href="https://developers.google.com/+/web/embedded-post/" target="_blank">https://developers.google.com/+/web/embedded-post/</a>.</p>';
  
  return $output;
}

// echo font theme
function googleplushangoutevent_font_themes( $id, $data = null ) {
  include dirname(__FILE__) . '/google-fonts.php';
  $google_fonts = json_decode( $fonts );
  
  $defaultfonts = array('Arial', 'Arial Black', 'Book Antiqua', 'Courier New', 'Georgia', 'Helvetica', 'Impact', 'Lucida Console', 'Lucida Sans Unicode', 'Palatino Linotype', 'Times New Roman', 'Tahoma', 'Verdana');
  $webfonts = array();
  
  foreach ( $google_fonts->items as $font) {
    $webfonts[] = $font->family;
  }
  
  $the_fonts = array_merge($defaultfonts, $webfonts);
  sort ($the_fonts);
  
  // set default data
  if ( ( $id == 'title_theme' ) && ( $data == null ) ) $data = 'Arial';
  if ( ( $id == 'date_theme' ) && ( $data == null ) ) $data = 'Arial';
  if ( ( $id == 'detail_theme' ) && ( $data == null ) ) $data = 'Arial';
  if ( ( $id == 'icon_theme' ) && ( $data == null ) ) $data = 'Arial';
  if ( ( $id == 'countdown_theme' ) && ( $data == null ) ) $data = 'Arial';
  if ( ( $id == 'event_button_theme' ) && ( $data == null ) ) $data = 'Arial';
  ?>
    <input name="hidden_<?php echo $id; ?>" type="hidden" id="hidden_<?php echo $id; ?>" value="<?php echo $data; ?>" />
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <?php foreach ($the_fonts as $the_font): ?>
        <option value="<?php echo $the_font; ?>"><?php echo $the_font; ?>&nbsp;</option>
      <?php endforeach; ?>
    </select>
  <?php
}

// echo font size
function googleplushangoutevent_font_sizes( $id, $data = null ) {
  // set default data
  if ( ( $id == 'title_size' ) && ( $data == null ) ) $data = 14;
  if ( ( $id == 'date_size' ) && ( $data == null ) ) $data = 12;
  if ( ( $id == 'detail_size' ) && ( $data == null ) ) $data = 12;
  if ( ( $id == 'icon_size' ) && ( $data == null ) ) $data = 12;
  if ( ( $id == 'countdown_size' ) && ( $data == null ) ) $data = 11;
  if ( ( $id == 'event_button_size' ) && ( $data == null ) ) $data = 14;
  ?>
    <input name="hidden_<?php echo $id; ?>" type="hidden" id="hidden_<?php echo $id; ?>" value="<?php echo $data; ?>" />
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <option value="8">8&nbsp;</option>
      <option value="9">9&nbsp;</option>
      <option value="10">10&nbsp;</option>
      <option value="11">11&nbsp;</option>
      <option value="12">12&nbsp;</option>
      <option value="14">14&nbsp;</option>
      <option value="16">16&nbsp;</option>
      <option value="18">18&nbsp;</option>
      <option value="20">20&nbsp;</option>
      <option value="22">22&nbsp;</option>
      <option value="24">24&nbsp;</option>
      <option value="26">26&nbsp;</option>
      <option value="28">28&nbsp;</option>
      <option value="36">36&nbsp;</option>
      <option value="48">48&nbsp;</option>
      <option value="72">72&nbsp;</option>
    </select>
  <?php
}

// echo font style
function googleplushangoutevent_font_styles( $id, $data = null ) {
  // set default data
  if ( ( $id == 'title_style' ) && ( $data == null ) ) $data = 'bold';
  ?>
    <input name="hidden_<?php echo $id; ?>" type="hidden" id="hidden_<?php echo $id; ?>" value="<?php echo $data; ?>" />
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <option value="normal">Normal&nbsp;</option>
      <option value="bold">Bold&nbsp;</option>
      <option value="italic">Italic&nbsp;</option>
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
