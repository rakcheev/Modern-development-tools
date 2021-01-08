       
@extends('layouts.userhead')

@section('content')
    <body class="changeContructBody">
        <main> 
            @include('adminLeftColumn') 
            <div class="header clearfix">
                @if(Session::get('status') === 9)
                        <a class="button addOrder" href="{{ $addPropertyLink }}">Добавить</a>
                @endif
                <h1 class="customerCaption">Конструктор</h1>
                <ul class="typesOfOrderNav clearfix">
                    <li>
                        <a class ="steelAfter" href="{{ $steelsLink }}">Сталь</a>
                    </li>
                    <li>
                        <a class="bladeAfter" href="{{ $bladesLink }}">Клинки</a>
                    </li>
                    <li>
                        <a class="bolsterAfter" href="{{ $bolstersLink }}">Больстеры</a>
                    </li>
                    <li>
                        <a class="handleAfter" href="{{ $handlesLink }}">Рукояти</a>
                    </li>
                    <li>
                        <a class="handleMaterialAfter" href="{{ $handlesMaterialLink }}">Материалы рукоятей</a>
                    </li>
                    <li>
                        <a class="sizeAfter" href="{{ $sizesLink }}">Размеры</a>
                    </li>
                </ul>
            </div>
            <table id="falseHead" class='adminTable'>
                <tr>
                    <th class="tblNameProp">Название</th>
                    <th class="tblImgSvg">Картинка</th>
                    <th class="tblDescProp">Описание</th>
                    <th class="tblPrice">Цена</th>
                </tr>
            </table>
            <div id="scrollTable" class="scrollTable">
                <table id="OrdersTable" class='adminTable'>
                    <tr class="captionsTableOrder">
                        <th scope="col" class="tblNameProp">Название</th>
                        <th scope="col" class="tblImgSvg">Картинка</th>
                        <th scope="col" class="tblDescProp">Описание</th>
                        <th scope="col" class="tblPrice">Цена</th>
                    </tr>
                    @foreach ($properties as $property)
                        <tr class="OrderRecord" onclick="window.location.href='{{ $controllerLink }}{{$property->id}}'">
                            <td aria-label="Название">
                                <div>{{ $property->name}}({{ $property->id}})</div>
                            </td>
                            <td aria-label="Вид">
                                @if ( $pageType !== 'steels' && $pageType !== 'handleMaterials' )
                                    <div style="width: 150px;" class="svgProperty">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 150">
                                            <path fill="none"  stroke-width="1" stroke="#000000" d="{{ $property->path }}" vector-effect="non-scaling-stroke"
                                            @if (( $pageType == 'blades' )) 
                                                transform="translate(-280 0) scale(0.90 0.90)"
                                            @elseif (( $pageType == 'bolsters' )) 
                                                transform="translate(-440 0) scale(0.90 0.90)"
                                            @elseif (( $pageType == 'handles' ))
                                                transform="translate(-600 0) scale(0.94 0.94)"
                                            @endif 
                                            />
                                        </svg>
                                    </div>
                                @elseif ( $pageType == 'steels' || $pageType == 'handleMaterials' )
                                    <div style="width: 150px;" class="svgProperty">
                                        <img src="{{ asset('img/patternsConstruct') }}/{{ $property->texture }}" width="100%">
                                    </div>
                                @endif
                            </td>
                            <td aria-label="Описание">
                                <div>{{ $property->description}}</div>
                            </td>
                            <td aria-label="Цена">
                                <div>{{ $property->price}}</div>
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