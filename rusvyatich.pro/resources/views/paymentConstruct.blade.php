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
                <div class="orderPayBlock">
                    <div>
                        <span class="headerCondition">Детали заказа</span>
                    </div>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Сталь</span>
                            </dt>
                            <dd id="steel__{$item['id']}">
                                <div class="new-select-style-wpandyou" id="steel">
                                        @foreach ($steels as $steel)
                                                @if ($properties->id_typeOfSteel === $steel->id)
                                                    {{ $steel->name }}
                                                @endif
                                        @endforeach   
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Клинок</span>
                            </dt>
                            <dd id="length__{$item['id']}">
                                <div class="new-select-style-wpandyou" id="blade">
                                        @foreach ($blades as $blade)
                                                @if ($properties->id_typeOfBlade === $blade->id)
                                                    {{ $blade->name }}
                                                @endif
                                        @endforeach  
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Спуски</span>
                            </dt>
                            <dd id="length__{$item['id']}">
                                <div class="new-select-style-wpandyou" id="blade">
                                    {{$spusk}}
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Больстер</span>
                            </dt>
                            <dd id="width__{$item['id']}"> 
                                <div class="new-select-style-wpandyou" id="bolster">
                                        @foreach ($bolsters as $bolster)
                                            @if ($properties->id_typeOfBolster === $bolster->id)
                                                    {{ $bolster->name }}
                                            @endif
                                        @endforeach  
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Ручка</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">   
                                <div class="new-select-style-wpandyou">
                                        @foreach ($handles as $handle)
                                                @if ($properties->id_typeOfHandle === $handle->id)
                                                {{ $handle->name }}

                                                @endif        
                                        @endforeach        
                          
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Материал ручки</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">  
                                <div class="new-select-style-wpandyou">
                                        @foreach ($handleMaterials as $handleMaterial)
                                                @if ($properties->id_typeOfHandleMaterial === $handleMaterial->id)
                                                {{ $handleMaterial->name }}   
                                                @endif
                                        @endforeach   
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Длина клинка</span>
                            </dt>
                            <dd id="blade_length">{{ $properties->blade_length }} мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Высота клинка</span>
                            </dt>
                            <dd id="blade_height">{{ $properties->blade_height }} мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Толщина обуха</span>
                            </dt>
                            <dd id="butt_width">{{ $properties->butt_width }} мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Длина ручки</span>
                            </dt>
                            <dd id="handle_length">{{ $properties->handle_length }} мм</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted withoutDots">
                                <span>Дополнительно:</span>
                            </dt>
                            <dd id="length__{$item['id']}">
                            </dd>
                        </dl>
                        <ul class="list">
                        @foreach($additions as $addition)
                            <li>{{$addition->name}}</li>
                        @endforeach
                        </ul>
                        @if($order->customer_note)
                        <dl class="dl-inline clearfix">
                            <dt class="">
                                <span>Описание:</span>
                            </dt>
                            <dd></dd>
                        </dl> 
                        <p style="width: 100%; word-wrap: break-word;">{{ $order->customer_note }}</p>
                        @endif
                    </div>
                        <div id="costSum">Общая цена: {{$order->sum_of_order}} + {{DELIVERY_COST}} (доставка) = {{$order->sum_of_order + DELIVERY_COST}} р. @if(($order->id_payment === PAY_PERSENT) && ($order->money_payed === 0))<br><span class="aboutRestPay">Внос залога: {{$order->sum_of_order*PERSENT/100}} р.</span>@endif @if($order->money_payed > 0)<br> <span class="aboutRestPay">Осталось оплатить: {{$order->sum_of_order + DELIVERY_COST - $order->money_payed}} р. </span>@endif</div>
                        <div class="test_svg clearfix">
                            <svg id="svg" xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 1800 487">
                                <g id="bladePaths" transform="translate(600 48)">
                                    <g id="blade_wrap_svg">
                                        <path id="blade_svg" vector-effect="non-scaling-stroke" stroke ="#000000" stroke-width="0.65" fill="#000000"></path>
                                    </g>
                                    <path id="fixBlade" vector-effect="non-scaling-stroke"  fill="#f2efef" d="M645,117 L630,117 Q597,117 591,139 L591, 300 L645,300z"></path>
                                    <g id="bolster_wrap_svg">
                                        <path id="bolster_svg" stroke ="#000000" stroke-width="0.65" vector-effect="non-scaling-stroke" data-width="5"  fill="#000000"></path>
                                    </g> 
                                    <g id="handle_wrap_svg">
                                        <path class="" id="handle_svg" stroke ="#000000" stroke-width="0.65" vector-effect="non-scaling-stroke"  fill="#000000"></path>
                                        <!--<circle class="klepka" stroke ="#000000" vector-effect="non-scaling-stroke"  fill="#000000" r="10" cx="980" cy="100"></circle>
                                        <circle class="klepka" stroke ="#000000" vector-effect="non-scaling-stroke"  fill="#000000" r="10" cx="1130" cy="100"></circle>
                                        <circle class="klepka" stroke ="#000000" vector-effect="non-scaling-stroke"  fill="#000000" r="10" cx="1280" cy="100"></circle>-->
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
                                    <pattern id="patternHandle" viewbox="0 0 1800 487"  width="868" height="408"  patternUnits="userSpaceOnUse" x="-285" y="-55">
                                        <image id="handleImg" href="" width="830" height="250" />
                                    </pattern>
                                    <pattern id="patternBolster" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse"  x="-63" y="-58" width="680" height="300">
                                        <image id="bolsterImg" width="930" height="400" href=""/>
                                    </pattern>
                                    <pattern id="patternSteel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse" x="-495" y="-12" width="815" height="285">
                                        <image id="steelImg" width="720" height="310" href=""/>
                                    </pattern>
                                    <!--<pattern id="patternKlepka" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse" x="-495" y="-12" width="815" height="285">
                                        <image id="klepkaImg" width="720" height="225" href=""/>
                                    </pattern>-->
                                </defs>
                            </svg>
                            <div id="note">
                                Обратите внимание что размер см на линейке может не совпадать с фактическим размером см на вашем экране
                            </div>
                        </div>
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
                            <dd >@if($order->patronymic) {{ $order->patronymic }} @else <span class="absenseAboutUser">.</span> @endif</dd>
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
                            @if($order->id_payment !== PAY_PERSENT)
                            <input type="hidden" name="ik_am" value="{{$order->sum_of_order + DELIVERY_COST - $order->money_payed}}" />
                            @elseif($order->money_payed == 0)
                            <input type="hidden" name="ik_am" value="{{($order->sum_of_order)*PERSENT/100}}" />
                            @else
                            <input type="hidden" name="ik_am" value="{{$order->sum_of_order + DELIVERY_COST - $order->money_payed}}"/>
                            @endif
                            <input type="hidden" name="ik_x_typeorder" value="{{$typeOrder}}" />
                            <input type="hidden" name="ik_x_idorder" value="{{$order->id}}" />
                            <input type="hidden" name="ik_cur" value="RUB" />
                            <input type="hidden" name="ik_desc" value="Оплата заказа №{{$order->id}}" />
                                <input id="payButton" class="button button2" type="submit" value="оплатить">
                        </form>
                </div>
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
<script>
    // "global" vars
    var patternPath = "{{ asset('img/patternsConstruct') }}/";
