@extends('layouts.userhead')

@section('content')
    <body class="constructOrderBody">
        <main>
            @include('adminLeftColumn')
            @include('customerOrderBlock')
            @include('adminEvents')
            <div class="knifeProperties">
                <div id="knifePropertiesCaption" class="caption">
                    <span>Заказ</span>
                    @if($order->id_payed == PAYED) <span id="payed">оплачен</span> @endif 
                    <span id="timerr" class="timerPay"></span>
                </div>
                <div id="bodyKnifeProperties" class="bodyKnifeProperties cartProperties">
                    <ul id="cart_slider" class="cart_slider">
                        @foreach ($productsSerial as $product)
                            <li class="cartElement clearfix" onclick="toProductSerial({{ $product->id }})">
                                <span id="id">{{ $product->name }} ({{ $product->steel }})</span>
                                <img src="{{ asset('img/imgStorage') }}/{{ $product->image }}?{{VERSION}}" width="143px" height="80px">
                                <span class="productOrderCount">{{$product->countInOrder}} шт.</span>
                                <span class="costCartElement">{{ $product->price }} <span class="forBig">руб.</span><span class="forMin">р.</span></span>
                            </li>
                        @endforeach
                        @foreach ($products as $product)
                            <li class="cartElement clearfix" onclick="toProduct({{ $product->id }})">
                                <span id="id">{{ $product->name }} ({{ $product->steel }})</span>
                                <img src="{{ asset('img/imgStorage') }}/{{ $product->image }}?{{VERSION}}" width="143px" height="80px">
                                <span class="productOrderCount">1 шт.</span>
                                <span class="costCartElement">{{ $product->price }} <span class="forBig">руб.</span><span class="forMin">р.</span></span>
                            </li>
                        @endforeach
                    </ul>
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
            if(document.getElementById){timerr.innerHTML=time;}
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
            if (!($("#flag1200").is(':visible') ||$("#flag900").is(':visible') ||$("#flag600").is(':visible') ||$("#flag400").is(':visible'))) {
                $('.customerOrderBlock').css('height', $(window).height());
                $('.events').css('height', $(window).height());
                $('.knifeProperties').css('height', $(window).height());
                propertiesHeight  = $(window).height()-$('#knifePropertiesCaption').outerHeight()-5;
                $('#bodyKnifeProperties').css('height', propertiesHeight + 'px');
                if(device.desktop()) $('#bodyKnifeProperties').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});

                customerHeight = $(window).height()-$('.aboutCustomer').outerHeight()-5;
                $('#scrollPieceCustomer').css('height', customerHeight + 'px');
                if (device.desktop()) $('#scrollPieceCustomer').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});
                reorderElements([document.getElementsByClassName('customerOrderBlock')[0], document.getElementsByClassName('events')[0], document.getElementsByClassName('knifeProperties')[0]]);
            } else {
                $('.customerOrderBlock').css('height', 'auto');
                $('.events').css('height', $(window).height());
                $('.knifeProperties').css('height', 'auto');
                $('#bodyKnifeProperties').css('height', 'auto');
                $('#scrollPieceCustomer').getNiceScroll().remove();
                $('#bodyKnifeProperties').getNiceScroll().remove();
                reorderElements([document.getElementsByClassName('customerOrderBlock')[0], document.getElementsByClassName('knifeProperties')[0], document.getElementsByClassName('events')[0]]);
            }
            if ($('#flag400').is(':visible')) {
                $('#message').attr('placeholder', "Сообщение");
            } else {
                $('#message').attr('placeholder', "Введите сообщение");
            }
            if ($(window).width()<400) {
                $('#refuseButton').text('Отменить');
                $('#refuseButton').css('padding', '7px 10px');
                $('.sumInline dt span').text('Сумма');
            } else {
                $('#refuseButton').text('Отменить заказ');
                $('#refuseButton').css('padding', '7px 15px');
                $('.sumInline dt span').text('Сумма заказа');
            }
        }
        viewResizer();
        //changeLettersHeight();
        $(window).resize(function(){
            viewResizer();
            changeLettersHeight();
        });

        /*Шаблон только цифры */
        $("input[name='sumOrder']").on("change keyup input click", function(){
            if(this.value == "") return false; 
            if (this.value.match(/[^0-9]/g)){
                this.value = this.value.replace(/[^0-9]/g, '');
            } 
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