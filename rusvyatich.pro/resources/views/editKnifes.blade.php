@extends('layouts.userhead')

@section('content')
    <body class="editKnifesBody">
        <main> 
            @include('adminLeftColumn')  
            <div class="header">
                @if(Session::get('status') === 9 || Session::get('status') === 3)
                    <a class="button addOrder" href="@if(!empty($serial)) /home/knifeSerial/add @else /home/knife/add @endif">Добавить нож</a>
                @endif
                <h1 class="customerCaption">Продукты</h1>
                <ul class="typesOfOrderNav clearfix">
                    <li>
                        <a class ="serialAfter" href="{{$serialLink}}">Серийные</a>
                    </li>
                    <li>
                        <a class="individAfter" href="{{$individLink}}">Индивидуальные</a>
                    </li>
                </ul>
            </div>  
            <table id="falseHead" class='adminTable'>
                <tr>
                    <th class="tblNum">№</th>
                    <th class="tblImg">картинка</th>
                    <th class="tblName">Название</th>
                    <th class="tblStatus">@if(!empty($serial)) Колличество @else Статус @endif</th>
                    <th class="tblPrice">Цена</th>
                </tr>
            </table>
            <div id="scrollTable" class="scrollTable">
                <table id="OrdersTable" class='adminTable'>
                    <tr id="captionsKnife">
                        <th class="tblNum">№</th>
                        <th class="tblImg">картинка</th>
                        <th class="tblName">Название</th>
                        <th class="tblStatus">@if(!empty($serial)) Колличество @else Статус @endif</th>
                        <th class="tblPrice">Цена</th>
                    </tr>
                    @foreach ($products as $product)
                    <tr onclick="@if(!empty($serial)) toProductSerial({{ $product->id }}) @else toProduct({{ $product->id }}) @endif">
                        <td aria-label="№">
                            <div>{{ $product->id}}</div>
                        </td>
                        <td aria-label="Картинка">
                            <div class="tblImg">
                                <img src="{{ asset('img/imgStorage') }}/{{ $product->image }}" width="100%">
                            </div>
                        </td>
                        <td aria-label="Название">
                            <div>{{ $product->name}}</div>
                        </td>
                        <td aria-label="@if(!empty($serial)) Колличество @else Статус @endif">
                            @if(!empty($serial))
                                <div>
                                    {{$product->count}}
                                </div>
                            @else
                                @foreach ($statusKnifes as $statusKnife)
                                    @if($statusKnife->id === $product->id_status)<div>{{ $statusKnife->name}}</div>@endif
                                @endforeach
                            @endif
                        </td>
                        <td aria-label="Цена">
                            <div>{{ $product->price}}</div>
                        </td>
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
        $.each($(".typesOfOrderNav a"),function(){

            if($(this).attr("href")=="#"){
                $(this).parent().addClass('underlined');
            }
        });

    });
</script>
@endsection