@extends('layouts.userhead')

@section('content')
    <body class="workersBody">
        <main>
            @include('adminLeftColumn')   
            <div class="header">
                <h1 class="customerCaption">Работники</h1>
                <button class="button addOrder" onclick="addWorker(); return false;">Добавить</button>
                <ul class="typesOfOrderNav clearfix">
                @if(Session::get('status') === ADMIN)
                    <li>
                        <a href="{{ $operatorsLink }}">Операторы</a>
                    </li>
                    <li>
                        <a href="{{ $operatorsMainLink }}">Главные операторы</a>
                    </li>
                    <li>
                        <a href="{{ $mastersLink }}">Мастера</a>
                    </li>
                    <li>
                        <a href="{{ $mastersMainLink }}">Главные мастера</a>
                    </li>
                @endif
                @if(Session::get('status') === MAIN_OPERATOR)
                    <li>
                        <a href="{{ $operatorsLink }}">Операторы</a>
                    </li>
                @endif
                @if(Session::get('status') === MAIN_MASTER)
                    <li>
                        <a href="{{ $mastersLink }}">Мастера</a>
                    </li>
                @endif
                </ul>
            </div>
            <table id="falseHead" class='adminTable'>
                <tr>
                    <th scope="col" class="tblWorkerId">id</th>
                    <th scope="col" class="tblWorkerName">имя</th>
                    <th scope="col" class="tblWorkerPhone">телефон</th>
                </tr>
            </table>
            <div id="scrollTable" class="scrollTable">
                <table id="OrdersTable" class='adminTable'>
                    <tr class="captionsTableOrder">
                        <td scope="col" class="tblWorkerId">id</td>
                        <td scope="col" class="tblWorkerName">имя</td>
                        <td scope="col" class="tblWorkerPhone">телефон</td>
                    </tr>
                    @foreach ($users as $user)
                        <tr onclick="window.location.href=window.location.toString()+ '/{{ $user->id }}'">
                            <td aria-label="id"><div>{{ $user->id }}</div></td>
                            <td aria-label="имя"><div>{{ $user->name }}</div></td>
                            <td aria-label="телефон"><div>{{ $user->phone }}</div></td>
                        </tr>   
                    @endforeach
                </table>
            </div>
        </main>
        @include('handleOldToken')        
    <footer>
    </footer>
</body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
<script type="text/javascript">
    $().ready(function(){
        @include('scripts.closeMessages')
        @include('scripts.sameScripts')

        if (device.desktop()){
            $('#scrollTable').niceScroll($('#OrdersTable'), {cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:8, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8", mousescrollstep:45, bouncescroll: false});
        } else {
            $('#falseHead').css('width', '100%');
        }

        $('.scrollTable').css('height', $(window).height() - $('.header').outerHeight() - 15); 
        $(window).resize(function(){
            $('.scrollTable').css('height', $(window).height() - $('.header').outerHeight() - 15);
        });

        if ($('#scrollTable').height() < $('#OrdersTable').height()) {
            $('#falseHead').css('width', 'calc(100% - 8px)');
        } else {
            $('#falseHead').css('width', '100%');
        }

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

    });
</script>
@endsection