@extends('layouts.site')

@section('content')
    <body>
        @include('layouts.brandHead')
        <main class="payBody"> 
            <div class="container">
                <div style="margin-top: 60px;">
                    <span class="headerCondition">Детали заказа</span>
                </div>
                <div id="bodyKnifeProperties" class="bodyKnifeProperties">
                    <div class="customerDescription">
                        <span style="font-weight:500; margin-top: 20px;">Описание:</span><p>{{ $order->description }}</p>
                    </div>
                    @if (!empty($order->image))
                    <img class="individualImage" src="{{ asset('orderImages') }}/{{ $order->image }}" style="display:block; margin: 5px auto; width: 100%;">
                    @endif
                </div>
                        <div id="costSum">Общая цена: {{$order->sum_of_order}} + {{DELIVERY_COST}} (доставка) = {{$order->sum_of_order + DELIVERY_COST}} р. @if(($order->sum_of_order > WITHOUT_PAY) && ($order->money_payed === 0))<br><span class="aboutRestPay">Внос залога: {{$order->sum_of_order*PERSENT/100}} р.</span>@endif @if($order->money_payed > 0)<br> <span class="aboutRestPay">Осталось оплатить: {{$order->sum_of_order + DELIVERY_COST - $order->money_payed}} р. </span>@endif</div>
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
                        @if($order->money_payed === 0)
                        <form method="post" action="https://sci.interkassa.com/" enctype="utf-8">
                            <input type="hidden" name="ik_co_id" value="5aab5e593d1eaffa678b4567" />
                            <input type="hidden" name="ik_pm_no" value="{{ ($typeOrder == CONSTRUCT_ORDER) ? 'construct' : (($typeOrder == CART_ORDER) ? 'cart' : 'image')}}_order_{{$order->id}}" />
                            <input type="hidden" name="ik_am" value="{{$order->sum_of_order*PERSENT/100}}" />
                            <input type="hidden" name="ik_x_typeorder" value="{{$typeOrder}}" />
                            <input type="hidden" name="ik_x_idorder" value="{{$order->id}}" />
                            <input type="hidden" name="ik_cur" value="RUB" />
                            <input type="hidden" name="ik_desc" value="Оплата заказа №{{$order->id}}" />
                                <input class="payButtonToKassa button button2" type="submit" value="внести залог в 50%">
                        </form>
                        @endif
                        <form method="post" action="https://sci.interkassa.com/" enctype="utf-8">
                            <input type="hidden" name="ik_co_id" value="5aab5e593d1eaffa678b4567" />
                            <input type="hidden" name="ik_pm_no" value="{{ ($typeOrder == CONSTRUCT_ORDER) ? 'construct' : (($typeOrder == CART_ORDER) ? 'cart' : 'image')}}_order_{{$order->id}}" />
                            <input type="hidden" name="ik_am" value="{{$order->sum_of_order + DELIVERY_COST - $order->money_payed}}" />
                            <input type="hidden" name="ik_x_typeorder" value="{{$typeOrder}}" />
                            <input type="hidden" name="ik_x_idorder" value="{{$order->id}}" />
                            <input type="hidden" name="ik_cur" value="RUB" />
                            <input type="hidden" name="ik_desc" value="Оплата заказа №{{$order->id}}" />
                                <input id="payButton" class="payButtonToKassa button button2" type="submit" value="оплатить полностью">
                        </form>
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
    });
</script>
@endsection