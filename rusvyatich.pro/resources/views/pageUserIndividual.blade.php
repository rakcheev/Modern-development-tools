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
                </div>
                <div id="bodyKnifeProperties" class="bodyKnifeProperties">
                    <div class="customerDescription">
                        <p>{{ $order->description }}</p>
                    </div>
                    @if (!empty($order->image))
                    <img class="individualImage" src="{{ asset('orderImages') }}/{{ $order->image }}" style="display:block; margin: 5px auto;">
                    @endif
                    @if($order->sum_of_order > 0)
                    <dl class="dl-inline clearfix">
                        <dt class="dt-dotted">
                            <span>Доставка</span>
                        </dt>
                        <dd><span>{{ $typeOfSend->name }}</span></dd>
                    </dl>
                    @endif
                    <p class="customerDescription"><strong>Итого:</strong> @if(empty($order->sum_of_order)) — {{$order->sum_of_order}} @else {{$order->sum_of_order}} + {{$typeOfSend->price}} (доставка) = <strong>{{$order->sum_of_order + $typeOfSend->price}} р.</strong>@endif</p>
                    @if(!empty($order->sum_of_order) && ($order->id_payed === NOT_PAYED) && (($order->sum_of_order + $typeOfSend->price) > $order->money_payed)) <p class="customerDescription" style="font-weight: 500;">Осталось оплатить: {{$order->sum_of_order + $typeOfSend->price - $order->money_payed}} р.  </p>@elseif(!empty($order->sum_of_order))<p class="customerDescription" style="font-weight: 500;">Оплачен</p> @endif
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
        viewResizer();
        //changeLettersHeight();
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