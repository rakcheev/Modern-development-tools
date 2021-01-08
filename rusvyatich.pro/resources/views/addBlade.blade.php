@extends('layouts.userhead')

@section('content')
    <body>
        <main class="editPropBody"> 
            @include('adminLeftColumn')   
            <div class="header">
                <h1 class="customerCaption">
                    @if (!empty($blade)) Редактирование клинка
                    @else Добавление клинка
                    @endif
                </h1>
            </div>
            <div class="aboutUser">
                <form id="bladeAddForm" method="POST" class="propertyForm">
                    <div class="adresCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Название</span>
                            </dt>
                            <dd>
                                <input type="text" name="name" value="@if (!empty($blade)){{ $blade->name }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Цена</span>
                            </dt>
                            <dd>
                                <input type="text" name="price" value="@if (!empty($blade)){{ $blade->price }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Популярность</span>
                            </dt>
                            <dd>
                                <input type="numeric" name="popularity" value="@if (!empty($blade)){{ $blade->popularity }}@endif">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix descDl">
                            <dt class="dt-dotted">
                                <span>Описание</span>
                            </dt>
                            <dd>
                                <textarea name="description">@if (!empty($blade)){{ $blade->description }}@endif</textarea>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix svgDl">
                            <dt class="dt-dotted">
                                <span>Svg вид</span>
                            </dt>
                            <dd>
                                <textarea name="path">@if (!empty($blade)){{ $blade->path }}@endif</textarea>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix" style="margin-bottom: 25px;">
                            <dt class="dt-dotted">
                                <span>Koef Сложн.</span>
                            </dt>
                            <dd>
                                <div class="new-select-style-wpandyou">
                                    <select name="hardness" size="bar" style="text-align: right;" class="statusSelect">
                                            @foreach ($koefCosts as $koefCost)
                                                <option value="{{ $koefCost->id }}" 
                                                    @if (!empty($blade) && $blade->hardness === $koefCost->id)
                                                        selected 
                                                    @endif
                                                >    
                                                {{ $koefCost->k }}
                                                </option>           
                                            @endforeach   
                                    </select>
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Изогнут больше 5мм</span>
                            </dt>
                            <dd>
                                <input id="checkBent" type='checkbox' name="bent" @if (!empty($blade))
                                    @if ($blade->bent === 1) 
                                        checked 
                                    @endif
                                @endif>
                                <label for="checkBent" class="check"></label>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Не ХО</span>
                            </dt>
                            <dd>
                                <input id="checkHO" type='checkbox' name="free" @if (!empty($blade))
                                    @if ($blade->free === 1) 
                                        checked 
                                    @endif
                                @endif>
                                <label for="checkHO" class="check"></label>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Скрыть</span>
                            </dt>
                            <dd>
                                <input id="checkHide" type='checkbox' name="viewable" @if (!empty($blade))
                                    @if ($blade->viewable === 0) 
                                        checked 
                                    @endif
                                @endif>
                                <label for="checkHide" class="check"></label>
                            </dd>
                        </dl>
                    </div>
                    <div class="nameCustomer clearfix">
                        <div class="svgProperty">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Вид</span>
                                </dt>
                                <dd>
                                </dd>
                            </dl>
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 320 200">
                            <path id="pathProperty" fill="none"  stroke-width="1" stroke="#000000" d="" vector-effect="non-scaling-stroke" transform="translate(-280 0) scale(0.90 0.90)"/>
                            </svg>
                            <div class="">
                                <div id="output2">
                                </div>
                            </div>
                            <label class="file_upload">
                                <span class="button helpSvgButton">Подгонка</span>
                                <input id="svgImgMain" type="file" name="imageMain" accept="image/*">
                            </label>
                        </div>
                        <button id="saveProperty" type="button" class="button" onclick="@if (empty($blade)) addBlade(); @else 
                        updateBlade({{ $blade->id }}); @endif return false">Сохранить</button>
                        @if (!empty($blade))
                            @if (!$presence) 
                                <button id="removeProperty" type="button" class="button" onclick="confirmationDropPart();">Удалить</button>
                                <div id='confirmationDropPart' class="wrapAlert">
                                    <div class="alert">
                                        <div class="boxAlert">
                                            <span class="captionAlert">Удалить?
                                            </span>
                                            <button id="confirmDrop" class="button leftButton" onclick="updateBlade({{ $blade->id }}, 0);  return false">Да</button>
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
<script src="{{ asset('admin/js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
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

        $('#pathProperty').attr('d',$('textarea[name=path]').val());
        $('textarea[name=path]').on('blur keyup paste input', function(){
            $('#pathProperty').attr('d',$('textarea[name=path]').val());
        });

        /*скрытие блока сообщения*/
        $('#close_alert').click(function(event){
            $('#success_message').css('display','none');
            if ($('#close_alert').attr('data-saved') == 1) {
                location.reload();
            }
        });

        $.each($(".admin_navigation a"), function() {

            if ($(this).attr("href") == "#"){
                $(this).parent().addClass('navPushed');
            }
        });
        
        document.getElementById('svgImgMain').addEventListener('change', helpImgSvg, false);
        
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