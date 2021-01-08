@extends('layouts.shop')

@section('content')

<body class="productBody">
@include('layouts.metrix')
    <header class="mainHeader staticHeader">
        <div class="container clearfix">
            <a href="/" class="Runic brandInHeader">ВяТИч</a>
            <div class="header_right">
                <div class="phone_for_call">
                    <span class="timeCall">c {{START_WORK_DAYSHIFT}}:00 до {{END_WORK_DAYSHIFT}}:00</span><span>+7 (925) 195-59-78</span>
                </div>
                <div class="userIndexBlock">
                    <span id="countBox">
                        <script id="countTemplate" type="text/x-jquery-tmpl">
                            @{{if newCount>0}}<span class="newMessageCount @{{if newCount>9}}wideCountTwo@{{/if}} @{{if newCount>99}}wideCountThree@{{/if}}">${newCount}</span>@{{/if}}
                        </script>
                    </span>
                <div class="wrapLogin icon-profile">
                <a id="toHome" href="@if (!empty(Session::get('userId')))
                    /home
                @else
                    /auth
                @endif"
                @if (!empty(Session::get('userId')))
                    class="accounted"
                @endif>{{ $username }}</a></div>
                @if (!empty(Session::get('userId')))
                <a id="outUser" class="icon-exit" href="#" onclick="confirmationOut(); return false">Выйти</a>
                @endif
                </div>
                <div class="bond_block">
                    <a href="https://vk.com/rusvyatich" class="vkontakte" target="_blank">Вконтакте</a>
                    <a href="https://www.instagram.com/rusvyatich/" class="instagram" target="_blank">Инстаграм</a>
                </div>
            </div>
        </div>
    </header>
<main role="main">
    <div id="wrap_for_product">
        <img id="window" src="/./.:0" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
        <button class="nextMainImage">
            <div class="arrow"></div>
        </button>
        <button class="prevMainImage">
            <div class="arrow"></div>
        </button>
        <button id="close_main_img" class="window_close" type="button" title="Закрыть"onclick="closeMainImg();"></button>
    </div>
    <div class="container">
        <div class="prePage">
            <ul class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/shop">Магазин ножей</a></li>
                <li><a href="#">{{ $knife->name }}</a></li>
            </ul>
        </div>
            <div class="productBlock clearfix">
                <section class="left_product clearfix">
                    <section class="main_for_product_img">
                        <img data-number="0" src="{{ asset('img/imgStorage') }}/{{ $KnifeImages[0]->image }}?{{VERSION}}" width="100%" height="auto" class="main_product_img" alt="Нож {{ $knife->name }} (кузница Вятич)" title="Нож {{ $knife->name }} (кузница Вятич)">
                        <div  id='enlarge' class = 'enlarge'></div>
                    </section>
                    <section class="wrapForPaginationImage">
                        <div class="nextImage"></div>
                        <div class="prevImage"></div>
                        <div class="choose_view clearfix">
                            <ul class='imageLine'>
                                @foreach($KnifeImages as $z=>$KnifeImage)
                                <li class="imageLi">
                                    <img id="img{{$z}}" src="{{ asset('img/imgStorage') }}/{{ $KnifeImage->image }}?{{VERSION}}" width="232px" height="130px" @if ($z === 0) class="view_pushed" alt="Нож {{ $knife->name }} (кузница Вятич)" title="Нож {{ $knife->name }} (кузница Вятич)" @endif style="width: 232px; height: 130px;">
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                </section>
                <section class="right_product clearfix">
                    <span id="nameKnife">{{ $knife->name }} ({{ $knife->steel }})</span>
                    <div class="product_popup_description" id="popup_first_desc">
                        <span class='title_of_description'>Описание:</span><p>{{ $knife->description }}</p>
                    </div>
                    <div class="product_description" id="popup_second_desc">
                        <span class='title_of_description'>Характеристики:</span>
                        <dl class="dl-inline steel clearfix">
                            <dt class="dt-dotted">
                                <span>Сталь</span>
                            </dt>
                            <dd>{{ $knife->steel }}</dd>
                        </dl>
                        <dl class="dl-inline length clearfix">
                            <dt class="dt-dotted">
                                <span>Длина клинка</span>
                            </dt>
                            <dd>{{ $knife->blade_length }} мм</dd>
                        </dl>
                        <dl class="dl-inline width clearfix">
                            <dt class="dt-dotted">
                                <span>Ширина клинка</span>
                            </dt>
                            <dd>{{ $knife->blade_width }} мм</dd>
                        </dl>
                        <dl class="dl-inline thickness clearfix">
                            <dt class="dt-dotted">
                                <span>Толщина обуха</span>
                            </dt>
                            <dd>{{ $knife->blade_thickness }} мм</dd>
                        </dl> 
                        <dl class="dl-inline handle_length_dl clearfix">
                            <dt class="dt-dotted">
                                <span>Длина ручки</span>
                            </dt>
                            <dd>{{ $knife->handle_length }} мм</dd>
                        </dl> 
                        <span class="cost_popup">Цена: {{ $knife->price }} р.</span>
                    </div>
                    
                    <button id="to_form" class="button addToCart" onclick="addToCart(); return false;" disabled="disabled">В корзину</button>
                </div>
            </section>
            <section class="infoProduct">
                <section class="content deliveryContentProduct">
                    <h4>Доставка</h4>
                    <ul class="list">
                        @foreach ($typeOfSends as $typeOfSend)
                            <li>{{$typeOfSend->description}} ({{$typeOfSend->price}} р.)</li>
                        @endforeach
                    </ul>
                </section>
            </section>
    </div>
    <div id="success_after_add_to_cart" class="success">
        <div id="alert_after_add_to_cart" class="alert">
            <span class="captionAlert">Нож добавлен в корзину</span>
            <button class="button" id="return_to_buy" onclick="returnToBuy();">продолжить покупки</button>
            <button class="button" id="to_cart_after_add">перейти в корзину</button>
        </div>
    </div>
    @include('layouts.orderPopup')
