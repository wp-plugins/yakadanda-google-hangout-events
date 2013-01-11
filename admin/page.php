<div id="google-hangout-event" class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2>Google+ Hangout Events</h2>
  
  <form method="post" action="<?php echo GPLUS_HANGOUT_EVENT_URL . '/admin/posteddata.php'; ?>">
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
        <th scope="row"><label for="title_color"><?php _e('Title') ?></label></th>
        <td>
          <input name="title_color" type="text" id="title_color" value="<?php echo ($data['title_color']) ? $data['title_color'] : '#555555'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'title_theme', $data['title_theme'] );
            googleplushangoutevent_font_sizes( 'title_size', $data['title_size'] );
            googleplushangoutevent_font_styles( 'title_style', $data['title_style'] );
          ?>
        </td>
        <td rowspan="6"><div id="picker"></div></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="date_color"><?php _e('Date') ?></label></th>
        <td>
          <input name="date_color" type="text" id="date_color" value="<?php echo ($data['date_color']) ? $data['date_color'] : '#DE4931'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'date_theme', $data['date_theme'] );
            googleplushangoutevent_font_sizes( 'date_size', $data['date_size'] );
            googleplushangoutevent_font_styles( 'date_style', $data['date_style'] );
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="detail_color"><?php _e('Detail') ?></label></th>
        <td>
          <input name="detail_color" type="text" id="detail_color" value="<?php echo ($data['detail_color']) ? $data['detail_color'] : '#555555'; ?>" class="regular-text colorwheel" />
          <?php
            googleplushangoutevent_font_themes( 'detail_theme', $data['detail_theme'] );
            googleplushangoutevent_font_sizes( 'detail_size', $data['detail_size'] );
            googleplushangoutevent_font_styles( 'detail_style', $data['detail_style'] );
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="event_background"><?php _e('Event Background') ?></label></th>
        <td><input name="event_background" type="text" id="event_background" value="<?php echo ($data['event_background']) ? $data['event_background'] : '#FFFFFF'; ?>" class="regular-text colorwheel" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="countdown_color"><?php _e('Countdown') ?></label></th>
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
        <th scope="row"><label for="countdown_background"><?php _e('Countdown Background') ?></label></th>
        <td><input name="countdown_background" type="text" id="countdown_background" value="<?php echo ($data['countdown_background']) ? $data['countdown_background'] : '#408CFD'; ?>" class="regular-text colorwheel" /></td>
      </tr>
    </table>
    <p class="submit">
      <?php if ( get_option('yakadanda_googleplus_hangout_event_access_token') ): ?>
        <input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">&nbsp;
        <a href="<?php echo GPLUS_HANGOUT_EVENT_URL . '/admin/posteddata.php?logout=1'; ?>" class="button-primary">Logout</a>
      <?php else: ?>
        <input id="submit" class="button-primary" type="submit" value="Save and Connect" name="submit">
      <?php endif; ?>
    </p>
      <p class="description"><?php _e('Visit <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a> to generate your api key, client id, client secret, and to register your redirect uri, or <a href="'. $manual_url .'" target="_blank">see these instructions</a> for the detail.') ?></p>
  </form>
</div>