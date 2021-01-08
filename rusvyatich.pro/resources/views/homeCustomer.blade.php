@extends('layouts.userhead')

@section('content')
    <body class='customerBody'>    
        <main>
            <div class="header">
                <h1 class="customerCaption">Мои заказы</h1>
            </div> 
            @include('adminLeftColumn')
            <span id="emptyOrders">У вас нет заказов</span>
            <table id="falseHead" class='customerTable'>
                    <tr>
                        <th scope="col" class="tblNumberOrder">№</th>
                        <th scope="col" class="tblDate">Дата</th>
                        <th scope="col" class="tblType">Тип заказа</th> 
                        <th scope="col" class="tblStatus">Статус</th>
                        <th scope="col" class="tblMessage">Последнее сообщение</th>
                        <th scope="col" class="tblSum">Сумма</th>
                    </tr>
            </table>
            <div id="scrollTable" class="scrollTable">
                <table id="OrdersTable" class='customerTable'>
                    <thead>
                        <tr class="captionsTableOrder">
                            <th scope="col" class="tblNumberOrder">№</th>
                            <th scope="col" class="tblDate">Дата</th>
                            <th scope="col" class="tblType">Тип заказа</th> 
                            <th scope="col" class="tblStatus">Статус</th>
                            <th scope="col" class="tblMessage">Последнее сообщение</th>
                            <th scope="col" class="tblSum">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <script id="OrdersTemplate" type="text/x-jquery-tmpl">
                            <tr class="OrderRecord 
                                    @{{if newCount>0}}unreaded@{{/if}}
                                " onclick=" 
                                @if(Session::get('status') === CUSTOMER) 
                                    toOrderForCustomer(${id}, ${id_type_order}); 
                                @elseif(Session::get('status') === MASTER || Session::get('status') === MAIN_MASTER)
                                    toOrderForMaster(${id}, ${id_type_order}); 
                                @endif
                            return false">
                                <td aria-label="№">
                                    <div>@{{if id_type_order === 2}}i @{{/if}} ${id}</div>
                                </td>
                                <td aria-label="Дата">
                                    <div class="tblDate"><span class="timeOrder">${TimeCreate}</span><span class="dateOrder">${DateCreate}</span></div>
                                </td>
                                <td aria-label="Тип">
                                    <div>${orderType}</div>
                                </td>
                                <td aria-label="Статус">
                                    <div>${statusOrder}</td></div>
                                </td>
                                <td aria-label="Сообщение">
                                    <div class="clearfix">
                                        <span class="newMessage" @{{if newCount<=0}} style="width: 100%;" @{{/if}}>@{{if message}}${message}@{{else}}Картинка@{{/if}}</span>@{{if newCount>0}}<span class="newMessageCount @{{if newCount>9}}wideCountTwo@{{/if}} @{{if newCount>99}}wideCountThree@{{/if}}">${newCount}</span>@{{/if}}
                                    </div>
                                </td>
                                <td aria-label="Сумма">
                                    <div class="">${sum_of_order}</div>
                                </td>
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
            subscribe("user{{Session::get('userId')}}");
        }

        conn.onmessage = function(e) {
            tmplConstructOrders();
            
        }

        function subscribe(channel) {
            conn.send(JSON.stringify({command: "subscribe", channel: channel}));
        }


        @include('scripts.closeMessages')
        @include('scripts.sameScripts')

        if(device.desktop()){
            $('#scrollTable').niceScroll($('#OrdersTable'), {cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:8, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep: 45, bouncescroll: false});
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

        var cartData = null;
        var changeFlag = true; //флаг нужды изменения данных о заказах
        
        /*Получение заказов для отображения в таблице*/
        function getOrders() {
            $.ajax({
                type:'POST',
                async: false,
                url:"/getOrdersCustomer",
                dataType:'json',
                success: function(data){
                    if (data['success'] === 1) {
                        cartData = data['orders'];
                        if(data['changes'] === 1){
                            cartData = data['orders'];
                            changeFlag = true;
                            if ( cartData.length == 0) {
                                $('#falseHead').css('display', 'none');
                                $('#emptyOrders').css('display', 'block');
                            } else {
                                $('#falseHead').css('display', '');
                                $('#emptyOrders').css('display', '');
                            }
                        } else {
                            changeFlag = false;
                        }
                    } else {
                        cartData = null;
                        changeFlag = false;
                    }
                    handleOldToken(data,true);
                    if (!cartData) {
                    }
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