@extends('layouts.userhead')

@section('content')
    <body class="authBody">
        @include('layouts.brandHead')
        <main>
            <form id="reset_form" method="POST" action="" class="tool_form">
                <span class="captionForm">Изменение пароля</span>
                <div id="before_send">
                    <input type="password" name="password" placeholder="Старый пароль" autocomplete="off" style="margin-top: 10px;">
                    <input type="password" name="newPassword" placeholder="Новый пароль" autocomplete="off" style="margin-top: 10px;">
                    <input type="password" name="newPasswordCheck" placeholder="Повторите пароль" autocomplete="off" style="margin-top: 10px;">
                    <div id="notMatchPassword">Пароли не совпадают</div>
                    <div id="notLengthPassword">Введите не менее 5-и символов</div>
                    <button id="changePassword" type="button" class="button" onclick="changePasswordd(); return false">Изменить</button>
                </div>
                <div id="success_change_password">
                    <p>Ваш пароль успешно изменен!</p>
                    <a href="/home/user" style="color: black;">В личный кабинет</a>
                </div>
            </form>
        </main>
        @include('handleOldToken')   
        @include('layouts.footer')
</body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
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
    });
</script>
@endsection