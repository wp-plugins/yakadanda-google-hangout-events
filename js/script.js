jQuery(function($){
  /* backend */
  if ( $('body').find('#google-hangout-event-all-events').length === 1 ) {
    
  }
  if ( $('body').find('#google-hangout-event-settings').length === 1 ) {
    // select themes, sizes, and styles
    var optionsFirst = new Array("title", "date", "detail", "icon", "countdown", "event_button"),
    optionsLast = new Array("theme", "size", "style");
    
    for ( f in optionsFirst ) {
      for ( l in optionsLast ) {
        var the_value = $('#hidden_'+optionsFirst[f]+'_'+optionsLast[l]).val();
        if ( the_value ) $('#'+optionsFirst[f]+'_'+optionsLast[l]+' option[value="' + the_value + '"]').attr('selected',true);
      }
    }
    
    // Farbtastic Color Picker
    var f = $.farbtastic('#picker');
    var p = $('#picker').css('opacity', 0.25);
    var selected;
    
    $('.colorwheel').each(function () { f.linkTo(this); $(this).css('opacity', 0.75); }).focus(function() {
      if (selected) {
        $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
      }
      f.linkTo(this);
      p.css('opacity', 1);
      $(selected = this).css('opacity', 1).addClass('colorwell-selected');
    });
    
    $('#googleplushangoutevent-help-tab').on('click', function(e) {
      $('#contextual-help-link').trigger('click');
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
      if (typeof $(this).attr('time') !== "undefined") {  
        googleplushangoutevent_makeCountDown( $(this).attr('id'), $(this).attr('time') );
      }
    });
  }
  // widget
  if ( ( $('body').find('#ghe-event-widget').length === 1 ) || ( $('body').find('#ghe-hangout-widget').length === 1 ) ) {
    $(".ghe-countdown").each(function() {
      if (typeof $(this).attr('time') !== "undefined") {  
        googleplushangoutevent_makeCountDown( $(this).attr('id'), $(this).attr('time') );
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
      diffMinutes = diff/1000/60;
      diffHours = diff/1000/60/60;
      diffDays = diff/1000/60/60/24;
      diffMonths = diff/2628000000,
      diffYears = diffMonths/12;
    
    minutes = hours = days = months = years = '';
    if (diffMinutes >=1) {
      text = 'Minute';
      if (diffHours >=2 ) { text = 'Minutes'; }
      minutes = '%i<span>&nbsp;'+text+'</span><em>|</em>';
    }
    if (diffHours >=1) {
      text = 'Hour';
      if (diffHours >=2 ) { text = 'Hours'; }
      hours = '%h<span>&nbsp;'+text+'</span><em>|</em>';
    }
    if (diffDays >=1) {
      text = 'Day';
      if (diffDays >=2 ) { text = 'Days'; }
      days = '%d<span>&nbsp;'+text+'</span><em>|</em>';
    }
    if (diffMonths >= 1) {
      text = 'Month';
      if (diffMonths >=2 ) { text = 'Months'; }
      months = '%m<span>&nbsp;'+text+'</span><em>|</em>';
    }
    if (diffYears >= 1) {
      text = 'Year';
      if (diffYears >=2 ) { text = 'Years'; }
      years = '%y<span>&nbsp;'+text+'</span><em>|</em>';
    }
    
    $("#"+selector).countdown({
      htmlTemplate: years + months + days + hours + minutes + "%s<span>&nbsp;Seconds</span>",
      date: startTime,
      yearsAndMonths: true,
      /*servertime: function() { 
        var time = null; 
        $.ajax({url: 'get_time.php', 
          async: false, 
          dataType: 'text', 
          success: function( data, status, xhr ) {  
            time = data; 
          }, 
          error: function(xhr, status, err) { 
            time = new Date(); 
            time = time.getTime();
          }
        });
        return time; 
      },*/
      hoursOnly: false,
      onComplete: function( event ) {
        $(this).html("Completed");
      },
      onPause: function( event, timer ) {
        $(this).html("Pause");
      },
      onResume: function( event ) {
        $(this).html("Resumed");
      },
      leadingZero: true
    });
  });
}
