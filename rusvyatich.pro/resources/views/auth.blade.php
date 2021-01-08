@extends('layouts.userhead')

@section('content')
    <body class="authBody notFixed">
        @include('layouts.brandHead')
        <main> 
            <form id="auth_form" method="POST" action="" class="tool_form">
                <span class="captionForm">Вход</span>
                <div class="pwdLogInput">
                    <input id="username" type="text" name="username" class="phone" placeholder="Ваш телефон" autocomplete="off" pattern="[0-9]*" inputmode="numeric" onclick="focusPhone(); return false;">
                    <input id="password" type="password" name="password" placeholder="Пароль" autocomplete="off">
                    <div class="wrongLogin">Не верный логин/пароль</div>
                </div>
                <label class="remember_me">
                    <span>Запомнить меня</span>
                    <input id="rememberme" type="checkbox" name="rememberme">
                    <label class="check" for="rememberme"></label>
                </label>
                <button id="entranceButton" type="button" class="button" onclick="authUser(); return false">Войти</button>
                <a id="forgotPassword" href="/auth/resetPwd">Забыли пароль?</a>
            </form>
            <div id="waitLogin" class="tool_form">
                <span class="waitText">Подождите минуточку пароль проверяется</span>

                <div class="circleBlock">
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                </div>
            </div>
        </main>
        @include('handleOldToken')    
        @include('layouts.footer')
</body>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
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
        if (!navigator.cookieEnabled) alert('Включите пожалуйста куки');
        /*Enter password*/
        $('#password, #username').keydown(function(event){
            if(event.keyCode===13) {
                authUser();
            }
        });
        $('#rememberme').prop('checked', true);

    });
</script>
@endsection