</script>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
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
            var flagChange=0; //флаг для изменения viewBox
            @if ($properties->blade_length >160) 
            flagChange = 1;
            @endif
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
            /*Первоначальная установка svg view ножа*/
            if($(window).width()<=1370 && flagChange == 0){
                    shape = document.getElementById("svg");
                    shape.setAttribute("viewBox", "0 0 1200 335"); 
                    $('#bladePaths').attr('transform','translate(0 48)');
                    $('#lineikaLines').attr('transform','translate(0 10)');
            } 
            /*Реакция на ресайзы*/
            $(window).resize(function() {
                var w = $(window).width();
                var h = $(window).height();
                shape = document.getElementById("svg");
                if(w<=1370 && flagChange===0){
                    shape.setAttribute("viewBox", "0 0 1200 335");
                    $('#bladePaths').attr('transform','translate(0 48)');
                    $('#lineikaLines').attr('transform','translate(0 10)'); 
                }else if(w<=1370 && flagChange===1){
                    shape.setAttribute("viewBox", "0 0 1800 487");
                    $('#bladePaths').attr('transform','translate(600 70)');
                    $('#lineikaLines').attr('transform','translate(0 162)'); 
                }else if(w>1370 ){
                    shape.setAttribute("viewBox", "0 0 1800 487");
                    $('#bladePaths').attr('transform','translate(600 70)');
                    $('#lineikaLines').attr('transform','translate(0 162)');
                }
            });
        getPathSec({{ $properties->id_typeOfBlade }}, 1);
        getPathSec({{ $properties->id_typeOfBolster }}, 2);
        getPathSec({{ $properties->id_typeOfHandle }}, 3);
        setTextureSec({{ $properties->id_typeOfSteel }}, 1);
        setTextureSec({{ $properties->id_typeOfHandleMaterial }}, 2);
        /*Изменение svg высоты клинка*/
        length = parseFloat($('#blade_length').text())*4;
        height = parseFloat($('#blade_height').text())*4;
        driveKnife(length,height,320,80);
        $('#showOrderButton').css('right', '0');
    });
</script>
@endsection