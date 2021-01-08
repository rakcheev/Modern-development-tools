@extends('layouts.userhead')

@section('content')
    <body>    
        <main> 
            @include('adminLeftColumn')
            <div class="header clearfix">
                @if(Session::get('status') === MAIN_MASTER)
                    <a class="button addOrder" href="/home/orders">Мои заказы</a>
                @endif
                <h1 class="customerCaption">Заказы</h1>
                <ul class="typesOfOrderNav">
                    <li>
                        <a class="constructLink" href="{{ $constructOrdersLink }}">С конструктора<span id ="constructNew" class="newCount"></span></a>
                    </li>
                    <li>
                        <a class="individualLink" href="{{ $individualOrdersLink }}">Индивидуальные<span id ="imageNew" class="newCount"></span></a>
                    </li>
                    <li class="lastNav">
                        <a class="cartLink" href="{{ $cartOrdersLink}}">Из корзины<span id ="cartNew" class="newCount"></span></a>
                    </li>
                    <li class="">
                        <a class="cartLink" href="{{ $allOrdersLink}}">Все заказы<span id ="cartNew" class="newCount"></span></a>
                    </li>
                </ul>
            </div>
            <table id="falseHead" class='adminTable'>
                <tr>
                    <th scope="col" class="tblNumberOrderAdmin">№</th>
                    @if($typeId === 4) <th scope="col" class="tblNote">Тип заказа</th>@endif
                    <th scope="col" class="tblDate">Дата Создания</th>
                    <th scope="col" class="tblSum">Сумма</th>
                    <th scope="col" class="tblName">Имя</th>
                    <th scope="col" class="tblPhone">Телефон</th>
                    <th scope="col" class="tblMessage">Сообщение</th>
                    <th scope="col" class="tblStatus">Статус</th>
                    @if($typeId !== 4)<th scope="col" class="tblNote">Заметка</th>@endif
                </tr>
            </table>
            <div id="scrollTable" class="scrollTable">
                <table id="OrdersTable" class='adminTable'>
                    <tr class="captionsTableOrder">
                        <th scope="col" class="tblNumberOrderAdmin">№</th>
                        @if($typeId === 4) <th scope="col" class="tblNote">Тип заказа</th>@endif
                        <th scope="col" class="tblDate">Дата Создания</th>
                        <th scope="col" class="tblSum">Сумма</th>
                        <th scope="col" class="tblName">Имя</th>
                        <th scope="col" class="tblPhone">Телефон</th>
                        <th scope="col" class="tblMessage">Сообщение</th>
                        <th scope="col" class="tblStatus">Статус</th>
                        @if($typeId !== 4)<th scope="col" class="tblNote">Заметка</th>@endif
                    </tr> 
                    <tbody>
                        <script id="OrdersTemplate" type="text/x-jquery-tmpl">
                       
                            <tr class="OrderRecord  
                                @{{if id_viewed == 1}}
                                    unviewed
                                @{{/if}} 
                                @if(Session::get('status') === MAIN_MASTER)
                                    @{{if id_viewed_master == 1 }}
                                        unviewed
                                    @{{/if}}
                                @endif
                                @{{if newCount>0}}unreaded@{{/if}}"
                                @if($typeId !== 4) onclick="window.location.href=window.location.toString()+ '/${id}'"@else
                                onclick="@{{if id_type_order == 1}} window.location.href='constructOrders'+ '/${id}'@{{/if}} @{{if id_type_order == 2}} window.location.href='individualOrders'+ '/${id}'@{{/if}} @{{if id_type_order == 3}} window.location.href='cartOrders'+ '/${id}'@{{/if}}"
                                @endif>

                                <td aria-label="№"> 
                                    <div class="">@{{if id_type_order === 2}}i @{{/if}} ${id}</div>
                                </td>
                                @if($typeId === 4)
                                    <td aria-label="Тип">
                                        <div class="purposeNote">${orderType}</div>
                                    </td>
                                @endif
                                <td aria-label="Дата">
                                    <div class="tblDate"><span class="timeOrder">${TimeCreate}</span><span class="dateOrder">${DateCreate}</span></div>
                                </td>
                                <td aria-label="Сумма"> 
                                    <div class="">${sum_of_order}</div>
                                </td>
                                <td aria-label="Имя"> 
                                    <div class="">${name}</div>
                                </td>
                                <td aria-label="Телефон">
                                    <div class="">${phone}</td></div>
                                </td>
                                <td aria-label="Сообщение">
                                    <div class="clearfix">
                                        <span class="newMessage" @{{if newCount<=0}} style="width: 100%;" @{{/if}}>@{{if message}}${message}@{{else}}Картинка@{{/if}}</span>@{{if newCount>0}}<span class="newMessageCount @{{if newCount>9}}wideCountTwo@{{/if}} @{{if newCount>99}}wideCountThree@{{/if}}">${newCount}</span>@{{/if}}
                                    </div>
                                </td>
                                <td aria-label="Статус">
                                    <div class="">${statusOrder}</div>
                                </td>
                                @if($typeId !== 4)
                                <td aria-label="Заметка">
                                    <div class="purposeNote">${purpose}</div>
                                </td> 
                                @endif
                            </tr>
                        </script>
                    </tbody>
                </table>
            </div>
        </main>
        @include('handleOldToken')    
        <footer>
        </footer>
    </body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.tmpl.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script type="text/javascript">
    $().ready(function(){

        var conn = new WebSocket('wss://rusvyatich.pro/wss2/NNN:32769');
        conn.onopen = function(e) {
            subscribe('newOrders'); 
            @if (Session::get('status') == ADMIN || Session::get('status') == MAIN_MASTER || Session::get('status') == OPERATOR || Session::get('status') == MAIN_OPERATOR)
                subscribe("user{{ Session::get('userId') }}");
            @endif
        }

        conn.onmessage = function(e) {
            tmplConstructOrders();

        }

        function subscribe(channel) {
            conn.send(JSON.stringify({command: "subscribe", channel: channel}));
        }

        @include('scripts.closeMessages')
        @include('scripts.sameScripts')
        
        if (device.desktop()){
            $('#scrollTable').niceScroll($('#OrdersTable'), {cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:8, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8", mousescrollstep:45, bouncescroll: false});
        } else {
            $('#falseHead').css('width', '100%');
        }
        $('.scrollTable').css('height', $(window).height() - $('.header').outerHeight() - 15); 
        tmplConstructOrders();
        $(window).resize(function(){
            $('.scrollTable').css('height', $(window).height() - $('.header').outerHeight() - 15);
            FalseHeadChanger();
        });
        $('.scrollTable').on('mousewheel', function() {
            if($(this).scrollTop()<0){
                $(this).scrollTop(0);
            }
        });
        $.each($(".admin_navigation a"), function() {

            if ($(this).attr("href") == "#"){
                $(this).parent().addClass('navPushed');
            }
        });
        $.each($(".typesOfOrderNav a"), function() {

            if ($(this).attr("href") == "#") {
                $(this).parent().addClass('underlined');
            }
        });
        var cartData = null;
        var changeFlag = true; //флаг нужды изменения данных о заказах
        
        /*Получение заказов для отображения в таблице*/
        function getOrders() {
            $.ajax({
                type:'POST',
                async: false,
                url:"/getOrders",
                data: {
                    'typeOfOrder': {{ $typeId }}
                },
                dataType:'json',
                success: function(data){
                    if (data['success'] === 1) {
                        cartData = data['orders'];
                        if(data['changes'] === 1){
                            cartData = data['orders'];
                            changeFlag = true;
                        }else {
                            changeFlag = false;
                        }
                        if (data['changeConstruct'] > 0){
                            $('#constructNew').text('+' + data['changeConstruct']);
                        } else {
                            $('#constructNew').empty();
                        }
                        if (data['changeIndividual'] > 0){
                            $('#imageNew').text('+' + data['changeIndividual']);
                        } else {
                            $('#imageNew').empty();
                        }
                        if (data['changeCart'] > 0){
                            $('#cartNew').text('+' + data['changeCart']);
                        } else {
                            $('#cartNew').empty();
                        }
                    } else {
                        cartData = null;
                        changeFlag = false;
                    }
                    handleOldToken(data, true);
                },
                error: function(xhr,status,error){  
                     console.log(error);
                     location.reload();
                    }
            });

        }

        function FalseHeadChanger(){
            if (($('#scrollTable').height() < $('#OrdersTable').height()) && device.desktop()) {
                $('#falseHead').css('width', 'calc(100% - 8px)');
            } else {
                $('#falseHead').css('width', '100%');
            }
        }

        /*формирование шаблона корзины + её открытие*/
        function tmplConstructOrders(){
            $.when(getOrders()).then(function(){
                if(changeFlag){
                    var cartBox=$('#OrdersTable');
                    $('.OrderRecord').remove();
                    if(cartData){
                        cartBox.find("#OrdersTemplate").tmpl(cartData).appendTo("#OrdersTable");
                    }
                    FalseHeadChanger();
                }
            });
        }
    });
</script>
</html>
@endsection