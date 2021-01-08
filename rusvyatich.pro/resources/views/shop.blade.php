@extends('layouts.shop')

@section('content')

<body class="shop">
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
	<div class="container clearfix">
		<div class="prePage">
			<ul class="breadcrumb">
				<li><a href="/">Главная</a></li>
				<li><a href="#">Магазин ножей</a></li>
			</ul>
		</div>
		<h1 class="captionPage">Магазин ножей</h1>
		<div class="addGradient"></div>
		<section class="takeByParameters clearfix">
		<div class="loadBlock">
			<div class="circleBlock">
				<div class="circle"></div>
			    <div class="circle"></div>
			    <div class="circle"></div>
			    <div class="circle"></div>
			</div>
		</div>
		<form id="searchByParameters">
			<section class="parameter">
				<span class="parameterCaption">Сталь</span>
				<ul>
					@foreach($steels as $steel)
						<li class="shopSteels">
							<input class="steelInput" type="checkbox" name="steel{{$steel->id}}" id="steel{{$steel->id}}" value="{{$steel->id}}" @if(in_array($steel->id, $steelsSet)) checked @endif>
							<label class="checkConditions" for="steel{{$steel->id}}"></label>
							<span>{{$steel->name}}</span>
						</li>
					@endforeach
					<button type="button" class="eraseSteel button" onclick="eraseSteels();">сбросить стали</button>
				</ul>
			</section>
			<section class="parameter parameterSizes">
				<span class="parameterCaption">Размеры</span>
				<section class="parameterSizesName">
					<span class="parameterCaptionLevel2">Длина</span>
					<label>
						<span>от</span>
						<input type="text" autocomplete="off" id="minBladeLength" name="minBladeLength" value="{{ $minBladeLengthSet }}"/>
					</label>
					<label>
						<span>до</span>
						<input type="text" autocomplete="off" id="maxBladeLength" name="maxBladeLength" value="{{ $maxBladeLengthSet }}"/>
					</label>
					<span>мм</span>
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderBladeLength"></div>
				</section>
				<section class="parameterSizesName">
					<span class="parameterCaptionLevel2">Ширина</span>
					<label>
						<span>от</span>
						<input type="text" autocomplete="off" id="minBladeWidth" name="minBladeWidth" value="{{ $minBladeWidthSet }}"/>
					</label>
					<label>
						<span>до</span>
						<input type="text" autocomplete="off" id="maxBladeWidth" name="maxBladeWidth" value="{{ $maxBladeWidthSet }}"/>
					</label>
					<span>мм</span>
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderBladeWidth"></div>
				</section>
				<section class="parameterSizesName">
					<span class="parameterCaptionLevel2">Обух</span>
					<label>
						<span>от</span>
						<input type="text" autocomplete="off" id="minBladeButt" name="minBladeButt" value="{{ $minBladeButtSet }}"/>
					</label>
					<label>
						<span>до</span>
						<input type="text" autocomplete="off" id="maxBladeButt" name="maxBladeButt" value="{{ $maxBladeButtSet }}"/>
					</label>
					<span>мм</span>
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderBladeButt"></div>
				</section>
			</section>
			<section class="parameter parameterCost">
				<span class="parameterCaption">Стоимость</span>
				<section class="parameterCostName">
					<span class="parameterCaptionLevel2">Цена</span>
					<label>
						<span>от</span>
						<input type="text" autocomplete="off" id="minCost" name="minCost" value="{{ $minCostSet }}"/>
						</label>
					<label>
					<label>
						<span>до</span>
						<input type="text" autocomplete="off" id="maxCost" name="maxCost" value="{{ $maxCostSet }}"/>
					</label>
					<span> р.</span>
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderCost"></div>
				</section>
				<section class="parameterCostName">
					<div>	
						<label>
		                    <input id="up" type="radio" name="sortCost" value="1" @if($costSortSet == 1) checked @endif>
							<label class="Radio" for="up"></label>
		                    <span>По возрастанию</span>
	                    </label>
					</div>
                    <div>
						<label>
		                    <input id="down" type="radio" name="sortCost" value="2" @if($costSortSet == 2) checked @endif>
		                    <label class="Radio" for="down"></label>
		                    <span>По убыванию</span>
	                    </label>
                    </div>
				</section>
			</section>
		</form>
		</section>
        <div id="productsBox" class="product_block clearfix">
			<script id="productsTemplate" type="text/x-jquery-tmpl">
	            <div class="product">
	                <img src="{{ asset('img/imgStorageMin') }}/${image}" width="340" height="190" alt="Нож ${name} (кузница Вятич)" title="Нож ${name} (кузница Вятич)">
	                <div class="product_description">
	                	<span class="nameKnife">${name} (${steel})</span>
	                    <dl class="dl-inline clearfix">
	                        <dt class="dt-dotted">
	                            <span>Сталь</span>
	                        </dt>
	                        <dd>${steel}</dd>
	                    </dl>
	                    <dl class="dl-inline clearfix">
	                        <dt class="dt-dotted">
	                            <span>Длина клинка</span>
	                        </dt>
	                        <dd>${blade_length} мм</dd>
	                    </dl>
	                    <dl class="dl-inline clearfix">
	                        <dt class="dt-dotted">
	                            <span>Ширина клинка</span>
	                        </dt>
	                        <dd>${blade_width} мм</dd>
	                    </dl>
	                    <dl class="dl-inline clearfix">
	                        <dt class="dt-dotted">
	                            <span>Толщина обуха</span>
	                        </dt>
	                        <dd>${blade_thickness} мм</dd>
	                    </dl>
	                   <span class="cost">Цена: ${price} р.</span>
	                </div>
	                @{{if source == "individual"}}
	                	<a class="abutton button read_more" href="/shop/knife${id}">Подробнее</a>
	                @{{else}}
                    	<a class="abutton button read_more" href="/shop/serialKnife${id}">Подробнее</a>
                    @{{/if}}
	            </div>          
	        </script>
        </div>
	        <span class='emptyProducts unfindKnife' style="display: none;">Нож не найден</span>
	</div>
    @include('layouts.orderPopup')
