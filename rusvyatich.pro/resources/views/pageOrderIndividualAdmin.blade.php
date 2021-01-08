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
                    <span id="timer" class="timerPay"></span>
                </div>
                <div id="bodyKnifeProperties" class="bodyKnifeProperties">
                    <div class="customerDescription">
                        <p>{{ $order->description }}</p>
                    </div>
                    <img class="individualImage" src="{{ asset('orderImages') }}/{{ $order->image }}" style="display:block; margin: 5px auto;">
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
                $('.knifeProperties').css('height', $(window).height());
                $('#scrollPieceCustomer').getNiceScroll().remove();
                $('#bodyKnifeProperties').getNiceScroll().remove();
                propertiesHeight  = $(window).height()-$('#knifePropertiesCaption').outerHeight()-5;
                $('#bodyKnifeProperties').css('height', propertiesHeight + 'px');
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