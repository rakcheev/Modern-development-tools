@extends('layouts.userhead')

@section('content')
    <body class="authBody firstStageReset">
        @include('layouts.brandHead')
        <main>
            <form id="reset_form" class="tool_form">
                <span class="captionForm">Восстановление пароля</span>
                <div class="beforeSend">
                    <p class="aboutFormInside">Пожалуйста введите ваш телефон</p>
                    <input id="username" type="text" name="username" class="phone" placeholder="Ваш телефон" autocomplete="off" onclick="focusPhone(); return false;">
                    <button id="resetButton" type="button" class="button" onclick="resetPasswordByEmail(); return false">Восстановить</button>
                </div>
                <div class="afterSend">
                    <p id="sended" class="aboutFormInside">На почту <b id="emailSended"></b> была отправлено письмо с ссылкой для восстановления пароля (cсылка будет активна в течении {{RESET_TIME_EMAIL/60}}-и минут).</p> 
                    <p id="alreadySended" class="aboutFormInside">Письмо уже было отправлено, повторите попытку позже</p>
                </div>
                <span class="timerAlert">Выслать сообщение повторно через <span id="timer_inp"></span> секунд </span>
                <span class="dayLimit">Колличество попыток на сегодня окончено</span>
            </form>
        </main>
        @include('handleOldToken')   
        @include('layouts.footer')
</body>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    $().ready(function(){
        @include('scripts.closeMessages')
        @include('scripts.sameScripts')

        /*Шаблон для телефона*/
        $(".phone").mask("+7(999) 999-99-99",{placeholder:"_"});
        $('#username').keydown(function(event){
            if(event.keyCode===13) {
                event.preventDefault();
                resetPasswordByEmail();
                return;
            }
        });
        
        function timer(){
            if ($('.dayLimit').is(':visible')) return;
            var oldHtml = $('#timer_inp').html();
            if (oldHtml == 0) {
                $('.timerAlert').css('display', 'none');
                //$('#resetButton').css('display', 'block');
                $('.beforeSend').css('display', 'block');
                $('.afterSend').css('display', 'none');
            } else {
                $('#timer_inp').html(oldHtml-1);
            }
        }
        setInterval(timer,1000);
    });
</script>
@endsection