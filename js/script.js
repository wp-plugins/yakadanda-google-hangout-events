jQuery(function($){
  
  // In admin page settings
  if ( $('body').find('#google-hangout-event').length == 1 ) {
    // select display
    var value_of_display = $('#hidden_display').val();
    if ( value_of_display ) $('#display option[value=' + value_of_display + ']').attr('selected',true);
    
    // select countdown
    var  value_of_countdown_display = $('#hidden_countdown_display').val();
    if ( value_of_countdown_display ) $('#countdown_display :radio[value=' + value_of_countdown_display + ']').attr('checked',true);
    
    // select themes, sizes, and styles
    var optionsFirst = new Array("title", "date", "detail", "countdown"),
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
    
  }
  
  // In frontend
  if ( $('body').find('#ghe-widget-single').length == 1 ) {
    var gheStartTimesSingle = $('#ghe-start-times-single').val();
    startTimesSingle = gheStartTimesSingle.split(';');
    for (i in startTimesSingle) {
      loadCountDown(i, startTimesSingle[i], 'ghe-countdown-single-');
      $("#ghe-time-single-"+i).text( moment(startTimesSingle[i]).format('MMMM Do YYYY - h.mma Z') );
    }
  }
  
  if ( $('body').find('#ghe-widget-extra').length == 1 ) {
    var gheStartTimesExtra = $('#ghe-start-times-extra').val();
    startTimesExtra = gheStartTimesExtra.split(';');
    for (i in startTimesExtra) {
      if ( $('body').find('#ghe-countdown-extra-'+i).length == 1 ) {
        loadCountDown(i, startTimesExtra[i], 'ghe-countdown-extra-');
      }
      $('#ghe-time-extra-'+i).text( moment(startTimesExtra[i]).format('MMMM Do YYYY - h.mma Z') );
    }
  }
  
});

function loadCountDown(i, startTime, selector) {
  jQuery(function($){
    var theSelector = selector + i;
    $("#"+theSelector).countdown({
      htmlTemplate: "%d<span>Days</span><em>|</em>%h<span>Hours</span><em>|</em>%i<span>Minutes</span><em>|</em>%s<span>Seconds</span>",
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
