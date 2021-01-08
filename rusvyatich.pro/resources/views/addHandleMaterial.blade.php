@extends('layouts.userhead')

@section('content')
    <body>
        <main class="editPropBody"> 
            @include('adminLeftColumn')   
            <div class="header">
                <h1 class="customerCaption">
                    @if (!empty($handleMaterial)) Редактирование материала рукояти
                    @else Добавление материала рукояти
                    @endif
                </h1>
            </div>
            <div class="aboutUser">
                <form id="handleMaterialAddForm" method="POST" class="propertyForm">
                    <div class="adresCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Название</span>
                            </dt>
                            <dd>
                                <input type="text" name="name" value="@if (!empty($handleMaterial)){{ $handleMaterial->name }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Цена</span>
                            </dt>
                            <dd>
                                <input type="numeric" name="price" value="@if (!empty($handleMaterial)){{ $handleMaterial->price  }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Цвет</span>
                            </dt>
                            <dd>
                                <input type="text" name="color" value="@if (!empty($handleMaterial)){{ $handleMaterial->color }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Популярность</span>
                            </dt>
                            <dd>
                                <input type="numeric" name="popularity" value="@if (!empty($handleMaterial)){{ $handleMaterial->popularity }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix descDl">
                            <dt class="dt-dotted">
                                <span>Описание</span>
                            </dt>
                            <dd>
                                <textarea name="description">@if (!empty($handleMaterial)){{ $handleMaterial->description }}@endif</textarea>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Наборный</span>
                            </dt>
                            <dd>
                                <input id="checkNabor" type='checkbox' name="nabor" @if (!empty($handleMaterial))
                                    @if ($handleMaterial->nabor === 1) 
                                        checked 
                                    @endif
                                @endif>
                                <label for="checkNabor" class="check"></label>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Скрыть</span>
                            </dt>
                            <dd>
                                <input id="checkHide" type='checkbox' name="viewable" @if (!empty($handleMaterial))
                                    @if ($handleMaterial->viewable === 0) 
                                        checked 
                                    @endif
                                @endif>
                                <label for="checkHide" class="check"></label>
                            </dd>
                        </dl>
                    </div>
                    <div class="nameCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Текстура</span>
                            </dt>
                            <dd>
                            </dd>
                        </dl>
                        <div class="imgPattern">
                            <div class="file file_image">
                                <label class="file_upload">
                                    <span class="button chooseImg">Выбрать</span>
                                    <input id="fileMain" type="file" name="imageMain" accept="image/*">
                                </label>
                                <div class="row rowMain @if (!empty($handleMaterial)) shownRow @endif">
                                    <div id="outputMain" class="outputs">
                                        <div class="close">
                                            <div id="image_close_main" class="window_close">Закрыть</div>
                                        </div>
                                        <span>
                                            @if (!empty($handleMaterial))
                                                <img id="previewMain" class="thumb" src="{{ asset('img/patternsConstruct') }}/{{ $handleMaterial->texture }}" />
                                                @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="saveProperty" type="button" class="button" onclick="@if (empty($handleMaterial)) addHandleMaterial(); @else 
                        updateHandleMaterial({{ $handleMaterial->id }}); @endif return false">Сохранить</button>
                        @if (!empty($handleMaterial))
                            @if (!$presence) 
                                <button id="removeProperty" type="button" class="button" onclick="confirmationDropPart(); return false;">Удалить</button>
                                <div id='confirmationDropPart' class="wrapAlert">
                                    <div class="alert">
                                        <div class="boxAlert">
                                            <span class="captionAlert">Удалить?
                                            </span>
                                            <button id="confirmDrop" class="button leftButton" onclick="updateHandleMaterial({{ $handleMaterial->id }}, 0);  return false">Да</button>
                                            <button class="button rightButton" onclick="rejectDropPart(); return false">Нет</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </main>
        @include('handleOldToken')    
        <div id="success_message" class="wrapAlert">
            <div id="alert_message" class="alert">
                <div class="boxAlert">
                    <span class="captionAlert"></span>
                    <button id="close_alert" class="button">продолжить</button>
                </div>
            </div>
        </div>      
    <footer>
    </footer>
</body>
<script src="{{ asset('admin/js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('admin/js/admin.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('admin/js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
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

        $('.aboutUser').css('height', $(window).height() - $('.header').outerHeight() - 35);
        if(device.desktop()) $('.aboutUser').niceScroll({cursorcolor:'#8a8484', cursoropacitymin:'1', cursorwidth:9, cursorborder:'none', cursorborderradius:0, background: "#e8e8e8", mousescrollstep:25, bouncescroll: false});
        $(window).resize(function(){
            $('.aboutUser').css('height', $(window).height() - $('.header').outerHeight() - 35);
        });

        $.each($(".adminNavigation a"),function(){
            if($(this).attr("href")=="#"){
                $(this).addClass('navPushed');
            }
        });

        /*скрытие блока сообщения*/
        $('#close_alert').click(function(event){
            $('#success_message').css('display','none');
            if ($('#close_alert').attr('data-saved') == 1) {
                location.reload();
            }
        });

        /*Отработка действия при удалении загруженной главной картинки*/
        $('#image_close_main').click(function(event){
            $('.rowMain').css('display','none');
            $("#fileMain")[0].value = "";
            $('.file_upload .button').text('выбрать');
            $('.imgPattern .file_upload').css('display', 'inline-block');
        });

        document.getElementById('fileMain').addEventListener('change', handleFileSelectMain, false);

        /*Шаблон для цвета*/
        $.mask.definitions['h'] = "[A-Fa-f0-9]";
        $(" input[name='color']").mask("#hhhhhh",{placeholder:"_"});
        
        /*Шаблон только цифры */
        $("input[name='price'], input[name='popularity']").on("change keyup input click", function(){
            if(this.value == "") return false; 
            if (this.value.match(/[^0-9]/g)){
                this.value = this.value.replace(/[^0-9]/g, '');
            } 
        });

    });
</script>
@endsection