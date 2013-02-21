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
  
  add_submenu_page( 'options-general.php', 'Google+ Hangout Events', 'Google+ Hangout Events', 'manage_options', 'googleplus-hangout-events', 'googleplushangoutevent_page' );
}

function googleplushangoutevent_page() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
  
  $data = get_option( 'yakadanda_googleplus_hangout_event_options' );
  $manual_url = GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/manual.php';
  
  include('page.php');
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
  $data = get_option('yakadanda_googleplus_hangout_event_options');
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
