jQuery(function($){
  /* backend */
  if ( $('body').find('#google-hangout-event-all-events').length === 1 ) {
    
  }
  if ( $('body').find('#google-hangout-event-settings').length === 1 ) {
    // Iris color picker
    $('.iris-color-picker').iris({
      palettes: ['#d2d2d2', '#fefefe', '#444444', '#d64337', '#5f5f5f', '#ffffff', '#3366cc', '#c03c34'],
      change: function(event, ui) {
        $(this).css( 'background-color', ui.color.toString());
      }
    });
    $(document).click(function (e) {
      if (!$(e.target).is(".iris-color-picker, .iris-picker, .iris-picker-inner")) {
        $('.iris-color-picker').iris('hide');
      }
    });
    $('.iris-color-picker').click(function (event) {
      $('.iris-color-picker').iris('hide');
      $(this).iris('show');
    });
    
    $('#googleplushangoutevent-help-tab').on('click', function(e) {
      if ($('#screen-meta').is(":hidden")) {
        $('#contextual-help-link').trigger('click');
      }
      $('#tab-link-googleplushangoutevent-setup a').trigger('click');
      $("html, body").animate({ scrollTop: $('#wpbody').offset().top }, 500);
      e.preventDefault();
    });
    
    $('#preference_tabs').tabs();
    
    // close notice
    $('#googleplushangoutevent-dismiss').click(function(e) {
      var data = {
          action: 'googleplushangoutevent_dismiss'
        };
      $.post(ajax_object.ajax_url, data, function(response) {
        $('.googleplushangoutevent-notice').remove();
      });
      
      e.preventDefault();
    });
    
    // logout
    $('#googleplushangoutevent-logout').click(function(e) {
      var data = {
        action: 'googleplushangoutevent_logout'
      };
      $.post(ajax_object.ajax_url, data, function(response) {
        location.reload();
      });
      e.preventDefault();
    });
    // reset settings
    $('#googleplushangoutevent-restore-settings').click(function(e){
      $('#googleplushangoutevent-dialog-confirm').dialog('open');
      e.preventDefault();
    });
    // Confirmation dialog
    $("#googleplushangoutevent-dialog-confirm").dialog({
      autoOpen: false,
      resizable: false,
      draggable: false,
      height: 200,
      width: 300,
      modal: true,
      buttons: {
        "OK": function() {
          $(this).dialog("close");
          var data = {
            action: 'googleplushangoutevent_restore_settings'
          };
          $.post(ajax_object.ajax_url, data, function(response) {
            window.location.replace(response);
          });
        },
        Cancel: function() {
          $(this).dialog("close");
        }
      }
    });
    
    // toggle message
    if ($('body').find('#googleplushangoutevent-msg').length === 1) {
      setTimeout(function () {
        $('#googleplushangoutevent-msg').toggle('slow');
      }, 2000);
    }
  }

  var formfield;

  /* user clicks button on custom field, runs below code that opens new window */
  $('#googleplushangoutevent-upload').click(function() {
    formfield = $(this).prev('input'); //The input field that will hold the uploaded file url
    tb_show('', 'media-upload.php?TB_iframe=true');
    
    return false;
  });
  //adding my custom function with Thick box close function tb_close() .
  window.old_tb_remove = window.tb_remove;
  window.tb_remove = function() {
    window.old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
    
    formfield = null;
  };
  // user inserts file into post. only run custom if user started process using the above process
  // window.send_to_editor(html) is how wp would normally handle the received data
  window.original_send_to_editor = window.send_to_editor;
  window.send_to_editor = function(html) {
    if (formfield) {
      fileurl = $('img', html).attr('src');
      $(formfield).val(fileurl);
      
      tb_remove();
    } else {
      window.original_send_to_editor(html);
    }
  };
  /* endBackend */
  
  /* frontend */
  // shortcode
  if ( $('body').find('.yghe-event').length >= 1 ) {
    $(".yghe-shortcode-countdown").each(function() {
      if (typeof $(this).data('cdate') !== "undefined") {
        googleplushangoutevent_makeCountDown( $(this).attr('id'), $(this).data('cdate') );
      }
    });
  }
  // widget
  if ( ( $('body').find('#ghe-event-widget').length === 1 ) || ( $('body').find('#ghe-hangout-widget').length === 1 ) ) {
    $(".ghe-countdown").each(function() {
      if (typeof $(this).data('cdate') !== "undefined") {  
        googleplushangoutevent_makeCountDown( $(this).attr('id'), $(this).data('cdate') );
      }
    });
  }
  /* endFrontend */
  
});

function googleplushangoutevent_makeCountDown(selector, startTime) {
  jQuery(function($){
    var todayTime = new Date(),
      beginTime = new Date(startTime),
      diff = new Date(beginTime-todayTime),
      diffMinutes = diff/1000/60,
      diffHours = diff/1000/60/60,
      diffDays = diff/1000/60/60/24,
      diffMonths = diff/2628000000,
      diffYears = diffMonths/12,
      countDownTemplate = '';

    if (diffYears >= 1) {
      countDownTemplate += '%y';
    }
    if (diffMonths >= 1) {
      countDownTemplate += '%m';
    }
    if (diffDays >=1) {
      countDownTemplate += '%d';
    }
    if (diffHours >=1) {
      countDownTemplate += '%h';
    }
    if (diffMinutes >=1) {
      countDownTemplate += '%i';
    }
    
    $("#"+selector).countdown({
      date: startTime,
      template: countDownTemplate + '%s',
      yearsAndMonths: true,
      timeSeparator: '|',
      leadingZero: false,
      onComplete: function( event ) {
        $(this).html("Completed");
      }
    });
  });
}
