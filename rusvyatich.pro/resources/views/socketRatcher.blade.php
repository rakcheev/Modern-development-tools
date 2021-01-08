<script type="text/javascript">

    var conn = new WebSocket('wss://rusvyatich.pro/wss2/NNN:32769');
    conn.onmessage = function(e) {
        tmplMessages({{ $order->id }}, {{ $typeOrder }});
    }
    conn.onopen = function(e) {// двойные скобки удалены 

        @foreach ($mainMasters as $mainMaster)
            subscribe("user{{$mainMaster->id}}");
        @endforeach
        subscribe("user{{$order->id_operator}}");
        @foreach ($mainOperators as $mainOperator)
            subscribe("user{{$mainOperator->id}}");
        @endforeach

        subscribe("user1");
        @if($order->id_master) 
            subscribe("user{{ $order->id_master }}");
        @endif
        @if (Session::get('status') !== CUSTOMER) 
            subscribe("user{{ $order->user_id }}");
        @elseif(!empty(Session::get('userId')))
            subscribe("user{{ Session::get('userId') }}");
        @endif
        switch({{$typeOrder}}) {
            case {{CONSTRUCT_ORDER}}:
                subscribe('constructOrder{{ $order->id }}');
                break;

            case {{IMAGE_ORDER}}:
                subscribe('imageOrder{{ $order->id }}');
                break;

            case {{CART_ORDER}}:
                subscribe('cartOrder{{ $order->id }}');
                break;

            default:
                break;
        }
        tmplMessages({{ $order->id }}, {{ $typeOrder }});

        conn.send(JSON.stringify({command: "imonline", id: {{ $outer }}}));
    }


    /*Подписаться на канал*/
    function subscribe(channel) {
        conn.send(JSON.stringify({command: "subscribe", channel: channel}));
    }

    /*Отписаться от канала*/
    function unsubscribe(channel) {
        conn.send(JSON.stringify({command: "unsubscribe", channel: channel}));
    }

    function sendSocket() {
       var msg = "Sended";
        conn.send(JSON.stringify({command: "message", message: msg}));
    }
    /*Отправка сообщения*/
    function sendLetter(orderId, typeOrder){
        if ($('#message').text() == '' && $('.row').is(':hidden')) return;
        $("#image_close").css('display','none');
        file = new FormData();
        var f = $('input[type=file]')[0].files[0];
        if($('.row').is(':hidden')) {
            f = '';
        }
        hidePreview();
        file.append('file', f);
        file.append('message', $('#message').text());
        $('#message').empty();
        file.append('orderId', orderId);
        file.append('typeOrder', typeOrder);
        $('#letterButton').prop('disabled', true);
        $('#letterButton').addClass('button_pushed');
        $.ajax({
            type:"POST",
            url:"/sendMessage",
            data: file,
            processData: false,
            contentType: false,
            dataType:'json',
        }).done(function(data){
            handleAjaxResponse(data);
            if(data['success'] == 1){
                hidePreview();
                flagForm=true;
                $("#image_close").css('display','block');
                tmplMessages(orderId, typeOrder);
                $('#letterButton').prop('disabled', false);
                $('#letterButton').removeClass('button_pushed');
                sendSocket();
            }
        }).fail(function (xhr,status,error){  
            console.log(error);
        });
    }
    //tmplMessages({{ $order->id }}, {{ $typeOrder }});

</script>