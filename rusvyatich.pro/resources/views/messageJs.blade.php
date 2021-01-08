
$("[contenteditable='true']").each(function(){       
    var id = $(this).attr("id");
    $(this).bind('blur keyup paste input', function() {
  		changeLettersHeight();
    });
});

document.getElementById('file').addEventListener('change', handleFileSelect, false);

 $("[contenteditable='true']").on('focus', function(){
    var $this = $(this);
    $this.html( $this.html() + '<br>' );  // firefox hack
  });

  $("[contenteditable='true']").on('blur focuseout', function(){
    var $this = $(this);
    $this.text( $this.text().replace('<.*?>', '') );
  });

$('div[contenteditable]').keydown(function(e) {
    if (e.keyCode === 13) {
      $('#letterButton').trigger('click');
      return false;
    }  
  });
$(function($){
    $("[contenteditable]").focusout(function(){
        var element = $(this);        
        if (!element.text().trim().length) {
            element.empty();
        }
    });
});
   $(document).mouseup(function(e){
    if ($('body').hasClass('unclicked') || $('#error_message').is(':visible') ||
     $('#note_message').is(':visible')) return false;
    if (e.which != 1) return false;
    var div = $('#window');
        if ( $('#viewMessageImg').is(':visible')){
            if (!div.is(e.target) && div.has(e.target).length === 0) {
              closeMessageImg();
            }
        }
        if ($('#svgWindow').is(':visible')) {
            var div = $('#svgWindow .test_svg');
            if (!div.is(e.target) && div.has(e.target).length === 0) {
                closeSvgWindow();
            }
        }
    });

/*Просмотр картинки сообщения*/
$(document).mousemove(function(e){
    if($('#viewMessageImg').is(':visible')){
        place_image=$('#window').offset();
        x = e.pageX - place_image.left;
        y = e.pageY - place_image.top;
        if (
            x > $('#window').width() ||
            y > $('#window').height() ||
            x < 0 ||
            y < 0
        ) {
            $('#close_message_img').addClass('hoveredClose');
        } else {
            $('#close_message_img').removeClass('hoveredClose');
        }
    }  else if ($('#svgWindow').is(':visible')){
        place_image=$('#svgWindow .test_svg').offset();
        x = e.pageX - place_image.left;
        y = e.pageY - place_image.top;
        if (
            x > $('#svgWindow .test_svg').width() ||
            y > $('#svgWindow .test_svg').height() ||
            x < 0 ||
            y < 0
        ) {
            $('#close_svg').addClass('hoveredClose');
        } else {
            $('#close_svg').removeClass('hoveredClose');
        }
    }
});

if ( !$('#refuseButton').is(':visible')) {
    $('#online').addClass('withoutRefuseButton');
}