</main>
@include('handleOldToken')    
@include('layouts.footerBig')
@include('layouts.cart')
</body>
<script>
    var idKnife = false;
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
        	getKnifesByParameters();
        	checkCart();
            @include('scripts.closeMessages')

        	$("#sliderCost").slider({
				min: {{ $minCost }},
				max: {{ $maxCost }},
				values: [{{ $minCostSet }},{{ $maxCostSet }}],
				range: true,
				stop: function(event, ui) {
					$("input#minCost").val($("#sliderCost").slider("values",0));
					$("input#maxCost").val($("#sliderCost").slider("values",1));
					getKnifesByParameters();
			    },
			    slide: function(event, ui){
					$("input#minCost").val($("#sliderCost").slider("values",0));
					$("input#maxCost").val($("#sliderCost").slider("values",1));
			    }
            });
            $("input#minCost").change(function(){
				var value1=$("input#minCost").val();
				var value2=$("input#maxCost").val();

				if (value1 < {{ $minCost }}) { value1 = {{ $minCost }}; $("input#minCost").val({{$minCost}})}

			    if(parseInt(value1) > parseInt(value2)){
					value1 = value2;
					$("input#minCost").val(value1);
				}
				$("#sliderCost").slider("values",0,value1);	
			});

				
			$("input#maxCost").change(function(){
				var value1=$("input#minCost").val();
				var value2=$("input#maxCost").val();
				
				if (value2 > {{ $maxCost }}) { value2 = {{ $maxCost }}; $("input#maxCost").val({{ $maxCost }})}

				if(parseInt(value1) > parseInt(value2)){
					value2 = value1;
					$("input#maxCost").val(value2);
				}
				$("#sliderCost").slider("values",1,value2);
			});

			/*Слайдер на длину клинка*/
        	$("#sliderBladeLength").slider({
				min: {{ $minBladeLength }},
				max: {{ $maxBladeLength }},
				values: [{{ $minBladeLengthSet }},{{ $maxBladeLengthSet }}],
				range: true,
				stop: function(event, ui) {
					$("input#minBladeLength").val($("#sliderBladeLength").slider("values",0));
					$("input#maxBladeLength").val($("#sliderBladeLength").slider("values",1));
					getKnifesByParameters();
			    },
			    slide: function(event, ui){
					$("input#minBladeLength").val($("#sliderBladeLength").slider("values",0));
					$("input#maxBladeLength").val($("#sliderBladeLength").slider("values",1));
			    }
            });

            $("input#minBladeLength").change(function(){
				var value1=$("input#minBladeLength").val();
				var value2=$("input#maxBladeLength").val();

				if (value1 < {{ $minBladeLength }}) { value1 = {{ $minBladeLength }}; $("input#minBladeLength").val({{ $minBladeLength }})}

			    if(parseInt(value1) > parseInt(value2)){
					value1 = value2;
					$("input#minBladeLength").val(value1);
				}
				$("#sliderBladeLength").slider("values",0,value1);	
			});

			$("input#maxBladeLength").change(function(){
				var value1=$("input#minBladeLength").val();
				var value2=$("input#maxBladeLength").val();
				
				if (value2 > {{ $maxBladeLength }}) { value2 = {{ $maxBladeLength }}; $("input#maxBladeLength").val({{ $maxBladeLength }})}

				if(parseInt(value1) > parseInt(value2)){
					value2 = value1;
					$("input#maxBladeLength").val(value2);
				}
				$("#sliderBladeLength").slider("values",1,value2);
			});



			/*Слайдер на ширину клинка*/
        	$("#sliderBladeWidth").slider({
				min: {{ $minBladeWidth }},
				max: {{ $maxBladeWidth }},
				values: [{{ $minBladeWidthSet }},{{ $maxBladeWidthSet }}],
				range: true,
				stop: function(event, ui) {
					$("input#minBladeWidth").val($("#sliderBladeWidth").slider("values",0));
					$("input#maxBladeWidth").val($("#sliderBladeWidth").slider("values",1));
					getKnifesByParameters();
			    },
			    slide: function(event, ui){
					$("input#minBladeWidth").val($("#sliderBladeWidth").slider("values",0));
					$("input#maxBladeWidth").val($("#sliderBladeWidth").slider("values",1));
			    }
            });

            $("input#minBladeWidth").change(function(){
				var value1=$("input#minBladeWidth").val();
				var value2=$("input#maxBladeWidth").val();

				if (value1 < {{ $minBladeWidth }}) { value1 = {{ $minBladeWidth }}; $("input#minBladeWidth").val({{ $minBladeWidth }})}

			    if(parseInt(value1) > parseInt(value2)){
					value1 = value2;
					$("input#minBladeWidth").val(value1);
				}
				$("#sliderBladeWidth").slider("values",0,value1);	
			});

			$("input#maxBladeWidth").change(function(){
				var value1=$("input#minBladeWidth").val();
				var value2=$("input#maxBladeWidth").val();
				
				if (value2 > {{ $maxBladeWidth }}) { value2 = {{ $maxBladeWidth }}; $("input#maxBladeWidth").val({{ $maxBladeWidth }})}

				if(parseInt(value1) > parseInt(value2)){
					value2 = value1;
					$("input#maxBladeWidth").val(value2);
				}
				$("#sliderBladeWidth").slider("values",1,value2);
			});



			/*Слайдер на толщину обуха*/
        	$("#sliderBladeButt").slider({
				min: {{ $minBladeButt }},
				max: {{ $maxBladeButt }},
				values: [{{ $minBladeButtSet }},{{ $maxBladeButtSet }}],
				range: true,
                step : 0.1,//Шаг, с которым будет двигаться ползунок
				stop: function(event, ui) {
					$("input#minBladeButt").val($("#sliderBladeButt").slider("values",0).toFixed(1));
					$("input#maxBladeButt").val($("#sliderBladeButt").slider("values",1).toFixed(1));
					getKnifesByParameters();
			    },
			    slide: function(event, ui){
					$("input#minBladeButt").val($("#sliderBladeButt").slider("values",0).toFixed(1));
					$("input#maxBladeButt").val($("#sliderBladeButt").slider("values",1).toFixed(1));
			    }
            });

            $("input#minBladeButt").change(function(){
				var value1=$("input#minBladeButt").val();
				var value2=$("input#maxBladeButt").val();
				if (value1 < {{ $minBladeButt }}) { value1 = {{ $minBladeButt }}; $("input#minBladeButt").val({{ $minBladeButt }})}

			    if(parseFloat(value1) > parseFloat(value2)){
					value1 = value2;
					$("input#minBladeButt").val(value1);
				}
				$("#sliderBladeButt").slider("values",0,value1);	
			});

			$("input#maxBladeButt").change(function(){
				var value1=$("input#minBladeButt").val();
				var value2=$("input#maxBladeButt").val();
				if (value2 > {{ $maxBladeButt }}) { value2 = {{ $maxBladeButt }}; $("input#maxBladeButt").val({{ $maxBladeButt }})}

				if(parseFloat(value1) > parseFloat(value2)){
					value2 = value1;
					$("input#maxBladeButt").val(value2);
				}
				$("#sliderBladeButt").slider("values",1,value2);
			});
			$('#searchByParameters input').change(function(){
				getKnifesByParameters();
			});
			$('#searchByParameters input[type=checkbox] ,#searchByParameters input[type=radio]').change(function(){
				getKnifesByParameters();
			});
            $("input#minBladeButt").blur(function(){
            	$("input#minBladeButt").val(parseFloat($("input#minBladeButt").val()).toFixed(1));
            });

			$("input#maxBladeButt").blur(function(){
            	$("input#maxBladeButt").val(parseFloat($("input#maxBladeButt").val()).toFixed(1));

			});
            /*Отработка действия клавиши esc*/
            window.addEventListener("keydown", function(event){
                if(event.keyCode===27) {
                    doEsc();
                }
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
            $(document).mousemove(function(e){
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
            if ($('#wrap_cart').is(':visible') && $('#wrap_construct_order').is(':hidden')) {
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
                if ($('#wrap_cart').is(':visible') && !$('#wrap_construct_order').is(':visible')){
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
            /*Скролы*/
            if(device.desktop()) {
                $('.wrap_slider').niceScroll('.cart_slider',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false}); 
                    $('#aboutPartScrollable').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
            } else {
                $('body').addClass('mobileBody');
            }

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
            });
            tmplNewChanges();
        });
</script>
</html>

@endsection