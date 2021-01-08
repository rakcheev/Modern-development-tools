@extends('layouts.userhead')

@section('content')
    <body class="addWorkerBody">
        <main>  
            @include('adminLeftColumn')   
            <div class="header">
                <h1 class="customerCaption">Добавить сотрудника</h1>
            </div>
            <div class="aboutUser">
                <form id="userAddForm" method="POST" class="propertyForm">
                    <div class="adresCustomer">
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Регион</span>
                            </dt>
                            <dd>
                                <input type="text" name="region" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Населенный пункт</span>
                            </dt>
                            <dd>
                                <input type="text" name="locality" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Улица</span>
                            </dt>
                            <dd>
                                <input type="text" name="street" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Дом</span>
                            </dt>
                            <dd>
                                <input type="text" name="house" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Квартира</span>
                            </dt>
                            <dd>
                                <input type="text" name="flat" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Почтовый индекс</span>
                            </dt>
                            <dd>
                                <input type="text" name="mailIndex" value="">
                            </dd>
                        </dl>
                    </div>
                    <div class="nameCustomer">

                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Должность</span>
                            </dt>
                            <dd>
                                <div class="new-select-style-wpandyou">
                                    <select size="bar" style="text-align: right;" class="statusSelect" name="status">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}">
                                                {{  $status->meaning }}
                                            </option>           
                                        @endforeach         
                                    </select>
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Телефон</span>
                            </dt>
                            <dd>
                                <input type="text" name="phone" value="" class="phone">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Имя</span>
                            </dt>
                            <dd>
                                <input type="text" name="name" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Фамилия</span>
                            </dt>
                            <dd>
                                <input type="text" name="surname" value="">
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix">
                            <dt class="dt-dotted">
                                <span>Отчество</span>
                            </dt>
                            <dd>
                                <input type="text" name="patronymic" value="">
                            </dd>
                        </dl>
                        <button type="button" class="button changeUserButton" onclick="saveWorker(); return false">Добавить</button>
                    </div>
                </form>
            </div>
        </main>
        @include('handleOldToken')        
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

        $(".phone").mask("+7(999) 999-99-99",{placeholder:"_"});
        $(" input[name='mailIndex']").mask("999999",{placeholder:"_"});

        /*Шаблон под фио (запрет цифр и символов кроме - ) + Первая буква заглавная*/
        $("input[name='name'], input[name='surname'],  input[name='patronymic']").on("change keyup input click", function(){
            if(this.value == "") return false; 
            if (this.value.match(/[^а-яA-Яa-zA-Z-\s]/g)){
            this.value = this.value.replace(/[^а-яА-Яa-zA-Z-\s]/g, '');
            }
            this.value=this.value[0].toUpperCase()+this.value.substring(1,this.length); 
        });

        /*Шаблон под первую заглавную букву*/
        $("input[name='street'], input[name='region'],  input[name='locality'], textarea").on("change keyup input click", function(){
            if(this.value == "") return false; 
            this.value=this.value[0].toUpperCase()+this.value.substring(1,this.length); 
        });

    });
</script>
@endsection