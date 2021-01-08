@extends('layouts.userhead')

@section('content')
    <body class="userBody">
        <main>
            @include('adminLeftColumn')
            @include('adminEvents')
            <div class="knifeProperties">
                <div id="knifePropertiesCaption" class="caption">
                    <div class="blockForShow">
                        <span id="showOrderButton" onclick="showOrderButton()">заказ</span>
                    </div>
                    <span>Заказ</span>
                    @if($order->id_payed == PAYED) <span id="payed">оплачен</span> @endif
                    <span id="timer" class="timerPay"></span>
                </div>
                <div id="bodyKnifeProperties" class="bodyKnifeProperties cartProperties">
                    <ul id="cart_slider" class="cart_slider">
                        @foreach ($productsSerial as $product)
                            <li class="cartElement clearfix">
                                <a href="/shop/serialKnife{{ $product->id }}" target="_blank" style="display: block; width: 100%; height: 100%;">
                                    <span id="id">{{ $product->name }} ({{ $product->steel }})</span>
                                    <img src="{{ asset('img/imgStorage') }}/{{ $product->image }}?{{VERSION}}" width="143px" height="80px">
                                    <span class="productOrderCount">{{$product->countInOrder}} шт.</span>
                                    <span class="costCartElement">{{ $product->price }} <span class="forBig">р.</span><span class="forMin">р.</span></span>
                                </a>
                            </li>
                        @endforeach
                        @foreach ($products as $product)
                            <li class="cartElement clearfix">
                                <a href="/shop/knife{{ $product->id }}" target="_blank" style="display: block; width: 100%; height: 100%;">
                                    <span id="id">{{ $product->name }} ({{ $product->steel }})</span>
                                    <img src="{{ asset('img/imgStorage') }}/{{ $product->image }}?{{VERSION}}" width="143px" height="80px">
                                    <span class="productOrderCount">1 шт.</span>
                                    <span class="costCartElement">{{ $product->price }} <span class="forBig">р.</span><span class="forMin">р.</span></span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <dl class="dl-inline clearfix">
                        <dt class="dt-dotted">
                            <span>Доставка</span>
                        </dt>
                        <dd><span>{{ $typeOfSend->name }}</span></dd>
                    </dl>
                    <p class="customerDescription"><strong>Итого:</strong> @if(empty($order->sum_of_order)) — {{$order->sum_of_order}} @else {{$order->sum_of_order}} + {{$typeOfSend->price}} (доставка) = <strong>{{$order->sum_of_order + $typeOfSend->price}} р.</strong>@endif</p>
                    @if(!empty($order->sum_of_order) && ($order->id_payed === NOT_PAYED) && (($order->sum_of_order + $typeOfSend->price) > $order->money_payed)) <p class="customerDescription" style="font-weight: 500;">Осталось оплатить: {{$order->sum_of_order + $typeOfSend->price - $order->money_payed}} р.  </p>@else <p class="customerDescription" style="font-weight: 500;">Оплачен</p> @endif
                </div>
            </div>
        </div>
    </main>
    @include('handleOldToken')        
    <footer>
    </footer>
    <div id="flag900"></div>
    <div id="flag600"></div>
    <div id="flag400"></div>
    <div id="flag1200"></div>
</body>
<script type="text/javascript">
    // "global" vars
    var pathImage = "{{ asset('img') }}/";
</script>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.tmpl.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.mousewheel.min.js') }}?{{VERSION}}" type="text/javascript"></script>
@include('socketRatcher')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
        tmplMessages({{ $order->id }}, {{ $typeOrder }});
    $().ready(function(){
        @include('scripts.closeMessages')
        @include('scripts.sameScripts')
        
        @if($seconds !== -10 && $minutes !== -10) 
        var sec={{$seconds}};
        var min={{$minutes}};

        function refresh()
        {
            sec--;
            if(sec==-01){sec=59; min=min-1;}
            else{min=min;}
            if(sec<=9){sec="0" + sec;}
            time=(min<=9 ? "0"+min : min) + ":" + sec;
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
        function viewResizer(){
            $('.customerOrderBlock').css('height', $(window).height());
            $('.events').css('height', $(window).height());
            $('.blockForShow').css('height', $(window).height());
            $('.knifeProperties').css('height', $(window).height());
            
            propertiesHeight  = $(window).height()-$('#knifePropertiesCaption').outerHeight()-5;
            $('#bodyKnifeProperties').css('height', propertiesHeight + 'px');
            if(device.desktop()) $('#bodyKnifeProperties').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});
            if ($('#flag400').is(':visible')) {
                $('#message').attr('placeholder', "Сообщение");
            } else {
                $('#message').attr('placeholder', "Введите сообщение");
            }
            if ($(window).width()<400) {
                $('#refuseButton').text('Отменить');
                $('#refuseButton').css('padding', '7px 8px');
                $('#payButton').css('padding', '7px 8px');
            } else {
                $('#refuseButton').text('Отменить заказ');
                $('#refuseButton').css('padding', '7px 15px');
                $('#payButton').css('padding', '7px 15px');
            }
        }
        if (device.desktop()) {
           $('.product_popup_description p').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:9, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, zindex: -10 });
        }

            /*Отработка действия клавиши esc*/
            window.addEventListener("keydown", function(event){
                if(event.keyCode===27) {
                    doEsc();
                }
            });
        viewResizer();
        $(window).resize(function(){
            viewResizer();
            blockForShowChanger();
            changeLettersHeight();
        });
        $.each($(".adminNavigation a"),function(){
            if($(this).attr("href")=="#"){
                $(this).addClass('navPushed');
            }
        });
        @include('messageJs');
    });
</script>
@endsection