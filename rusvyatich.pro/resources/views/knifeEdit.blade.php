@extends('layouts.userhead')

@section('content')
    <body class="editKnifeBody">
        <main>
            @include('adminLeftColumn')
            <div class="knife">
                <form id="knifeForm" method="POST" enctype="multipart/form-data">
                    <div class="leftKnife">
                        <div id="leftKnifeCaption" class="mainCaption">
                            <span>Характеристики ножа</span>
                        </div>
                        <div class="scrollPiece">
                            <div class="knifeEditProperties">
                                <dl class="dl-inline clearfix InlineKnifeName">
                                    <dt class="dt-dotted">
                                        <span>Название</span>
                                    </dt>
                                    <dd>
                                    <input type="numeric" name="name" value="@if (!empty($knife)){{ $knife->name }}@endif"/></dd>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifeSteel">
                                    <dt class="dt-dotted">
                                        <span id="32">Сталь</span>
                                    </dt>
                                    <dd id="steelChange">
                                        <div class="new-select-style-wpandyou">
                                            <select name="steel" size="bar" style="text-align: right;" class="statusSelect" onchange="confirmation();" onfocus="setStatusPrev();">
                                                    @foreach ($steels as $steel)
                                                        <option value="{{ $steel->id }}" 
                                                            @if (!empty($knife) && $knife->id_steel === $steel->id)
                                                                selected 
                                                            @endif
                                                        >    
                                                        {{ $steel->name }}
                                                        </option>           
                                                    @endforeach   
                                            </select>
                                        </div>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifeLength">
                                    <dt class="dt-dotted">
                                        <span>Длина клинка</span>
                                    </dt>
                                    <dd>
                                        <input type="numeric" name="blade_length" value="@if (!empty($knife)){{ $knife->blade_length }}@endif"/>
                                    </dd>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifeHeight">
                                    <dt class="dt-dotted">
                                        <span>Высота клинка</span>
                                    </dt>
                                    <dd><input type="numeric" name="blade_width" value="@if (!empty($knife)){{ $knife->blade_width}}@endif"</dd>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifeWidth">
                                    <dt class="dt-dotted">
                                        <span class="nameProperty">Толщина обуха</span>
                                    </dt>
                                    <dd><input type="numeric" name="blade_thickness" value="@if (!empty($knife)){{ $knife->blade_thickness }}@endif"/></dd>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifeHandleLength">
                                    <dt class="dt-dotted">
                                        <span>Длина рукояти</span>
                                    </dt>
                                    <dd id="steelChange"><input type="numeric" name="handle_length" value="@if (!empty($knife)){{ $knife->handle_length }}@endif"/></dd>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifePrice">
                                    <dt class="dt-dotted">
                                        <span>Цена</span>
                                    </dt>
                                    <dd><input type="numeric" name="price" value="@if (!empty($knife)){{ $knife->price }}@endif"/></dd>
                                </dl>
                                <dl class="dl-inline clearfix InlineKnifeStatus">
                                    <dt class="dt-dotted">
                                        @if(!empty($serial)) 
                                            <span>Кол-во</span>
                                        @else
                                            <span>Статус</span>
                                        @endif
                                    </dt>
                                    <dd>
                                        @if(!empty($serial)) 
                                            <input type="numeric" name="count" value="@if (!empty($knife)){{ $knife->count }}@endif"/>
                                        @else
                                        <div class="new-select-style-wpandyou">
                                            <select name="status" size="bar" style="text-align: right;" dir="rtl" class="statusSelect" onchange="confirmation();" onfocus="setStatusPrev();">
                                                @foreach ($statuses as $status)   
                                                    <option value="{{ $status->id }}" 
                                                        @if (!empty($knife))
                                                            @if ($knife->id_status === $status->id)
                                                                selected 
                                                            @endif
                                                        @endif
                                                        >    
                                                        {{ $status->name }}
                                                    </option>           
                                                @endforeach      
                                            </select>
                                        </div>
                                        @endif
                                    </dd>
                                </dl>
                                @if(!empty($serial)) 
                                    <dl class="dl-inline clearfix">
                                        <dt class="dt-dotted">
                                            <span>Скрыть</span>
                                        </dt>
                                        <dd>
                                            <input id="checkHide" type='checkbox' name="viewable" @if (!empty($knife))
                                                @if ($knife->viewable === 0) 
                                                    checked 
                                                @endif
                                            @endif>
                                        <label for="checkHide" class="check"></label>
                                        </dd>
                                    </dl>
                                @endif
                                <dl class="dl-inline clearfix enforcedWidth InlineKnifeHandle">
                                    <dt class="dt-dotted">
                                        <span>Рукоять</span>
                                    </dt>
                                    <dd>  
                                        <textarea type="text" name="handle" spellcheck="false" autocomplete="off">@if (!empty($knife)){{ $knife->handle }}@endif</textarea>
                                    </dd>
                                </dl>
                                <dl class="dl-inline clearfix enforcedWidth InlineKnifeDesc">
                                    <dt class="dt-dotted">
                                        <span>Описание ножа</span>
                                    </dt>
                                    <dd id="steelChange">
                                        <textarea type="text" name="description" spellcheck="false" autocomplete="off">@if (!empty($knife)){{ $knife->description }}@endif</textarea>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="rightKnife">
                        <div class="mainCaption">
                            <span>Фотографии ножа</span>
                        </div>
                        <div id="bodyPhotos" class="scrollPiece">
                            <div class="photoKnife photo1">
                                <div class="caption">
                                    <span>Фото1</span>
                                    @if (!empty($knife)) <label class="dropPhoto">
                                        <span>Удалить</span>
                                        <input id="drop1" type="checkbox" name="drop1">
                                        <label class="check" for="drop1"></label>
                                    </label> @endif
                                </div>
                                <div class="file file_image">
                                    <label class="file_upload">
                                        <span class="button chooseImg">Выбрать</span>
                                        <input id="file1" type="file" name="image1" accept="image/*">
                                    </label>
                                    <div class="row row1 
                                            @if (!empty($knife))
                                                @foreach($photos as $photo)
                                                    @if($photo->number == 1) shownRow 
                                                    @endif
                                                @endforeach
                                            @endif">
                                        <div class="outputs" id="output1">
                                            <div class="close">
                                                <div id="image_close_1" class="window_close">Закрыть</div>
                                            </div>
                                            <span>
                                                @if (!empty($knife))
                                                    @foreach($photos as $photo)
                                                        @if($photo->number == 1)
                                                        <img id="preview1" class="thumb" src="{{ asset('img/imgStorage') }}/{{ $photo->image }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="photoKnife photo2">
                                <div class="caption">
                                    <span>Фото2</span>
                                    @if (!empty($knife)) <label class="dropPhoto">
                                        <span>Удалить</span>
                                        <input id="drop2" type="checkbox" name="drop2">
                                        <label class="check" for="drop2"></label>
                                    </label> @endif
                                </div>
                                <div class="file file_image">
                                    <label class="file_upload">
                                        <span class="button chooseImg">Выбрать</span>
                                        <input id="file2" type="file" name="image2" accept="image/*">
                                    </label>
                                    <div class="row row2
                                            @if (!empty($knife))
                                                @foreach($photos as $photo)
                                                    @if($photo->number == 2) shownRow 
                                                    @endif
                                                @endforeach
                                            @endif">
                                        <div class="outputs" id="output2">
                                            <div class="close">
                                                <div id="image_close_2" class="window_close">Закрыть</div>
                                            </div>
                                            <span>
                                                @if (!empty($knife))
                                                    @foreach($photos as $photo)
                                                        @if($photo->number == 2)
                                                        <img id="preview2" class="thumb" src="{{ asset('img/imgStorage') }}/{{ $photo->image }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="photoKnife photo3">
                                <div class="caption">
                                    <span>Фото3</span>
                                    @if (!empty($knife)) <label class="dropPhoto">
                                        <span>Удалить</span>
                                        <input id="drop3" type="checkbox" name="drop3">
                                        <label class="check" for="drop3"></label>
                                    </label> @endif
                                </div>
                                <div class="file file_image">
                                    <label class="file_upload">
                                        <span class="button chooseImg">Выбрать</span>
                                        <input id="file3" type="file" name="image3" accept="image/*">
                                    </label>
                                    <div class="row row3 
                                            @if (!empty($knife))
                                                @foreach($photos as $photo)
                                                    @if($photo->number == 3) shownRow 
                                                    @endif
                                                @endforeach
                                            @endif">
                                        <div class="outputs" id="output3">
                                            <div class="close">
                                                <div id="image_close_3" class="window_close">Закрыть</div>
                                            </div>
                                            <span>
                                                @if (!empty($knife))
                                                    @foreach($photos as $photo)
                                                        @if($photo->number == 3)
                                                        <img id="preview3" class="thumb" src="{{ asset('img/imgStorage') }}/{{ $photo->image }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="photoKnife photo4">
                                <div class="caption">
                                    <span>Фото4</span>
                                    @if (!empty($knife)) <label class="dropPhoto">
                                        <span>Удалить</span>
                                        <input id="drop4" type="checkbox" name="drop4">
                                        <label class="check" for="drop4"></label>
                                    </label> @endif
                                </div>
                                <div class="file file_image">
                                    <label class="file_upload">
                                        <span class="button chooseImg">Выбрать</span>
                                        <input id="file4" type="file" name="image4" accept="image/*">
                                    </label>
                                    <div class="row row4 
                                            @if (!empty($knife))
                                                @foreach($photos as $photo)
                                                    @if($photo->number == 4) shownRow 
                                                    @endif
                                                @endforeach
                                            @endif">
                                        <div class="outputs" id="output4">
                                            <div class="close">
                                                <div id="image_close_4" class="window_close">Закрыть</div>
                                            </div>
                                            <span>
                                                @if (!empty($knife))
                                                    @foreach($photos as $photo)
                                                        @if($photo->number == 4)
                                                        <img id="preview4" class="thumb" src="{{ asset('img/imgStorage') }}/{{ $photo->image }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="photoKnife photo5">
                                <div class="caption">
                                    <span>Фото5</span>
                                    @if (!empty($knife)) <label class="dropPhoto">
                                        <span>Удалить</span>
                                        <input id="drop5" type="checkbox" name="drop5">
                                        <label class="check" for="drop5"></label>
                                    </label> @endif
                                </div>
                                <div class="file file_image">
                                    <label class="file_upload">
                                        <span class="button chooseImg">Выбрать</span>
                                        <input id="file5" type="file" name="image5" accept="image/*">
                                    </label>
                                    <div class="row row5 
                                            @if (!empty($knife))
                                                @foreach($photos as $photo)
                                                    @if($photo->number == 5) shownRow 
                                                    @endif
                                                @endforeach
                                            @endif">
                                        <div class="outputs" id="output5">
                                            <div class="close">
                                                <div id="image_close_5" class="window_close">Закрыть</div>
                                            </div>
                                            <span>
                                                @if (!empty($knife))
                                                    @foreach($photos as $photo)
                                                        @if($photo->number == 5)
                                                        <img id="preview5" class="thumb" src="{{ asset('img/imgStorage') }}/{{ $photo->image }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="photoKnife photo6">
                                <div class="caption">
                                    <span>Фото6</span>
                                    @if (!empty($knife)) <label class="dropPhoto">
                                        <span>Удалить</span>
                                        <input id="drop6" type="checkbox" name="drop6">
                                        <label class="check" for="drop6"></label>
                                    </label> @endif
                                </div>
                                <div class="file file_image">
                                    <label class="file_upload">
                                        <span class="button chooseImg">Выбрать</span>
                                        <input id="file6" type="file" name="image6" accept="image/*">
                                    </label>
                                    <div class="row row6 
                                            @if (!empty($knife))
                                                @foreach($photos as $photo)
                                                    @if($photo->number == 6) shownRow 
                                                    @endif
                                                @endforeach
                                            @endif">
                                        <div class="outputs" id="output6">
                                            <div class="close">
                                                <div id="image_close_6" class="window_close">Закрыть</div>
                                            </div>
                                            <span>
                                                @if (!empty($knife))
                                                    @foreach($photos as $photo)
                                                        @if($photo->number == 6)
                                                        <img id="preview6" class="thumb" src="{{ asset('img/imgStorage') }}/{{ $photo->image }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($serial)) 
                                <button id="saveKnife" class="button" name="send" type="button" onclick="@if(!empty($knife))updateSerialKnife({{ $knife->id }});@else saveSerialKnifee(); @endif return false;">Сохранить</button>
                            @else
                                <button id="saveKnife" class="button" name="send" type="button" onclick="@if(!empty($knife))updateKnife({{ $knife->id }});@else saveKnifee(); @endif return false;">Сохранить</button>
                            @endif

                            @if(Session::get('status') === 9 && !empty($knife)) <button id="removeKnife" class="button" name="send" type="button" onclick="confirmationDropKnife(); return false">удалить</button> @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
   	</main>
    <div id="success_message" class="wrapAlert">
        <div id="alert_message" class="alert">
            <div class="boxAlert">
                <span class="captionAlert">Нож изменен!</span>
                <button id="close_alert" class="button">продолжить</button>
            </div>
        </div>
    </div>
           @if (!empty($knife)) <div id='confirmationDropKnife' class="wrapAlert">
                <div class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Удалить нож?
                        </span>
                        <button id="confirmSum" class="button leftButton" onclick="dropKnife({{ $knife->id }}); return false">Да</button>
                        <button class="button rightButton" onclick="rejectDropKnife(); return false">Нет</button>
                    </div>
                </div>
            </div>
            @endif
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

       // if(device.desktop()) $('#bodyPhotos').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});

        function viewResizer(){
            if (!($("#flag1000").is(':visible') || $("#flag900").is(':visible') || $("#flag600").is(':visible') || $("#flag400").is(':visible'))){
                if (device.desktop()){
                    $('.scrollPiece').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8", mousescrollstep:45, bouncescroll: false});
                }
                $('.leftKnife').css('height', $(window).height());
                $('.rightKnife').css('height', $(window).height());
                $('.scrollPiece').css('height', $(window).height()-$('#leftKnifeCaption').outerHeight()-5 );
            } else {
                $('.leftKnife').css('height', 'auto');
                $('.rightKnife').css('height', 'auto');
                $('.scrollPiece').css('height', 'auto');
            }
        }
        viewResizer();
        setTimeout(function(){
            viewResizer();
        },500);
        $.each($(".adminNavigation a"),function(){
            if($(this).attr("href")=="#"){
                $(this).addClass('navPushed');
            }
        });

        $(window).resize(function(){
            viewResizer();
        });
        $('#close_alert').click(function(){
            $('#success_message').css('display', 'none');
        });
        /*Отработка действия при удалении загруженной  картинки*/
        $('#image_close_1').click(function(event){
            $('.row1').css('display','none');
            $("#file1")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.photo1 .file_upload').css('display', 'inline-block');
        });
        /*Отработка действия при удалении загруженной картинки*/
        $('#image_close_2').click(function(event){
            $('.row2').css('display','none');
            $("#file2")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.photo2 .file_upload').css('display', 'inline-block');
        });
        /*Отработка действия при удалении загруженной картинки*/
        $('#image_close_3').click(function(event){
            $('.row3').css('display','none');
            $("#file3")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.photo3 .file_upload').css('display', 'inline-block');
        });
        /*Отработка действия при удалении загруженной картинки*/
        $('#image_close_4').click(function(event){
            $('.row4').css('display','none');
            $("#file4")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.photo4 .file_upload').css('display', 'inline-block');
        });
        /*Отработка действия при удалении загруженной картинки*/
        $('#image_close_5').click(function(event){
            $('.row5').css('display','none');
            $("#file5")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.photo5 .file_upload').css('display', 'inline-block');
        });
        /*Отработка действия при удалении загруженной картинки*/
        $('#image_close_6').click(function(event){
            $('.row6').css('display','none');
            $("#file6")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.photo6 .file_upload').css('display', 'inline-block');
        });
        $('#file1').change(function(event){
            handleFileSelectPhoto(event,1);
        });
        $('#file2').change(function(event){
            handleFileSelectPhoto(event,2);
        });
        $('#file3').change(function(event){
            handleFileSelectPhoto(event,3);
        });
        $('#file4').change(function(event){
            handleFileSelectPhoto(event,4);
        });
        $('#file5').change(function(event){
            handleFileSelectPhoto(event,5);
        });
        $('#file6').change(function(event){
            handleFileSelectPhoto(event,6);
        });

        /*Шаблон только цифры */
        $("input[name='price'], input[name='blade_length'], input[name='blade_width'], input[name='blade_thickness'], input[name='handle_length']").on("change keyup input click", function(){
            if(this.value == "") return false; 
            if (this.value.match(/[^0-9.]/g)){
                this.value = this.value.replace(/[^0-9.]/g, '');
            } 
        });

    });
</script>
@endsection
