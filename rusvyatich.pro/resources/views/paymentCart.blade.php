@extends('layouts.site')

@section('content')
    <body>
        @include('layouts.brandHead')
        <main class="payBody"> 
            <div class="container">
            @if (!Session::has('userId'))
            <div class="wrapNextTimer">
                <div class="aboutSendedSms">Вам отправлено SMS-сообщение для доступа к личному кабинету, где вы сможете отслеживать статус заказа. В случае если SMS не пришло пожалуйста пройдите по ссылке для <a href="/auth/resetPassword">восстановления пароля</a></div>
            </div>
            @endif
            @if ($minutes || $seconds)
            <div class="refuseBlock">
                <div class="timerBlock"> <span id='timer'></span>После истечения указанного времени заказ автоматически отменяется </div>
                @if (!Session::has('userId'))<div class="canRefuseAndDelete">Вы можете отменить заказ и вместе с тем удалить ваш аккаунт нажав на следующую кнопку</div>@endif
                <button id="refuseAuth" class="button" onclick="confirmationRefuse();">отменить заказ</button>
            </div>
            @endif
                    <div style="margin-top: 60px;">
                        <span class="headerCondition">Детали заказа</span>
                    </div>
                <div id="bodyKnifeProperties" class="bodyKnifeProperties">
                    <ul id="cart_slider" class="cart_slider">
                        @foreach ($products as $product)
                            <li class="cartElement clearfix" onclick="toProductForUser({{ $product->id }})">
                                <img src="{{ asset('img/imgStorage') }}/{{ $product->link_of_image }}" width="143px" height="80px">
                                <span id="id" class="thumbdescription">{{ $product->name }}</span>
                                <span class="costCartElement">{{ $product->price }} <span class="forBig">р.</span></span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div id="costSum">Общая цена: {{$order->sum_of_order}} + {{DELIVERY_COST}} (доставка) = {{$order->sum_of_order + DELIVERY_COST}} р.</div>
                <div class="orderPayBlock">
                    <span class="headerCondition">Информация о клиенте</span>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Имя</span>
                            </dt>
                            <dd>@if($order->name) {{ $order->name }} @else <span class="absenseAboutUser">.</span>  @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Фамилия</span>
                            </dt>
                            <dd >@if($order->surname) {{ $order->surname }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Отчество</span>
                            </dt>
                            <dd>@if($order->patronymic) {{ $order->patronymic }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Телефон</span>
                            </dt>
                            <dd >@if($order->phone) {{ $order->phone }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>email</span>
                            </dt>
                            <dd >@if($order->email) {{ $order->email }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                </div>
                <div class="orderPayBlock">
                    <span class="headerCondition">Адрес доставки</span>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Регион</span>
                            </dt>
                            <dd>@if($order->region) {{ $order->region }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Населенный пункт</span>
                            </dt>
                            <dd>@if($order->locality) {{ $order->locality }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Улица</span>
                            </dt>
                            <dd>@if($order->street) {{ $order->street }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Дом</span>
                            </dt>
                            <dd>@if($order->house) {{ $order->house }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Квартира</span>
                            </dt>
                            <dd>@if($order->flat) {{ $order->flat }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Почтовый индекс</span>
                            </dt>
                            <dd>@if($order->mail_index) {{ $order->mail_index }} @else <span class="absenseAboutUser">.</span> @endif</dd>
                        </dl>
                </div>
                <div id="canChangeAlert">
                    <span>Изменить информацию о себе и адрес доставки можно в <a href="/home/user" style="color: black;">личном кабинете</a></span>
                </div>
                <div class="orderPayBlock">
                    <div class="conditionCheck">
                        <input id="acception" type="checkbox" name="conditions">
                        <label class="checkConditions" for="acception"></label>
                        <span class="textForCheckbox">
                            <span>Я прочитал(а) и принимаю</span> <a href="/conditionsPays" target="_blank">Условия платежной системы</a>
                        </span>
                    </div>
                        <form method="post" action="https://sci.interkassa.com/" enctype="utf-8">
                            <input type="hidden" name="ik_co_id" value="5aab5e593d1eaffa678b4567" />
                            <input type="hidden" name="ik_pm_no" value="{{ ($typeOrder == CONSTRUCT_ORDER) ? 'construct' : (($typeOrder == CART_ORDER) ? 'cart' : 'image')}}_order_{{$order->id}}" />
                            <input type="hidden" name="ik_am" value="{{$order->sum_of_order + DELIVERY_COST - $order->money_payed}}" />
                            <input type="hidden" name="ik_x_typeorder" value="{{$typeOrder}}" />
                            <input type="hidden" name="ik_x_idorder" value="{{$order->id}}" />
                            <input type="hidden" name="ik_cur" value="RUB" />
                            <input type="hidden" name="ik_desc" value="Оплата заказа №{{$order->id}}" />
                                <input id="payButton" class="button button2" type="submit" value="оплатить">
                        </form>

                </div>
            </div>
            <div id="wrap">
                <div id="popup_product">
                    <div class="phoneLineClose">
                        <span>Просмотр ножа</span>
                    </div>
                    <div class="close">
                        <button id="product_close" class="window_close product_close" onclick="closeProduct();">Закрыть</button>
                    </div>
                    <div class="product_itself_popup clearfix" id="popupMainScroll">
                        <div class="left_product clearfix">
                            <div class="main_for_product_img">
                                <img src="" width="519px" height="290px" class="main_product_img">
                                <div  id='enlarge' class = 'enlarge'></div>
                            </div>
                            <div class="choose_view clearfix">
                                <img src="" width="232px" height="130px" class="view_pushed">
                                <img src="" width="232px" height="130px">
                            </div>
                        </div>
                        <div class="right_product clearfix">
                            <span id="nameKnife"></span>
                            <div class="product_popup_description" id="popup_first_desc">
                                <span class='title_of_description'>Описание:</span><p></p>
                            </div>
                            <div class="product_description" id="popup_second_desc">
                                <span class='title_of_description'>Характеристики:</span>
                                <dl class="dl-inline steel clearfix">
                                    <dt class="dt-dotted">
                                        <span>Сталь</span>
                                    </dt>
                                    <dd></dd>
                                </dl>
                                <dl class="dl-inline length clearfix">
                                    <dt class="dt-dotted">
                                        <span>Длина клинка</span>
                                    </dt>
                                    <dd></dd>
                                </dl>
                                <dl class="dl-inline width clearfix">
                                    <dt class="dt-dotted">
                                        <span>Ширина клинка</span>
                                    </dt>
                                    <dd></dd>
                                </dl>
                                <dl class="dl-inline thickness clearfix">
                                    <dt class="dt-dotted">
                                        <span>Толщина обуха</span>
                                    </dt>
                                    <dd></dd>
                                </dl> 
                                <dl class="dl-inline handle_length_dl clearfix">
                                    <dt class="dt-dotted">
                                        <span>Длина ручки</span>
                                    </dt>
                                    <dd></dd>
                                </dl> 
                            </div>
                            <div class="cost_of_product">
                                <span class="cost_popup"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wrap_for_product">
                <img id="windowImage" src="">
                <button id="close_main_img" class="window_close product_close" type="button" title="Закрыть"onclick="closeMainImg();">
            </div>
            <div id='confirmationRefuse' class="wrapAlert success">
                <div id="alertOut" class="alert alertDaNet">
                    @if (!Session::has('userId'))
                    <span class="captionAlert">Отказаться от заказа?</span>
                    <button class="button leftButton" onclick="refuseOrderUnauth({{ $order->id}}, {{$typeOrder }}); return false"">Да</button>
                    @else
                    <span class="captionAlert">Отменить заказ?</span>
                    <button class="button leftButton" onclick="refuseOrder({{ $order->id}}, {{$typeOrder }}); return false"">Да</button>
                    @endif
                    <button class="button rightButton" onclick="rejectRefuse()">Нет</button>
                </div>
            </div>
        </main>
        @include('handleOldToken')    
        @include('layouts.footerBig')
</body>
<script type="text/javascript">
    // "global" vars
    var pathImage = "{{ asset('img') }}/";
</script>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    $().ready(function(){
        @include('scripts.sameScripts')

        if (device.desktop()) {
           $('.product_popup_description p').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:9, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, zindex: -10 });
        }
            @if ($minutes || $seconds) 
            var sec={{$seconds}};
            var min={{$minutes}};

            function refresh()
            {
                sec--;
                if(sec==-01){sec=59; min=min-1;}
                else{min=min;}
                if(sec<=9){sec="0" + sec;}
                time=(min<=9 ? "0"+min : min) + " мин." + sec +'сек';
                if(document.getElementById){timer.innerHTML=time;}
                inter=setTimeout(refresh, 1000);
                // действие, если таймер 00:00
                if(min=='00' && sec=='00'){
                    sec="00";
                    clearInterval(inter);
                   location.reload();
                }
            }
            refresh();
            @endif
            /*Лупа при наведении*/
            $(document).mousemove(function(e){
                
                    place_image=$('.main_product_img').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x < $('.main_product_img').outerWidth() &&
                        y < $('.main_product_img').outerHeight() &&
                        x > 0 &&
                        y > 0
                    ) {
                        $('.enlarge').css('display','block');
                    } else {
                        $('.enlarge').css('display','none');
                    }
                if ($('#wrap_for_product').is(':visible')) {
                    place_image=$('#windowImage').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#windowImage').outerWidth() ||
                        y > $('#windowImage').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('#wrap_for_product').addClass('hoveredClose');
                    } else {
                        $('#wrap_for_product').removeClass('hoveredClose');
                    }
                } else if ($('#wrap').is(':visible')) {
                    place_image=$('#popup_product').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#popup_product').outerWidth() ||
                        y > $('#popup_product').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('.close').addClass('hoveredClose');
                    } else {
                        $('.close').removeClass('hoveredClose');
                    }
                }
            });
            /*Аналогичные действия esc-ейпу от клика вне*/
            $(document).mouseup(function (e) {
                if ($('body').hasClass('unclicked') || $('#error_message').is(':visible') || $('#note_message').is(':visible')) return false;
                if (e.which != 1) return false;
                var div = $('#alert_message');
                if ($('#wrap_for_product').is(':visible')){
                    div=$('#windowImage');
                    if(div.is(':visible')){
                        if (!div.is(e.target) && div.has(e.target).length === 0) {
                            closeMainImg();
                        }
                    } 
                } else if ($('#wrap').is(':visible')){
                    div=$('#popup_product');
                    if(div.is(':visible')){
                        if (!div.is(e.target) && div.has(e.target).length === 0) {
                            closeProduct();
                        }
                    }
                }
            });

            /*Отработка действия клавиши esc*/
            window.addEventListener("keydown", function(event){
                if(event.keyCode===27) {
                    doEsc();
                }
            });
            /*выбор картинки в просмотре продукта*/
            $('.choose_view img').click(function(event){
                $('.choose_view img').removeClass('view_pushed');
                $(this).addClass('view_pushed');
                var src_img = $(this).attr('src');
                var nameImg = src_img.split('/');
                nameImg = nameImg[nameImg.length-1];
                if ($(window).width()>1000) {
                    $('.main_product_img').attr('src', "{{ asset('img/imgStorage') }}/" + nameImg);
                } else {
                    $('.main_product_img').attr('src', "{{ asset('img/imgStoragePhone') }}/" + nameImg);
                }
            });
            /*Вывод картинки на весь экран*/
            $('.main_for_product_img').click(function(){
                $('#wrap_for_product').css('display','block');
                $('#wrap').css('display','none');
                var src =$('.main_product_img').attr('src');
                $('#windowImage').attr('src',src);
            });
            function resizeImgScreen(){
                if ((($(window).width()) / $(window).height())> 1.63 && $('.right_product').css('float') == 'none') {
                    $('.main_product_img').css('height', $(window).height() - 70);
                    $('.main_product_img').css('width', 'auto');
                } else if ($('.right_product').css('float') == 'none') {
                    $('.main_product_img').css('height','auto');
                    $('.main_product_img').css('width','100%');
                } else {
                    
                    $('.main_product_img').css('height','290px');
                    $('.main_product_img').css('width','519px');

                }
            }
            resizeImgScreen();
        $(window).resize(function(){
            resizeImgScreen();
            resizeDescriptionPopup();
        });
    });
</script>
@endsection