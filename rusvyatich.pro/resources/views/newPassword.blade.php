@extends('layouts.userhead')

@section('content')
    <body class="authBody">
        @include('layouts.brandHead')
        <main>
            <form id="reset_form" action="" class="tool_form newPassword">
                <span class="captionForm">Восстановление пароля</span>
                <div class="beforeSend">
                    <p class="aboutFormInside">Пожалуйста введите новый пароль</p>
                        <div class="passwordBlockWrap">
                            <div class="input_block clearfix successOrder">
                                <input name="password" type="password" value="" spellcheck="false" autocomplete="off" placeholder="Введите Пароль">    
                                <input name="passwordCheck" type="password" value="" spellcheck="false" autocomplete="off" placeholder="Повторите пароль"> 
                                <input name="id_user" type="hidden" value="{{$id_user}}">
                                <input name="access_hash" type="hidden" value="{{$access_hash}}">
                            </div>
                            <div id="validation"></div>
                        </div>
                    <button id="changeButton" type="button" class="button" onclick="newPassword(); return false">Изменить</button>
                </div>
                <div class="afterSend">
                    <p class="aboutFormInside">На телефон <span id="phoneSended"></span> было выслано смс с паролем.</p><a href="/auth" style="color:black; text-align: center; display: block;">Войти</a>
                </div>
                <span class="timerAlert">Выслать пароль повторно через <span id="timer_inp"></span> секунд </span>
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
        $('input[type=password]').keydown(function(event){
            if(event.keyCode===13) {
                event.preventDefault();
                newPassword();
                return;
            }
        });
    });
</script>
@endsection