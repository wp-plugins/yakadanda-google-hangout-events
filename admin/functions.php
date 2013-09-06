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
      'content' => googleplushangoutevent_section_setup(),
  ));
  
  $screen->add_help_tab(array(
      'id' => 'googleplushangoutevent-shortcode',
      'title' => __('Shortcode'),
      'content' => googleplushangoutevent_section_shortcode(),
  ));
}

function googleplushangoutevent_section_setup() {
  $output = '<h1>How to get your Google Api Key, Client ID, and Client Secret</h1>';
  $output .= '<ol>';
  $output .= '<li>Go to <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a></li>';
  $output .= '<li>In selectbox click create to create project<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-1.png"/></li>';
  $output .= '<li>Enter the name of your project, e.g. <em>Yakadanda Google+ Hangout Events</em></li>';
  $output .= '<li>On Services menu of your project, turn on calendar api<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-2.png"/><br/><br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-3.png"/></li>';
  $output .= '<li>On API Access menu of your project, create an OAuth 2.0 client ID<br/><img src="' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/images/manual-4.png"/></li>';
  $output .= '<li>Fill Branding Information form and click next.</li>';
  $output .= '<li>Client ID Settings form:';
  $output .= '<dl>';
  $output .= '<dt><strong>Application type</strong></dt>';
  $output .= '<dd><em>Web application</em></dd>';
  $output .= '<dt><strong>Your site or hostname</strong></dt>';
  $output .= '<dd>Change the selectbox to "<em>http://</em>"<br/>';
  $output .= 'Copy and paste this url <em>' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/oauth2callback.php</em> to the textbox.</dd>';
  $output .= '<dt><strong>Redirect URI</strong></dt>';
  $output .= '<dd>It will automatically be filled with "<em>' . GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/oauth2callback.php</em>" after paste and click outside the textbox.</dd>';
  $output .= '</dl>';
  $output .= '<strong>Finally click Create client ID button.</strong>';
  $output .= '</li>';
  $output .= '<li>Now you have an Api Key, Client ID, and Client Secret.</li>';
  $output .= '</ol>';
      
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
  $output .= '</ul>';
  $output .= '<p><strong>Attributes</strong></p>';
  $output .= '<table class="sc_key"><tbody>';
  $output .= '<tr><td>type</td><td>=</td><td>all, normal, or hangout, by default type is all</td></tr>';
  $output .= '<tr><td>limit</td><td>=</td><td>number of events to display (maximum 20)</td></tr>';
  $output .= '<tr><td>past</td><td>=</td><td>number of months to display past events in X months ago, by default past is false</td></tr>';
  $output .= '<tr><td>author</td><td>=</td><td>self, or all, by default author is all</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">id</td><td style="vertical-align: top;">=</td><td>Event identifier (string). Single Event Example: <a href="https://plus.google.com/u/0/events/csnlc77gi4v519jom5gb28217so" target="_blank">https://plus.google.com/u/0/events/c<u>snlc77gi4v519jom5gb28217so</u></a> To create a single event you would place in shortcode <span>[google+events id="snlc77gi4v519jom5gb28217so"]</span></td></tr>';
  $output .= '<tr><td>filter_out</td><td>=</td><td>Filter out certain events by event identifiers, seperated by comma</td></tr>';
  $output .= '<tr><td>search</td><td>=</td><td>Text search terms (string) to display events that match these terms in any field, except for extended properties</td></tr>';
  $output .= '<tr><td>attendees</td><td>=</td><td>Events can have attendees, the value can be \'show\', \'show_all\', or \'hide\', the default value for attendees attribute is \'hide\'</td></tr>';
  $output .= '<tbody></table>';
  
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