</main>
@include('handleOldToken')    
@include('layouts.footerBig')
@include('layouts.cart')
</body>
<script>
    var idKnife = {{ $knife->id }}

    var PAY_WITHOUT = {{WITHOUT_PAY}}
    var PERSENT = {{PERSENT}}
</script>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.tmpl.js') }}?{{VERSION}}" type="text/javascript"></script>
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

            var imageLi = 0;
            var width = 0;
            var widthSlider = 0;
            var i = 0;
            var offset = 0;
            function setWidth(){
                imageLi = $(".choose_view .imageLine").children(".imageLi"); // Получаем массив всех слайдов
                width = $(".choose_view .imageLi").width()+10;
                widthSlider = $(".choose_view").width(); // Получаем ширину видимой области
                i = imageLi.length; // Получаем количество слайдов
                offset = i * width;
                $(".choose_view .imageLine").css('width',offset); // Задаем блоку со слайдами ширину всех слайдов // Задаем начальное смещение и ширину всех слайдов
                $(".choose_view .imageLine").css("transform","translate3d(-0px, 0px, 0px)");
                offset = 0;
            }
            setWidth();
            
            offset = 0; // Обнуляем смещение, так как показывается начала 1 слайд
            var countImages = {{count($KnifeImages)}}-1;
            $(".nextImage").click(function(){    // Событие клика на кнопку "следующий слайд"
                if (offset < (width * i -widthSlider) ) {    // Проверяем, дошли ли мы до конца
                    offset += width; // Увеличиваем смещение до следующего слайда
                    $(".choose_view .imageLine").css("transform","translate3d(-"+offset+"px, 0px, 0px)"); // Смещаем блок со слайдами к следующему
                }
                    
            });
     
            $(".prevImage").click(function(){    // Событие клика на кнопку "предыдущий слайд"
                if (offset > 0) { // Проверяем, дошли ли мы до конца
                    offset -= width; // Уменьшаем смещение до предыдущего слайда
                    $(".choose_view .imageLine").css("transform","translate3d(-"+offset+"px, 0px, 0px)"); // Смещаем блок со слайдами к предыдущему
                }
            });


            var imageNumber = 0;
            var images = [
                @foreach($KnifeImages as $z=>$KnifeImage)
                    "{{ $KnifeImage->image }}?{{VERSION}}",
                @endforeach
            ];
            function changePhoneFoldrers() {
                if ($(window).width()>1000) {
                    $('.main_product_img').attr('src', "{{ asset('img/imgStorage') }}/" + images[imageNumber]);
                    $('#window').attr('src', "{{ asset('img/imgStorage') }}/" + images[imageNumber]);
                } else {
                    $('.main_product_img').attr('src', "{{ asset('img/imgStoragePhone') }}/" + images[imageNumber]);
                    $('#window').attr('src', "{{ asset('img/imgStoragePhone') }}/" + images[imageNumber]);
                }
            }
            $(".nextMainImage").click(function(){
                if (imageNumber >= (images.length-1)) return;
                imageNumber++;
                if ($(window).width()>1000) {
                    $('#window').attr('src', "{{ asset('img/imgStorage') }}/" + images[imageNumber]);
                } else {
                    $('#window').attr('src', "{{ asset('img/imgStoragePhone') }}/" + images[imageNumber]);
                }
            });
            $(".prevMainImage").click(function(){
                if (imageNumber == 0) return;
                imageNumber--;
                if ($(window).width()>1000) {
                    $('#window').attr('src', "{{ asset('img/imgStorage') }}/" + images[imageNumber]);
                } else {
                    $('#window').attr('src', "{{ asset('img/imgStoragePhone') }}/" + images[imageNumber]);
                }

            });
            checkCart();
            $('#to_cart_after_add').click(function(){
                tmplKnife();
                $('#wrap').css('display','none');
                $('#success_after_add_to_cart').css('display','none');
            });
            /*выбор картинки в просмотре продукта*/
            $('.choose_view img').click(function(event){
                $('.choose_view img').removeClass('view_pushed');
                $(this).addClass('view_pushed');
                var src_img = $(this).attr('src');
                var nameImg = src_img.split('/');
                nameImg = nameImg[nameImg.length-1];
                imageNumber = parseInt($(this).attr('id').replace(/\D+/g,""));
                $('.main_for_product_img img').attr('data-number', imageNumber);
                if ($(window).width()>1000) {
                    $('.main_product_img').attr('src', "{{ asset('img/imgStorage') }}/" + nameImg);
                } else {
                    $('.main_product_img').attr('src', "{{ asset('img/imgStoragePhone') }}/" + nameImg);
                }
                //imageNumber = parseInt($(this).attr("id"));
            });
            function firstImpressionChanger() {
                if ($(window).width() <= '740'){
                    $('.brandInHeader').text('В');
                } else {
                    $('.brandInHeader').text('ВяТИч');
                }
                if ($(window).width() <= '500'){
                    $('.timeCall').text('c '+{{START_WORK_DAYSHIFT}}+' до '+{{END_WORK_DAYSHIFT}}); 
                //$('.main_image img').attr('src',"{{ asset('img') }}/iron.jpg");
                } else {
                    $('.timeCall').text('c '+{{START_WORK_DAYSHIFT}}+':00 до '+{{END_WORK_DAYSHIFT}}+':00'); 
                }
            }
            firstImpressionChanger();

            /*Выделение кнопки выбора типа оплаты*/
            $('#stage4 input[name=type_of_payment]').change(function(){
                if ( !$("#stage4 label").hasClass("unhovered") ) {
                    $('#stage4 label').removeClass('button_pushed');
                    $(this).parent('label').addClass('button_pushed');
                }
            });

            /*скрытие формы заказа по клику*/
            $('#form_close').click(function(event){
                $('#wrap_order').css('display','none');
                $('#form_order_product').trigger('reset');
            });

            /*скрытие блока успешной отправки данных*/
            $('#close_alert').click(function(event){
                $('.success').css('display','none');
            });
            $('input').on("change keyup input click", function(){
                $(this).removeClass('red');
                $(this).removeClass('notCheckedConditions');
                $(this).removeClass('unchooz');
            });
            $('input[type=password]').keydown(function(event){
                if(event.keyCode===13) {
                    event.preventDefault();
                    if ($(this).val() != '') {
                        $('#stage5 .onlyNext').click();
                    }
                    return;
                }
            });

            $('#wrap_construct_order .forCaptchaBlock svg').click(function(){
                $('#wrap_construct_order .forCaptchaBlock img').click();
            });
            $('#stage1 input ,#stage2 input ,#stage3 input, #stage4 input[name=captcha]').keydown(function(event){ 
                if(event.keyCode===13) {
                    var val = $(this).val();
                    if($(this).attr('name') == 'phone') {
                        if (val.match(/[0-9]/g).length <11) {
                            $(this).val('');
                        }
                    }
                    event.preventDefault();
                    $(this).parents('.stageOfOrder').children('.next').click();
                    return;
                }
            });
            $('input[name=conditions]').on("change keyup input click", function(){
                $('.checkConditions').removeClass('notCheckedConditions');
            });
            $('input[name=zone]').on("change keyup input click", function(){
                $('.zonesBlock').removeClass('unchoosenZonePopup');
            });
            $('input[type=radio]').on("change keyup input click", function(){
                $('.typeSend').removeClass('red');
            });

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
            var response = searchInCart(idKnife); //response = [bool, boolSec]; есть в корзине / не доступен
            /*Скролы*/
            if(device.desktop()) {
                $('.wrap_slider').niceScroll('.cart_slider',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false}); 
                $('#aboutPartScrollable').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
            } else {
                $('body').addClass('mobileBody');
            }
            /*Вывод картинки на весь экран*/
            $('.main_for_product_img').click(function(){
                $('#wrap_for_product').css('display','block');
                $('#wrap').css('display','none');
                var src =$('.main_product_img').attr('src');
                $('#window').attr('src',src);
                imageNumber = $('.main_for_product_img img').attr('data-number');
                hideMainScroll();
            });
            /*Отработка действия клавиши esc*/
            window.addEventListener("keydown", function(event){
                if(event.keyCode===27) {
                    doEsc();
                }
                if($('#wrap_for_product').is(':visible')) {
                    if(event.keyCode===37) {
                        $('.prevMainImage').click();
                    }
                    if(event.keyCode===39) {
                        $('.nextMainImage').click();
                    }
                }
            });
            /*Аналогичные действия esc-ейпу от клика вне*/
            $(document).mouseup(function (e) {
                if ($('#aboutPartWrap').is(':visible')){
                    div = $('#aboutPart');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        closeAboutPart();
                        if ($('#wrap_construct_order').is(':visible')) {
                            hideMainScroll();
                        }
                    }
                    return;
                } 
                if ($('#success_message').is(':visible')){
                    div = $('#alert_message');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        $('#close_alert').click();
                    }
                }
                if ($('#wrap_for_product').is(':visible')){
                    div=$('#window');
                    div1=$('.prevMainImage');
                    div2=$('.nextMainImage');
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !div1.is(e.target) && div1.has(e.target).length === 0 && !div2.is(e.target) && div2.has(e.target).length === 0) {
                        closeMainImg();
                    }
                } else if ($('#success_after_add_to_cart').is(':visible')){
                    div=$('#alert_after_add_to_cart');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        $('#return_to_buy').click();
                    }
                } else if ($('#wrap_cart').is(':visible') && !$('#wrap_construct_order').is(':visible')){
                    div=$('#cart');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                         closeCart();
                    }
                } else if ($('#wrap_construct_order').is(':visible') && $('#stage5').is(':hidden')){
                    div=$('#way_to_buy');
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#stage4 .next').hasClass('button_pushed')) {
                        @if(!Session::has('userId'))closeConstructOrderConfirmation(); @else closeConstructOrder(); @endif 
                    }
                }
            });

            $(document).mousemove(function(e){
                if (!$('#success_after_add_to_cart').is(':visible')) {
                    place_image=$('.main_product_img').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x < $('.main_product_img').width() &&
                        y < $('.main_product_img').height() &&
                        x > 0 &&
                        y > 0
                    ) {
                        $('.enlarge').css('display','block');
                    } else {
                        $('.enlarge').css('display','none');
                    }
                } 
                if ($('#aboutPartWrap').is(':visible')){
                   
                    place_image=$('#aboutPart').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#aboutPart').outerWidth() ||
                        y > $('#aboutPart').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('#aboutPart').addClass('hoveredClose');
                    } else {
                        $('#aboutPart').removeClass('hoveredClose');
                    }
                }
                if ($('#wrap_for_product').is(':visible')) {
                    place_image=$('#window').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    div1=$('.prevMainImage');
                    div2=$('.nextMainImage');
                    if ((
                        x > $('#window').outerWidth() ||
                        y > $('#window').outerHeight() ||
                        x < 0 ||
                        y < 0 ) && !div1.is(e.target) && div1.has(e.target).length === 0 && !div2.is(e.target) && div2.has(e.target).length === 0
                    ) {
                            $('#wrap_for_product').addClass('hoveredClose');
                    } else {
                        $('#wrap_for_product').removeClass('hoveredClose');
                    }
                } else if ($('#wrap_cart').is(':visible') && $('#wrap_construct_order').is(':hidden')) {
                    place_image=$('#cart').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#cart').outerWidth() ||
                        y > $('#cart').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('.close_cart').addClass('hoveredClose');
                    } else {
                        $('.close_cart').removeClass('hoveredClose');
                    }
                } else if ($('#wrap_construct_order').is(':visible') && $('#aboutPartWrap').is(':hidden')) {
                    place_image=$('#way_to_buy').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#way_to_buy').outerWidth() ||
                        y > $('#way_to_buy').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('.close_order_construct').addClass('hoveredClose');
                    } else {
                        $('.close_order_construct').removeClass('hoveredClose');
                    }
                }
            });
            /*маска для поля телефон и почтового индекса*/
                $(".phone").mask("+7(999) 999-99-99",{placeholder:"_"});
                $(" input[name='mailIndex']").mask("999999",{placeholder:"_"});
            /*При фокусе на ипуте изменение положения для места под вирт. клавиатуру*/
            $('input').on('focus',function(){
                if(!device.desktop()) {
                    document.getElementById('wrapForScrollOrder').scrollTop = 0;
                    var topPos=$(this).offset().top; 
                    var topFormPos=$('#form_construct_order').offset().top;
                    document.getElementById('wrapForScrollOrder').scrollTop = topPos-topFormPos+60;
                }
            });
            function resizeImgScreen(){
                if ((($(window).width()) / $(window).height())> 1.63 && $('.right_product').css('float') == 'none') {
                    $('.main_product_img').css('height', $(window).height() - 70);
                    $('.main_product_img').css('width', 'auto');
                } else if ($('.right_product').css('float') == 'none') {
                    $('.main_product_img').css('height','auto');
                    $('.main_product_img').css('width','100%');
                } else {
                    $('.main_product_img').css('height','auto');
                    $('.main_product_img').css('width','100%');
                }
            }
            resizeImgScreen();
            $('.wrapLogin').click(function(){
                window.location.href = $('#toHome').attr('href');
            });
            /*Реакция на ресайзы*/
            $(window).resize(function() {
                var w = $(window).width();
                var h = $(window).height();
                sizeCartPopup();
                resizeCart();
                firstImpressionChanger();
                resizeImgScreen();
                setWidth();
                changePhoneFoldrers();
            });
            tmplNewChanges();
        });
</script>
</html>

@endsection