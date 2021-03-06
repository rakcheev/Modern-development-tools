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
                    <div id="bodyKnifeProperties" class="bodyKnifeProperties">
                        <dl class="dl-inline clearfix customer-dl-inline">
                            <dt class="dt-dotted withoutDots">
                                <span>Вид ножа:</span>
                            </dt>
                            <dd >
                            </dd>
                        </dl>
                        <div class="test_svg clearfix">
                            <svg id="svg" xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 1800 487">
                                <g id="bladePaths" transform="translate(600 48)">
                                    <g id="blade_wrap_svg">
                                        <path id="blade_svg" vector-effect="non-scaling-stroke" stroke ="#000000" stroke-width="0.75" fill="#000000"></path>
                                    </g>
                                    <path id="fixBlade" vector-effect="non-scaling-stroke"  fill="#ffffff" d="M645,119 L630,119 Q597,119 591,139 L591, 300 L645,300z"></path>
                                    <g id="bolster_wrap_svg">
                                        <path id="bolster_svg" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke" data-width="5"  fill="#000000"></path>
                                    </g> 
                                    <g id="handle_wrap_svg">
                                        <path class="" id="handle_svg" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke"  fill="#000000"></path>
                                        <path class="klepka" id="klepka" d="." stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke"  fill="url(#patternKlepka)"></path>
                                    </g>
                                </g>
                                <g id="lineikaLines" transform="translate(0 162)">
                                {{ $c = 0 }}
                                @for ($i = 0; $i < 2400; $i+=40)
                                    <line x1="{{ $i }}" y1="325" x2="{{ $i }}" y2="305" stroke="#000000" stroke-width="2"/>
                                    @for ($j = 0; $j < 10; $j++)
                                        <line x1="{{ $i + $j * 4 }}" y1="325" x2="{{ $i + $j * 4 }}" y2="315" stroke="#000000" stroke-width="1"/>
                                    @endfor
                                    <text x="{{ $i }}" y="300">{{ $c }}</text>
                                    {{ $c++ }}
                                @endfor
                                </g>
                                <defs>
                                    <pattern id="patternHandle" viewbox="0 0 1800 487"  width="867.5" height="408"  patternUnits="userSpaceOnUse" x="-285" y="-55">
                                        <image id="handleImg" href="" xlink:href="" width="830" height="250" />
                                    </pattern>
                                    <pattern id="patternBolster" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse"  x="-63" y="-58" width="680" height="300">
                                        <image id="bolsterImg" width="930" height="400" href="" xlink:href="" />
                                    </pattern>
                                    <pattern id="patternSteel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse" x="-495" y="-27" width="815" height="285">
                                        <image id="steelImg" width="720" height="310" href="" xlink:href="" />
                                    </pattern>
                                    <pattern id="patternKlepka" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"  width="867.5" height="408"  patternUnits="userSpaceOnUse" x="-285" y="-55">>
                                        <image id="klepkaImg" width="830" height="250" href="{{ asset('img/patternsConstruct') }}/klepka.jpg?{{VERSION}}" xlink:href="{{ asset('img/patternsConstruct') }}/klepka.jpg?{{VERSION}}"/>
                                    </pattern>
                                </defs>
                            </svg>
                        </div>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Сталь</span>
                            </dt>
                            <dd>@foreach($steels as $steel)@if ($properties->id_typeOfSteel === $steel->id){{ $steel->name }}@endif @endforeach    
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Клинок</span>
                            </dt>
                            <dd>@foreach($blades as $blade) @if ($properties->id_typeOfBlade === $blade->id){{ $blade->name }}@endif @endforeach    
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Спуски</span>
                            </dt>
                            <dd id="spusk">
                                {{$spusk}}
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Больстер</span>
                            </dt>
                            <dd>@foreach($bolsters as $bolster) @if ($properties->id_typeOfBolster === $bolster->id){{ $bolster->name }}@endif @endforeach    
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Ручка</span>
                            </dt>
                            <dd>@foreach ($handles as $handle)@if ($properties->id_typeOfHandle === $handle->id){{ $handle->name }} @endif @endforeach  
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Материал ручки</span>
                            </dt>
                            <dd>@foreach ($handleMaterials as $handleMaterial) @if ($properties->id_typeOfHandleMaterial === $handleMaterial->id){{ $handleMaterial->name }}@endif @endforeach  
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Длина клинка</span>
                            </dt>
                            <dd><span id="blade_length">{{ $properties->blade_length }}</span> мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Высота клинка</span>
                            </dt>
                            <dd><span id="blade_height">{{ $properties->blade_height }}</span> мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Толщина обуха</span>
                            </dt>
                            <dd><span id="butt_width">{{ $properties->butt_width }}</span> мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Длина ручки</span>
                            </dt>
                            <dd><span id="handle_length">{{ $properties->handle_length }}</span> мм</dd>
                        </dl>
                        @if(!$additions->isEmpty())
                            <dl class="dl-inline clearfix customer-dl-inline">
                                <dt class="dt-dotted withoutDots">
                                    <span>Доп. на клинок:</span>
                                </dt>
                                <dd >
                                </dd>
                            </dl>
                            <ul class="list">
                                @foreach($additions as $addition)
                                    <li data-image="{{$addition->image}}" @if($addition->image) class="imageAddition" @endif>{{$addition->name}}</li>
                                @endforeach
                            </ul>
                        @endif
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Доставка</span>
                            </dt>
                            <dd><span>{{ $typeOfSend->name }}</span></dd>
                        </dl>
                        @if($order->customer_note)
                            <dl class="dl-inline clearfix customer-dl-inline">
                                <dt class="dt-dotted">
                                    <span>Описание</span>
                                </dt>
                                <dd id="handle_length">
                                    <div id="openNote" onclick="openNote();"></div>
                                    <div class="customerNote">{{ $order->customer_note }}</div>
                                </dd>
                            </dl>
                        @endif
                        <p class="customerDescription"><strong>Итого:</strong> @if(empty($order->sum_of_order)) — {{$order->sum_of_order}} @else {{$order->sum_of_order}} + {{$typeOfSend->price}} (доставка) = <strong>{{$order->sum_of_order + $typeOfSend->price}} р.</strong>@endif</p>
                            @if(!empty($order->sum_of_order) && ($order->id_payed === NOT_PAYED) && (($order->sum_of_order + $typeOfSend->price) > $order->money_payed)) <p class="customerDescription" style="font-weight: 500;">Осталось оплатить: {{$order->sum_of_order + $typeOfSend->price - $order->money_payed}} р.  </p>@elseif($order->id_payed === PAYED) <p class="customerDescription" style="font-weight: 500;">Оплачен</p> @endif
                    </div>
                    </div>
                </div>
        </div>
        <button id="svgWindow">
            <button id="close_svg" class="window_close" type="button" title="Закрыть"onclick="closeSvgWindow(); return false"></button>
        </button>
    </main>
    @include('handleOldToken')        
    <footer>
    </footer>
    <div id="flag900"></div>
    <div id="flag600"></div>
    <div id="flag400"></div>
    <div id="flag1200"></div>
</body>
<script>
    // "global" vars
    var patternPath = "{{ asset('img/patternsConstruct') }}/";
    var VERSION = {{VERSION}};
    @if($properties->id_typeOfBolster == 5) var fultang = 1; @else var fultang = 0; @endif
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

        /*svg на весь экран*/
        $(".test_svg").click(function () {
            $('.test_svg').clone().appendTo('#svgWindow');
            $('#svgWindow').css('display', 'block');
            $('#close_svg').removeClass('hoveredClose');
            $('#close_svg').css('display', 'block');
            $('#svgWindow #fixBlade').attr('fill', '#e8e8e8');
            $('#svgWindow svg').height($('#svgWindow svg').width()/3.58);
        });
        var flagChange=0; //флаг для изменения viewBox
        @if ($properties->blade_length >160) 
        flagChange = 1;
        @endif
           
        /*Первоначальная установка svg view ножа*/
        if(flagChange == 0){
            shape = document.getElementById("svg");
            shape.setAttribute("viewBox", "0 0 1200 335"); 
            $('#bladePaths').attr('transform','translate(0 48)');
            $('#lineikaLines').attr('transform','translate(0 10)');
        } 
        function viewResizer(){
            $('.customerOrderBlock').css('height', $(window).height());
            $('.events').css('height', $(window).height());
            $('.blockForShow').css('height', $(window).height());
            $('.knifeProperties').css('height', $(window).height());

            propertiesHeight  = $(window).height()-$('#knifePropertiesCaption').outerHeight()-5;
            $('#bodyKnifeProperties').css('height', propertiesHeight + 'px');
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
           // $('#bodyKnifeProperties').getNiceScroll().remove();
            if(device.desktop()) $('#bodyKnifeProperties').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});
        }
        viewResizer();
       // changeLettersHeight();
        blockForShowChanger();
        $(window).resize(function(){
            viewResizer();
            changeLettersHeight();
            blockForShowChanger();
            $('#svgWindow svg').height($('#svgWindow svg').width()/3.58);
        });
        $(".knifeProperties .new-select-style-wpandyou select").prop("disabled",true);
        getPath({{ $properties->id_typeOfBlade }}, 1);
        getPath({{ $properties->id_typeOfBolster }}, 2);
        getPath({{ $properties->id_typeOfHandle }}, 3);
        setTexture({{ $properties->id_typeOfSteel }}, 1);
        setTexture({{ $properties->id_typeOfHandleMaterial }}, 2);

        /*Изменение svg высоты клинка*/
        length = $('#blade_length').text()*4;
        height = $('#blade_height').text()*4;
        driveKnife(length,height,320,80);
        $('.letter').on('focus', function() {
            before = $(this).html();
        }).on('blur paste keydown keyup', function() { 
            if (before != $(this).html()) { $(this).trigger('change'); }
        });
        
        $('#showOrderButton').css('right', '0');
        
        $.each($(".adminNavigation a"),function(){
            if($(this).attr("href")=="#"){
                $(this).addClass('navPushed');
            }
        });
        @include('messageJs');
    });
</script>
@endsection