@extends('layouts.userhead')

@section('content')
    <body class='customerBody bodyChangeUser'>    
        <main>
            <div class="header">
                <h1 class="customerCaption">Мой профиль</h1>
            </div> 
            @include('adminLeftColumn') 
            <div class="aboutUser clearfix">
                <form id="userChangeForm" class="clearfix" method="POST">
                    <div class="adresCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Регион</span>
                            </dt>
                            <dd>
                                <input type="text" name="region" value="{{ $user->region }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Населенный пункт</span>
                            </dt>
                            <dd>
                                <input type="text" name="locality" value="{{ $user->locality }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Улица</span>
                            </dt>
                            <dd>
                                <input type="text" name="street" value="{{ $user->street }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Дом</span>
                            </dt>
                            <dd>
                                <input type="text" name="house" value="{{ $user->house }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Квартира</span>
                            </dt>
                            <dd>
                                <input type="text" name="flat" value="{{ $user->flat }}" class="notNecessary">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Почтовый индекс</span>
                            </dt>
                            <dd>
                                <input type="text" name="mailIndex" value="{{ $user->mail_index }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Зона проживания</span>
                            </dt>
                            <dd class="zonesDD">
                        <div class="zonesBlock">
                            <img src="{{ asset('img') }}/ZoneAll.png" width="285" height="160">
                            <input id="indZone1" class="" name="zoneInd" type="radio" value="1" @if ($user->id_area == 1) checked @endif>
                            <label for="indZone1" class="Radio fstZone"></label>
                            <input id="indZone2" class="" name="zoneInd" type="radio" value="2" @if ($user->id_area == 2) checked @endif>
                            <label for="indZone2" class="Radio secondZone"></label>
                            <input id="indZone3" class=""  name="zoneInd" type="radio" value="3" @if ($user->id_area == 3) checked @endif>
                            <label for="indZone3" class="Radio thirdZone"></label>
                        </div>     
                            </dd>
                        </dl>
                    </div>

                    <div class="nameCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Имя</span>
                            </dt>
                            <dd>
                                <input type="text" name="name" value="{{ $user->name }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Фамилия</span>
                            </dt>
                            <dd>
                                <input type="text" name="surname" value="{{ $user->surname }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Отчество</span>
                            </dt>
                            <dd>
                                <input type="text" name="patronymic" value="{{ $user->patronymic }}">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Почта</span>
                            </dt>
                            <dd>
                                <input type="text" name="email" value="{{ $user->email }}">
                                <div id="noteEmail" class="necessary">Не верный email формат</div>
                            </dd>
                        </dl><!-- 
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Смс уведомления</span>
                            </dt>
                            <dd>
                                <input id="checkHide" type='checkbox' name="sms_alert" @if ($user->sms_alert_id === SEND_SMS) 
                                        checked 
                                    @endif
                                >
                                <label for="checkHide" class="check"></label>
                            </dd>
                        </dl> -->
                        <a href="/home/user/changePassword" style="color: black; float: right;margin-top: 30px; margin-right: 15px;">Изменить пароль</a>
                        @if (Session::get('status') == CUSTOMER)
                            <button type="button" class="button dropUserButton" onclick="confirmationDropUser(); return false">Удалить аккаунт</button>
                        @endif
                        <button type="button" class="button changeUserButton" onclick="changeUser(); return false">Сохранить изменения</button>
                    </div>
                </form>
           </div>
            <div id='confirmationDropUser' class="wrapAlert">
                <div class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Удалить аккаунт безвозвратно?
                        </span>
                        <button id="confirmDrop" class="button leftButton" onclick="dropUser(); return false">Да</button>
                        <button class="button rightButton" onclick="rejectDropUser(); return false">Нет</button>
                    </div>
                </div>
            </div>
            <div id="success_message" class="wrapAlert">
                <div id="alert_message" class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Ваши данные успешно измененны!</span>
                        <button id="close_alert" class="button">продолжить</button>
                    </div>
                </div>
            </div>
        </main>
        @include('handleOldToken')    
        <footer>
        </footer>
    </body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
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

        $('.aboutUser').css('height', $(window).height() - $('.header').outerHeight() - 35);
        if(device.desktop()) $('.aboutUser').niceScroll({cursorcolor:'#8a8484', cursoropacitymin:'1', cursorwidth:9, cursorborder:'none', cursorborderradius:0, background: "#e8e8e8", mousescrollstep:25, bouncescroll: false});
        $(window).resize(function(){
            $('.aboutUser').css('height', $(window).height() - $('.header').outerHeight() - 35);
        });
        $.each($(".admin_navigation a"), function() {

            if ($(this).attr("href") == "#"){
                $(this).parent().addClass('navPushed');
            }
        });
    });

    $(" input[name='mailIndex']").mask("999999",{placeholder:"_"});
    
    /*Шаблон под фио (запрет цифр и символов кроме - ) + Первая буква заглавная*/
    $("input[name='name'], input[name='surname'],  input[name='patronymic']").on("change keyup input click", function(){
        if(this.value == "") return false; 
        if (this.value.match(/[^а-яA-Яa-zA-Z-\s]/g)){
        this.value = this.value.replace(/[^а-яА-Яa-zA-Z-\s]/g, '');
        }
        this.value=this.value[0].toUpperCase()+this.value.substring(1,this.length); 
    });

    /*Шаблон под первую заглавную букву*/
    $("input[name='street'], input[name='region'],  input[name='locality'], textarea").on("change keyup input click", function(){
        if(this.value == "") return false; 
        this.value=this.value[0].toUpperCase()+this.value.substring(1,this.length); 
    });

    /*скрытие блока успешного изменения данных*/
    $('#close_alert').click(function(event){
        $('#success_message').css('display','none');
    });
    $('a').click(function(){
        if($(this).attr("href") == "#") {
            return false;
        }
    });

    /*скрытие блока подтверждения удаления аккаунта при ошибке*/
    $('#close_error').click(function(){
    });

    $('input').keydown(function(event){
        if(event.keyCode===13) {
            event.preventDefault();
            changeUser();
            return;
        }
    });


</script>
</html>
@endsection