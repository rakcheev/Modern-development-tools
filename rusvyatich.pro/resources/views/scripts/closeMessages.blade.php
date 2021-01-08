
/*скрытие блока ошибки*/
$('#close_error').click(function(event){
	if ($('#alert_error').hasClass('reloadThis')) location.reload();
    $('.success').css('display','none');
});

/*скрытие блока заметки*/
$('#close_note').click(function(event){
    $('.success').css('display','none');
});
