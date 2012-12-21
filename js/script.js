jQuery(function($){
  
  // In admin page settings
  if ( $('body').find('#google-hangout-event').length == 1 ) {
    var the_value = $('#hidden_display').val();
    
    if ( the_value ) $('#display option[value=' + the_value + ']').attr('selected',true);
    
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
  if ( $('body').find('#ghe-widget').length == 1 ) {
    var gheStartTimes = $('#ghe-start-times').val();
    
    startTimes = gheStartTimes.split(';');
    
    for (i in startTimes) {
      loadCountDown(i, startTimes[i]);
      $("#ghe-time-"+i).text( moment(startTimes[i]).format('MMMM Do YYYY - h.mma Z') );
    }
   
  }
  
});

function loadCountDown(i, startTime) {
  jQuery(function($){
    $("#ghe-countdown-"+i).countdown({
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
