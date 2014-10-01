<div id="google-hangout-event-all-events" class="wrap ">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2><?php echo $title; ?></h2>
  <?php if ($message): ?>
    <div class="<?php echo $message['class']; ?>">
      <p><?php echo $message['msg']; ?></p>
    </div>
  <?php if (isset($message['cookie'])) setcookie('googleplushangoutevent_message', null, time()-1, '/'); endif; ?>
  <?php if (isset($_GET['action']) && ($_GET['action'] == 'extend') && isset($_GET['id'])): ?>
    <div class="google-web-starter-kit">
      <form method="post" action="">
        <?php if ($event): ?>
          <input type="hidden" name="extend_event" value="1" />
          <table class="form-table">
            <tr valign="top">
              <th scope="row"><label for="calendar_id"><?php _e('Event Title') ?></label></th>
              <td><?php _e('<a href="' . $event['htmlLink'] . '" title="' . $event['summary'] . '">' . $event['summary'] . '</a>'); ?></td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="calendar_id"><?php _e('Time') ?></label></th>
              <td>
                <?php
                  $start = isset($event["\0*\0modelData"]['start']['dateTime']) ? $event["\0*\0modelData"]['start']['dateTime'] : $event["\0*\0modelData"]['start']['date'];
                  $end = isset($event["\0*\0modelData"]['end']['dateTime']) ? $event["\0*\0modelData"]['end']['dateTime'] : $event["\0*\0modelData"]['end']['date'];
                  $timezone = isset($event['timeZoneLocation']) ? $event['timeZoneLocation'] : $event['timeZoneCalendar'];
                  _e(googleplushangoutevent_time($start, $end, $timezone,'shortcode'));
                ?>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="calendar_id"><?php _e('Location') ?></label></th>
              <td><?php _e(isset($event['location']) ? '<a href="http://maps.google.com/?q='. $event['location'] .'">' . $event['location'] . '</a>' : '<a href="' . $event['hangoutLink'] . '">Hangout</a>');?></td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="calendar_id"><?php _e('Description') ?></label></th>
              <td><?php _e(nl2br($event['description'])); ?></td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="calendar_id"><?php _e('Extend') ?></label></th>
              <td>
                <input type="text" name="image_location" value="<?php echo $image_src; ?>" class="regular-text"/>
                <input id="googleplushangoutevent-upload" type="button" class="button--upload" value="Upload Image"/>
                <p class="description">Enter the image location or upload an image from your computer and click Insert into Post button.</p>
                <?php if ($image_src): echo '<p><img src="' . $image_src . '" alt="' . $event['summary'] . '"></p>'; endif; ?>
              </td>
            </tr>
          </table>
          <p class="submit">
            <input id="submit" class="button--primary" type="submit" value="Save" name="submit">
          </p>
        <?php endif; ?>
      </form>
    </div>
  <?php else: ?>
    <div class="google-web-starter-kit google-table">
      <form id="events-filter" method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $listTable->display() ?>
      </form>
    </div>
  <?php endif; ?>
</div>
