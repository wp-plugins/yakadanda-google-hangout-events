<div id="google-hangout-event" class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2>Google+ Hangout Events</h2>
  <?php if ($response): ?>
    <div id="message" class="<?php echo $response['class']; ?>">
      <p><?php echo $response['msg']; ?></p>
    </div>
  <?php endif; ?>
  <form method="post" action="<?php echo GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/admin/posteddata.php'; ?>">
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><label for="calendar_id"><?php _e('Calendar ID') ?></label></th>
        <td><input name="calendar_id" type="text" id="calendar_id" value="<?php echo $data['calendar_id']; ?>" class="regular-text" />&nbsp;
        <?php if ( get_option('yakadanda_googleplus_hangout_event_access_token') ): ?>
          <strong style="color: green;">Connected.</strong>
        <?php else: ?>
          <strong style="color: red;">Not Connected.</strong>
        <?php endif; ?>
        <p class="description"><?php _e('Calendar identifier, e.g. your_name@gmail.com') ?></p></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="api_key"><?php _e('API Key') ?></label></th>
        <td><input name="api_key" type="text" id="api_key" value="<?php echo $data['api_key']; ?>" class="regular-text" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="client_id"><?php _e('Client ID') ?></label></th>
        <td><input name="client_id" type="text" id="client_id" value="<?php echo $data['client_id']; ?>" class="regular-text" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="client_secret"><?php _e('Client Secret') ?></label></th>
        <td><input name="client_secret" type="text" id="client_secret" value="<?php echo $data['client_secret']; ?>" class="regular-text" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="widget_border"><?php _e('Widget Border') ?></label></th>
        <td><input name="widget_border" type="text" id="event_border" value="<?php echo ($data['widget_border']) ? $data['widget_border'] : '#D2D2D2'; ?>" class="regular-text colorwheel" /></td>
        <td rowspan="12"><div id="picker"></div></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="widget_background"><?php _e('Widget Background') ?></label></th>
        <td><input name="widget_background" type="text" id="widget_background" value="<?php echo ($data['widget_background']) ? $data['widget_background'] : '#FEFEFE'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="title_color"><?php _e('Title') ?></label></th>
        <td>
          <input name="title_color" type="text" id="title_color" value="<?php echo ($data['title_color']) ? $data['title_color'] : '#444444'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'title_theme', $data['title_theme'] );
            googleplushangoutevent_font_sizes( 'title_size', $data['title_size'] );
            googleplushangoutevent_font_styles( 'title_style', $data['title_style'] );
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="date_color"><?php _e('Date') ?></label></th>
        <td>
          <input name="date_color" type="text" id="date_color" value="<?php echo ($data['date_color']) ? $data['date_color'] : '#D64337'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'date_theme', $data['date_theme'] );
            googleplushangoutevent_font_sizes( 'date_size', $data['date_size'] );
            googleplushangoutevent_font_styles( 'date_style', $data['date_style'] );
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="detail_color"><?php _e('Main Text') ?></label></th>
        <td>
          <input name="detail_color" type="text" id="detail_color" value="<?php echo ($data['detail_color']) ? $data['detail_color'] : '#5F5F5F'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'detail_theme', $data['detail_theme'] );
            googleplushangoutevent_font_sizes( 'detail_size', $data['detail_size'] );
            googleplushangoutevent_font_styles( 'detail_style', $data['detail_style'] );
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="icon_border"><?php _e('Buttons Border') ?></label></th>
        <td><input name="icon_border" type="text" id="icon_border" value="<?php echo ($data['icon_border']) ? $data['icon_border'] : '#D2D2D2'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="icon_background"><?php _e('Buttons Background') ?></label></th>
        <td><input name="icon_background" type="text" id="icon_background" value="<?php echo ($data['icon_background']) ? $data['icon_background'] : '#FFFFFF'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="icon_color"><?php _e('Buttons Text') ?></label></th>
        <td>
          <input name="icon_color" type="text" id="icon_color" value="<?php echo ($data['icon_color']) ? $data['icon_color'] : '#3366CC'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'icon_theme', $data['icon_theme']);
            googleplushangoutevent_font_sizes( 'icon_size', $data['icon_size']);
            googleplushangoutevent_font_styles( 'icon_style', $data['icon_style']);
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="countdown_background"><?php _e('Countdown Background') ?></label></th>
        <td><input name="countdown_background" type="text" id="countdown_background" value="<?php echo ($data['countdown_background']) ? $data['countdown_background'] : '#3366CC'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="countdown_color"><?php _e('Countdown Text') ?></label></th>
        <td>
          <input name="countdown_color" type="text" id="countdown_color" value="<?php echo ($data['countdown_color']) ? $data['countdown_color'] : '#FFFFFF'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'countdown_theme', $data['countdown_theme']);
            googleplushangoutevent_font_sizes( 'countdown_size', $data['countdown_size']);
            googleplushangoutevent_font_styles( 'countdown_style', $data['countdown_style']);
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="event_button_background"><?php _e('View Event Button Background') ?></label></th>
        <td><input name="event_button_background" type="text" id="event_button_background" value="<?php echo ($data['event_button_background']) ? $data['event_button_background'] : '#D64337'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="event_button_hover"><?php _e('View Event Button Hover') ?></label></th>
        <td><input name="event_button_hover" type="text" id="event_button_hover" value="<?php echo ($data['event_button_hover']) ? $data['event_button_hover'] : '#c03c34'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="event_button_color"><?php _e('View Event Button Text') ?></label></th>
        <td>
          <input name="event_button_color" type="text" id="event_button_color" value="<?php echo ($data['event_button_color']) ? $data['event_button_color'] : '#FFFFFF'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'event_button_theme', $data['event_button_theme']);
            googleplushangoutevent_font_sizes( 'event_button_size', $data['event_button_size']);
            googleplushangoutevent_font_styles( 'event_button_style', $data['event_button_style']);
          ?>
        </td>
      </tr>

    </table>
    <p class="submit">
      <?php if ( get_option('yakadanda_googleplus_hangout_event_access_token') ): ?>
        <input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">&nbsp;
        <a href="<?php echo GPLUS_HANGOUT_EVENTS_PLUGIN_URL . '/admin/posteddata.php?logout=1'; ?>" class="button-primary">Logout</a>
      <?php else: ?>
        <input id="submit" class="button-primary" type="submit" value="Save and Connect" name="submit">
      <?php endif; ?>
    </p>
    <p class="description"><?php _e('Visit <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a> to generate your api key, client id, client secret, and to register your redirect uri, or <a href="'. $manual_url .'" target="_blank">follow these instructions</a>.') ?></p>
    <h3>Shortcode Examples:</h3> 
    <ul class="sc_examples">
	    <li>[google+events]</li>
	    <li>[google+events type="hangout"]</li>
      <li>[google+events limit="3"]</li>
      <li>[google+events past="2"]</li>
      <li>[google+events author="all"]</li>
	    <li>[google+events limit="5" type="normal" past="1" author="all"]</li>
      <li>[google+events id="xxxxxxxxxxxxxxxxxxxxxxxxxx"]</li>
      <li>[google+events filter_out="xxxxxxxxxxxxxxxxxxxxxxxxxx,xxxxxxxxxxxxxxxxxxxxxxxxxx"]</li>
      <li>[google+events search="free text search terms"]</li>
      <li>[google+events attendees="show"]</li>
    </ul>
    <h4>Key</h4>
    <ul class="sc_key">
	    <li>type = all, normal, or hangout, by default type is all</li>
	    <li>limit = number of events to display (maximum 20)</li>
      <li>past = number of months to display past events in X months ago, by default past is false</li>
      <li>author = self, or all, by default author is all</li>
      <li>id = Event identifier (string). Single Event Example: https://plus.google.com/u/0/events/c<u>snlc77gi4v519jom5gb28217so</u>
        <br/>To create a single event you would place in shortcode [google+events id="snlc77gi4v519jom5gb28217so"]</li>
      <li>filter_out = Filter out certain events by event identifiers, seperated by comma</li>
      <li>search = Text search terms (string) to display events that match these terms in any field, except for extended properties</li>
      <li>attendees = Events can have attendees, the value can be 'show', 'show_all', or 'hide', the default value for attendees attribute is 'hide'</li>
    </ul>
  </form>
</div>
