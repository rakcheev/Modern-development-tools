$('a').click(function(){
    if($(this).attr("href") == "#") {
        return false;
    }
});
if (!device.desktop()) {
	$('body').addClass('mobileBody');
}

$('input').on("change keyup input click", function(){
    $(this).removeClass('red');
});

if (device.desktop() && navigator.userAgent.search(/Firefox/) > 0){
    $('.admin_navigation').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:0, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8", mousescrollstep:45, bouncescroll: false});
} 