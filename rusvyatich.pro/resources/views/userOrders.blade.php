@extends('layouts.userhead')

@section('content')
    <body class='constructOrderBody allUsersBody'>    
        <main>
            @include('adminLeftColumn')
            <div class="customerOrderBlock">
                <div class="aboutCustomer">
                        <span>{{ $user->name }}</span>
                        <span>{{ $user->surname }}</span>              
                        <span>{{ $user->patronymic }}</span>
                </div>
                <div id="scrollPieceCustomer" class="scrollPiece">
                    <div class="aboutOrder">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Телефон</span>
                            </dt>
                            <dd id="steel__{$item['id']}">{{ $user->phone }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>email</span>
                            </dt>
                            <dd id="steel__{$item['id']}">{{ $user->email }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix timeInline">
                            <dt class="dt-dotted">
                                <span>Местное время</span>
                            </dt>
                            <dd >от  @if( $user->fst_hours > 0) + @endif{{ $user->fst_hours}} до @if( $user->scnd_hours > 0) + @endif {{ $user->scnd_hours}}</dd>
                        </dl>
                        <dl class="dl-inline clearfix alertInline">
                            <dt class="dt-dotted">
                                <span>Уведомления</span>
                            </dt>
                            <dd > @if( $user->sms_alert_id === SEND_SMS) да @endif @if( $user->sms_alert_id === NO_SMS) нет @endif</dd>
                        </dl>
                    </div>
                    <div class="sectionCaption">
                        <span>Адрес</span>
                    </div>
                    <div class="adressCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Регион</span>
                            </dt>
                            <dd id="steel__{$item['id']}">{{ $user->region }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Населенный пункт</span>
                            </dt>
                            <dd id="length__{$item['id']}">{{ $user->locality }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Улица</span>
                            </dt>
                            <dd id="width__{$item['id']}">{{ $user->street }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Дом</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">{{ $user->house }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Квартира</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">{{ $user->flat }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Почтовый индекс</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">{{ $user->mail_index }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            @include('userAllOrders')
        </main>
        @include('handleOldToken')    
        <footer>
        </footer>
        <div id="flag900"></div>
        <div id="flag600"></div>
        <div id="flag400"></div>
        <div id="flag1000"></div>
        <div id="flag1200"></div>
    </body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
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
        @include('scripts.closeMessages')
        @include('scripts.sameScripts')
        
        if (device.desktop()){
            $('#scrollTable').niceScroll($('#OrdersTable'), {cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:8, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8", mousescrollstep:45, bouncescroll: false});
        } else {
            $('#falseHead').css('width', '100%');
        }
        function viewResizer(){
            if(!($("#flag1200").is(':visible') ||$("#flag900").is(':visible') ||$("#flag600").is(':visible') ||$("#flag400").is(':visible'))){
                $('.customerOrderBlock').css('height', $(window).height());
                $('.scrollTable').css('height', $(window).height()-$('.caption').outerHeight()); 
                $('.userAllOrders').css('height', 'auto');

                customerHeight = $(window).height()-$('.aboutCustomer').outerHeight()-5;
                $('#scrollPieceCustomer').css('height', customerHeight + 'px');
                if(device.desktop()) $('#scrollPieceCustomer').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});

                if ($('#scrollTable').height() < $('#OrdersTable').height() && device.desktop()) {
                    $('#falseHead').css('width', 'calc(100% - 8px)');
                } else {
                    $('#falseHead').css('width', '100%');
                }
            } else {
                $('.customerOrderBlock').css('height', 'auto');
                
                $('.scrollTable').css('height', $(window).height()-15); 
                if ($('#flag1000').is(':visible')){
                    $('.scrollTable').css('height', $(window).height()-$('.caption').outerHeight()-5);
                } 
            }
        }
        viewResizer();
        $(window).resize(function(){
            viewResizer();
        });

        $.each($(".admin_navigation a"), function() {

            if ($(this).attr("href") == "#"){
                $(this).parent().addClass('navPushed');
            }
        });
    });
</script>
</html>
@endsection