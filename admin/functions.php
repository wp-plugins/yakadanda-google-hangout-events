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
  $manual_url = GPLUS_HANGOUT_EVENT_URL . '/manual.php';
  
  include('page.php');
}

// echo font theme
function googleplushangoutevent_font_themes( $id, $data = null ) {
  ?>
    <input name="hidden_<?php echo $id; ?>" type="hidden" id="hidden_<?php echo $id; ?>" value="<?php echo $data; ?>" />
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <option value="Arial">Arial&nbsp;</option>
      <option value="Arial Black">Arial Black&nbsp;</option>
      <option value="Book Antiqua">Book Antiqua&nbsp;</option>
      <option value="Courier New">Courier New&nbsp;</option>
      <option value="Georgia">Georgia&nbsp;</option>
      <option value="Helvetica">Helvetica&nbsp;</option>
      <option value="Impact">Impact&nbsp;</option>
      <option value="Lucida Console">Lucida Console&nbsp;</option>
      <option value="Lucida Sans Unicode">Lucida Sans Unicode&nbsp;</option>
      <option value="Palatino Linotype">Palatino Linotype&nbsp;</option>
      <option value="Times New Roman">Times New Roman&nbsp;</option>
      <option value="Tahoma">Tahoma&nbsp;</option>
      <option value="Verdana">Verdana&nbsp;</option>
    </select>
  <?php
}

// echo font size
function googleplushangoutevent_font_sizes( $id, $data = null ) {
  // set default data
  if ( ( $id == 'title_size' ) && ( $data == null ) ) $data = 12;
  if ( ( $id == 'date_size' ) && ( $data == null ) ) $data = 12;
  if ( ( $id == 'detail_size' ) && ( $data == null ) ) $data = 12;
  if ( ( $id == 'countdown_size' ) && ( $data == null ) ) $data = 16;
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
  if ( ( $id == 'countdown_style' ) && ( $data == null ) ) $data = 'bold';
  ?>
    <input name="hidden_<?php echo $id; ?>" type="hidden" id="hidden_<?php echo $id; ?>" value="<?php echo $data; ?>" />
    <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
      <option value="normal">Normal&nbsp;</option>
      <option value="bold">Bold&nbsp;</option>
      <option value="italic">Italic&nbsp;</option>
    </select>
  <?php
}
