jQuery(function($){
  
  // In admin page settings
  if ( $('body').find('#google-hangout-event').length === 1 ) {
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
    
    $( "#setup_tabs" ).tabs();
    
  }
  
  /* In frontend */
  // 1st widget
  if ( $('body').find('#ghe-1st-widget').length === 1 ) {
    var gheStartTimes1st = $('#ghe-start-times-1st').val();
    if (typeof gheStartTimes1st !== "undefined") {
      startTimes1st = gheStartTimes1st.split(';');
      for (i in startTimes1st) {
        if ( ($('body').find('#ghe-countdown-1st-'+i).length === 1) && startTimes1st[i]) {
          loadCountDown(i, startTimes1st[i], 'ghe-countdown-1st-');
        }
      }
    }
  }
  // 2nd widget
  if ( $('body').find('#ghe-2nd-widget').length === 1 ) {
    var gheStartTimes2nd = $('#ghe-start-times-2nd').val();
    if (typeof gheStartTimes2nd !== "undefined") {
      startTimes2nd = gheStartTimes2nd.split(';');
      for (i in startTimes2nd) {
        if ( ($('body').find('#ghe-countdown-2nd-'+i).length === 1) && startTimes2nd[i] ) {
          loadCountDown(i, startTimes2nd[i], 'ghe-countdown-2nd-');
        }
      }
    }
  }
  
});

function loadCountDown(i, startTime, selector) {
  jQuery(function($){
    var theSelector = selector + i,
    todayTime = new Date(),
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
    
    $("#"+theSelector).countdown({
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
