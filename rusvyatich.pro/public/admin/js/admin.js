/* коды ошибок */
var ACCESS_ERROR = 99;
var UNAUTH_ERROR = 98;
var CSRF_NOT_VALID = 97;
var KNIFE_IN_ORDER = 96;
var WRONG_LOGIN_PASSWORD = 95;
var UNCHANGE_ERROR = 94;

var isIE = /*@cc_on!@*/false || !!document.documentMode;
var isEdge = !isIE && !!window.StyleMedia;

/*Получение данных с формы obj_form*/
function getData(obj_form) {
    var hData;
    $(obj_form).each(function() {
    hData = $(this).serialize();
    });
    return hData;
}

var kFix = 0;
var firstLengthHandle = 100;
var typeHandle = 1;
var simmetrical = 0;

var handleFixBlade = 'M645,119 L630,119 Q597,119 591,139 L591, 300 L645,300z';
var handleFixBladeFultang = 'M645,119 Q607,135 591,156 Q593,150 585,170 L581,300 L645,300z';

/*Отрисовка рукоятки и больстера по длине и ширине рукояти */
function driveKnifeHandle(handleLength,originalHandleLength,kHeight){
    var bolsterLength = parseInt($('#bolster_svg').attr('data-width'));
    var transform_x = handleLength / originalHandleLength;
    var transform_y = (handleLength * kHeight) / (originalHandleLength * kHeight);
    $('#handle_svg').attr('transform','scale(' + transform_x + ' ' + transform_y + ') translate(' + (bolsterLength) + ' 0)');
    $('.klepka').attr('transform','scale(' + transform_x + ' ' + transform_y + ') translate(' + (bolsterLength) + ' 0)');
    $('#handle_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 40) + ')');
    var k = ((handleLength/4) - firstLengthHandle)/1.2;
    switch (typeHandle) {
        case 1:
            if(!simmetrical){
                $('#bolster_svg').attr('transform','scale(' + transform_y + ' ' + transform_x + ')');
                $('#bolster_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 40) + ')');
            } else {
                $('#bolster_svg').attr('transform','scale(' + transform_y + ' ' + transform_x*1.1145 + ')');
                $('#bolster_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 40+10) + ')');
            }
            if (!fultang) {
                $('#fixBlade').attr('d',handleFixBlade);
                $('#fixBlade').attr('transform', 'translate(0 ' + k +')');
            } else {
                $('#fixBlade').attr('d',handleFixBladeFultang);
                $('#fixBlade').attr('transform', 'translate(0 ' + (k+5.7) +')');
            }
            break;
        case 2:
            if(!simmetrical){
                $('#bolster_svg').attr('transform','scale(' + transform_x + ' ' + (transform_y/1.205) + ')');
                $('#bolster_wrap_svg').attr('transform', 'translate(-' + ((transform_x - 1) * 640) + ((((transform_y - 1) * 33-6.7) >0) ? (' -'+((transform_y - 1) * 33-6.7)) : (' '+(-((transform_y - 1) * 33-6.7)))) + ')');
            } else {
                $('#bolster_svg').attr('transform','scale(' + transform_x + ' ' + (transform_y*1.2767/1.205) + ')');
                $('#bolster_wrap_svg').attr('transform', 'translate(-' + ((transform_x - 1) * 640) + ((((transform_y - 1) * 33-6.7) >0) ? (' -'+((transform_y - 1) * 33+15.9)) : (' '+(-((transform_y - 1) * 33+15.9)))) + ')');
            }
            if (!fultang) {
                $('#fixBlade').attr('d',handleFixBlade);
                $('#fixBlade').attr('transform', 'translate(0 ' + (k/1.205-10.5) +')');
            } else {
                $('#fixBlade').attr('d',handleFixBladeFultang);
                $('#fixBlade').attr('transform', 'translate(0 ' + (k/1.205-9.5) +')');
            }
            break;
        case 3:
            $('#bolster_svg').attr('transform','scale(' + transform_x + ' ' + (transform_y/1.095) + ')');
            $('#bolster_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 37-3.85) + ')'); 
            $('#fixBlade').attr('transform', 'translate(0 ' + (k/1.095-3.5) +')');
            if (!fultang) {
                $('#fixBlade').attr('d',handleFixBlade);
            } else {
                $('#fixBlade').attr('d',handleFixBladeFultang);
            }
            break;
        case 4:
            $('#bolster_svg').attr('transform','scale(' + transform_x + ' ' + (transform_y/0.895) + ')');
            $('#bolster_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 45+4.43) + ')');
            if (!fultang) {
                $('#fixBlade').attr('d',handleFixBlade);
                $('#fixBlade').attr('transform', 'translate(0 ' + (k/0.895+9) +')');
            } else {
                $('#fixBlade').attr('d',handleFixBladeFultang);
                $('#fixBlade').attr('transform', 'translate(0 ' + (k/0.895+15) +')');
            }
            break;
    }
}

/*Отрисовка клинка по длине, ширне*/
function driveKnife(bladeLength, bladeHeight, originalBladeLength, originalBladeHeight){
    var transform_x = bladeLength / originalBladeLength;
    var transform_y = bladeHeight / originalBladeHeight;
    $('#blade_svg').attr('transform','scale(' + transform_x + ' ' + transform_y + ') translate(0.3 0)');
    $('#blade_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 40) + ')');
}

/*Обработка старого токена*/                
function handleOldToken(data, reloaded){
    if (reloaded === undefined) {
        reloaded = false;
    }
    var errorFlag = false;
    if (data['success'] == 0 && data['res'] == CSRF_NOT_VALID) {
        if (reloaded) location.reload();
        $('#token_message .captionAlert').text('Сессия устарела. Пожалуйста перезагрузите страницу');
        $('#token_message').css('display', 'block');
        $('body').addClass('unclicked');
        errorFlag = true;
    }
    return errorFlag;
}


/*Обработка неуспешного ajax запроса*/     
function handleAjaxResponse(data, reloaded){
    if (reloaded === undefined) {
        reloaded = false;
    }
    var errorFlag = false;
    if (data['success'] == 0){
        if (reloaded) location.reload();
        if (data['note']) {
            $('#alert_error .captionAlert').text(data['note']);
            $('#error_message').css('display','block');
            errorFlag = true;
            return errorFlag;
        }
        switch(data['res']) {
            case KNIFE_IN_ORDER:
                $('#alert_error .captionAlert').text('Нож в заказе');
                $('#error_message').css('display','block');
                break;
            case UNAUTH_ERROR:
                $('#alert_error .captionAlert').text('Ошибка аутентификации');
                $('#error_message').css('display','block');
                location.reload();
                errorFlag = true;
                break;
            case ACCESS_ERROR:
                $('#alert_error .captionAlert').text('Вы не можете совершать данные действия');
                $('#error_message').css('display','block');
                errorFlag = true;
                break;
            case UNCHANGE_ERROR:
                $('#alert_error .captionAlert').text('Уже изменен');
                $('#error_message').css('display','block');
                $('#alert_error').addClass('reloadThis');
                break;
            case CSRF_NOT_VALID: 
                $('#token_message .captionAlert').text('Сессия устарела. Пожалуйста перезагрузите страницу');
                $('#token_message').css('display', 'block');
                $('body').addClass('unclicked');
                break;

            default:
                $('#token_message .captionAlert').text('Ошибка. Пожалуйста перезагрузите страницу');
                $('#token_message').css('display','block');
                $('body').addClass('unclicked');
        }
    }
    return errorFlag;
}

function validateEmail(address) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(reg.test(address) == false) {
        return false;
    } else {
        return true;
    }
}

var PrevSteelColor = false;
/*Установка цвета клинку/ручке*/
function setTexture(id, part){
    $.ajax({
        type:"POST",
        url:"/getTexture",
        cache: true,
        data:{
            "id": id,
            "part": part
        },
        dataType:"json"
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        switch(part){
            case 1:
                PrevSteelColor = data['color'];
                $('#blade_svg').attr('fill',data['color']);
                $('#steelImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#steelImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                img = document.getElementById('steelImg');
                if (isEdge || isIE) {
                    $('#blade_svg').attr('fill','url(#patternSteel)');
                }
                img.addEventListener('load', function() { 
                    $('#blade_svg').attr('fill','url(#patternSteel)');
                });
                if($('.imageAddition').attr('data-image')) {
                    setTimeout(setAdditionTexture($('.imageAddition').attr('data-image')),1000);
                }
                break;
            case 2:
                $('#handle_svg').attr('fill',data['color']);
                $('#handleImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#handleImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                if (isEdge || isIE) {
                    $('#handle_svg').attr('fill','url(#patternHandle)');
                }
                img = document.getElementById('handleImg');
                img.addEventListener('load', function() { 
                    $('#handle_svg').attr('fill','url(#patternHandle)');
                });
                break;
        }
    }).fail(function (xhr,status,error){
        setTexture(id, part);  
        console.log(error);
    });
}

/*Получение части ножа svg path*/
function getPath(id, part){
    $.ajax({
        type:"POST",
        url:"/getPath",
        cache: true,
        data:{
            "id": id,
            "part": part
        },
        dataType:"json"
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        if(data){
            switch(part){
            case 1:
                $('#blade_svg').attr('d',data['path']);
                break;
            case 2:
                simmetrical=data['simmetrical'];
                if (data['id'] == 5) {
                    $('#patternHandle').attr('x', '-298');
                    $('#handleImg').attr('height', '270');
                } else {
                    $('#patternHandle').attr('x', '-285');
                    $('#handleImg').attr('height', '250');
                }
                $('#bolster_svg').attr('d',data['path']);
                $('#bolster_svg').attr('fill',data['color']);
                $('#bolsterImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#bolsterImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                $('#bolster_svg').attr('data-width',data['width']);
                img = document.getElementById('bolsterImg');
                if (isEdge || isIE) {
                    $('#bolster_svg').attr('fill','url(#patternBolster)');
                }
                img.addEventListener('load', function() { 
                    $('#bolster_svg').attr('fill','url(#patternBolster)');
                });
                var lengthHandle=$('#handle_length').text()*4;
                driveKnifeHandle(lengthHandle,280,0.22);
                break;
            case 3: 
                if (!fultang) {
                    $('#handle_svg').attr('d',data['path']);
                } else {
                    $('#handle_svg').attr('d',data['pathFultang']);
                    $('#klepka').attr('d',data['pathKlepka']);
                }
                handleFixBlade = data['pathFixBlade'];
                handleFixBladeFultang = data['pathFixBladeFultang'];
                var lengthHandle=$('#handle_length').text()*4;
                typeHandle = data['heightHandle'];
                driveKnifeHandle(lengthHandle,280,0.22);
                break;
        }
    }

    }).fail(function (xhr,status,error){  
        console.log(error);
        getPath(id, part);
    });
}
function setAdditionTexture(image) {
    $('#blade_svg').attr('fill', PrevSteelColor);
    $('#steelImg').attr('href', patternPath + image + '?' + VERSION);
    $('#steelImg').attr('xlink:href', patternPath + image + '?' + VERSION);
    img = document.getElementById('steelImg');
    if (isEdge || isIE) {
        $('#blade_svg').attr('fill','url(#patternSteel)');
    }
    img.addEventListener('load', function() { 
        $('#blade_svg').attr('fill','url(#patternSteel)');
    });
}

/*Скрытие блока просмотра Svg ножа*/
function closeSvgWindow() {
    $('#svgWindow').css('display','none');
    $('#close_svg').css('display', 'none');
    $('#svgWindow .test_svg').remove();
}
function downloadSvg(orderId){
    var svgData = $('#svg').html();
    var bladePath = $("#blade_svg").attr('d');
    var bolsterPath = $("#bolster_svg").attr('d');
    var handlePath = $("#handle_svg").attr('d');
    var bolsterWidth = $("#bolster_svg").attr('data-width');
    var handleMaterial = $.trim($("#handleMaterial").text());
    var handle = $.trim($("#handle").text());
    var blade = $.trim($("#blade").text());
    var bolster = $.trim($("#bolster").text());
    var steel = $.trim($("#steel").text());
    var handleLength = $('#handle_length').text();
    var bladeLength = $('#blade_length').text();
    var bladeHeight = $('#blade_height').text();
    var buttWidth = $('#butt_width').text();
    var bladeTransform = $("#blade_svg").attr('transform');
    var bolsterTransform = $("#bolster_svg").attr('transform');
    var handleTransform = $("#handle_svg").attr('transform');
    var fixTransform = $("#fixBlade").attr('transform');

    var nameHandle = $('#handleImg').attr('href').split('/');
    nameHandle = (nameHandle[nameHandle.length-1]).split('?');
    nameHandle = nameHandle[0];
    var nameSteel = $('#steelImg').attr('href').split('/');
    nameSteel = (nameSteel[nameSteel.length-1]).split('?');
    nameSteel = nameSteel[0];
    var nameBolster = $('#bolsterImg').attr('href').split('/');
    nameBolster = (nameBolster[nameBolster.length-1]).split('?');
    nameBolster = nameBolster[0];
    var bolsterWrapTransform = $("#bolster_wrap_svg").attr('transform');
    var bladeWrapTransform = $("#blade_wrap_svg").attr('transform');
    var handleWrapTransform = $("#handle_wrap_svg").attr('transform');
    window.location.href="/downloadSvg/"+bladePath+"/"+bolsterPath+"/"+handlePath+"/"+bolsterWidth+
    "/"+handle+"/"+blade+"/"+handleMaterial+"/"+bolster+"/"+steel+"/"+bladeLength+"/"+bladeHeight+"/"+buttWidth+"/"+handleLength+
    "/"+bladeTransform+"/"+bolsterTransform+"/"+handleTransform+"/"+nameSteel+"/"+nameBolster+"/"+nameHandle+"/"+bolsterWrapTransform+"/"+bladeWrapTransform+"/"+handleWrapTransform+"/"+fixTransform+"/"+orderId;
  
}


/*Сылка на страницу продукта индивидуального*/
function toProduct(id){
    window.location.href='/home/knifes/individual/'+id;
}
/*Сылка на страницу продукта*/
function toProductSerial(id){
    window.location.href='/home/knifes/serial/'+id;
}

/*переопределение высоты блока right_product от высоты описания продукта
function resizeDescriptionPopup(){
    var nameHeight=$('#nameKnife').height();
    var firstHeight=$('#popup_first_desc').height();
    var secondHeight=$('#popup_second_desc').height();
    if( $('.right_product').css('float')=='none'){
        if($('.product_popup_description').css('float')=='none'){
            $('.right_product').height(nameHeight+firstHeight+secondHeight+195);
        }else{
            if(firstHeight>secondHeight){
                $('.right_product').height(nameHeight+firstHeight+195);
            }else{
                $('.right_product').height(nameHeight+secondHeight+195);
            }
        }
    }else{
        if($('.phoneLineClose').is(':visible')){
            $('.right_product').height('557px');
        }else{
            $('.right_product').height('460px');
        }
    }
}*/

/*скрытие блока картинки, выведенной во весь экран*/
function closeMainImg(){
    $('#wrap_for_product').css('display','none');
    $('#wrap').css('display','block');
}

/*закрытие продукта*/
function closeProduct() {
    $('.choose_view img').removeClass('view_pushed');
    $('.choose_view img:first-child').addClass('view_pushed');
    $('#wrap').css('display','none');
}

/*После esc*/
function doEsc(){
    if ($('body').hasClass('unclicked') || $('#error_message').is(':visible') || $('#note_message').is(':visible')) return false;
    if($('#wrap_for_product').is(':visible')){
        closeMainImg();
    }else if($('#wrap').is(':visible')){
        closeProduct();
    }else if($('#viewMessageImg').is(':visible')){
        $('#viewMessageImg').css('display', 'none');
    }
}

/*Ajax получение продукта
function toProductForUser(id){

    $.ajax({
        type:"POST",
        url:"/getKnifeForCustomer/"+id,
        cache: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        if (data['success'] == 1) {
            var knife = data['knife'];
                if ($(window).width()>1000) {
                    $('.main_product_img').attr('src', pathImage +"imgStorage/" + knife['link_of_image']);
                } else {
                    $('.main_product_img').attr('src',  pathImage +"imgStoragePhone/" + knife['link_of_image']);
                }
                $('.choose_view img:first-child').attr('src',pathImage + "imgStorageMin/" + knife['link_of_image']);
                $('.choose_view img:last-child').attr('src', pathImage + "imgStorageMin/" + knife['help_image']);
                $('.product_popup_description p').text(knife['description']);
                $('.steel dd').text(knife['steel']);
                $('.length dd').text(knife['blade_length']+" мм");
                $('.width dd').text(knife['blade_width']+" мм");
                $('.thickness dd').text(knife['blade_thickness']+" мм");
                $('.handle_length_dl dd').text(knife['handle_length']+" мм");
                $('.cost_popup').text("Цена: "+knife['price']+" p.");
                $('#nameKnife').text(knife['name']);
                $('.choose_view img').load(function(){
                    $('#wrap').css('display','block');
                    $(document).trigger('mousemove');
                    resizeDescriptionPopup();
                    document.getElementsByClassName('product_itself_popup')[0].scrollTop = 0; 
                });
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}
*/
/*Даллее на окно заметки*/
function closeAboutDialog() {
    file = new FormData();
    $('.successShowed').css('display', 'none');
    if ($('#showOrNot').is(":checked")) {
        var show = 1;
    } else {
        var show = 2;
        return;
    }
    file.append('show', show);     
    $.ajax({
        type:"POST",
        url:"/closeWindow",
        cache: false,
        data: file,
        processData: false,
        dataType:"json"
    }).done(function(data){
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Сылка на страницу добавления сотрудника*/
function addWorker(){
    window.location.href='/home/workers/add';
}       

/*Сохранение работника*/
function saveWorker(){
    var dataForm = getData('#userAddForm');
    $.ajax({
        type:"POST",
        url:"/saveWorker",
        cache: false,
        data: dataForm,
        dataType:"json"
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
            location.reload();
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Сохранение клинка*/
function addBlade() {
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    file.append('path', $('.propertyForm textarea[name=path]').val());
    file.append('hardness', $('.propertyForm select[name=hardness]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=bent]').is(":checked")) {
        var bentProp = 1;
    } else {
        var bentProp = 2;
    }
    if ($('.propertyForm input[name=free]').is(":checked")) {
        var freeProp = 1;
    } else {
        var freeProp = 2;
    }
    file.append('viewable', viewableProp);
    file.append('bent', bentProp);    
    file.append('free', freeProp);        

    $.ajax({
        type:"POST",
        url:"/addBlade",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        if (data['success']==1) {
            if (data['res']==2) {
                window.location.href = document.referrer;
            } else{
                $('#alert_message .captionAlert').text('Сохранено!');
                $('#close_alert').attr('data-saved', 1);
                $('#success_message').css('display','block');
            }
        } else {
            if(data['res'] == ACCESS_ERROR) {
                $('#alert_message .captionAlert').text('Вне вашей компетенции');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            } else if(data['note']){
                $('#alert_message .captionAlert').text(data['note']);
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            } else {
                $('#alert_message .captionAlert').text('Ошибка Сохранения!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            }
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*изменение клинка*/
function updateBlade(id, action) {
    if (action === undefined) {
        action = 1;
    }
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    file.append('path', $('.propertyForm textarea[name=path]').val());
    file.append('hardness', $('.propertyForm select[name=hardness]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=bent]').is(":checked")) {
        var bentProp = 1;
    } else {
        var bentProp = 2;
    }
    if ($('.propertyForm input[name=free]').is(":checked")) {
        var freeProp = 1;
    } else {
        var freeProp = 2;
    }
    file.append('viewable', viewableProp);
    file.append('bent', bentProp);    
    file.append('free', freeProp);        
    $.ajax({
        type:"POST",
        url:"/updateBlade/"+id+"/"+action,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        if (data['success']==1) {
            if (data['res']==2) {
                window.location.href = document.referrer;
            } else{
                $('#alert_message .captionAlert').text('Сохранено!');
                $('#close_alert').attr('data-saved', 1);
                $('#success_message').css('display','block');
            }
        } else {
            if(data['res'] == ACCESS_ERROR) {
                $('#alert_message .captionAlert').text('Вне вашей компетенции');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            } else if(data['note']){
                $('#alert_message .captionAlert').text(data['note']);
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            } else {
                $('#alert_message .captionAlert').text('Ошибка Сохранения!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            }
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


/*Сохранение стали*/
function addSteel() {
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('imageMain', $('#fileMain')[0].files[0]);
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    if ($('.propertyForm input[name=damask]').is(":checked")) {
        var damask = 2;
    } else {
        var damask = 1;
    }
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    file.append('damask', damask);
    file.append('viewable', viewableProp);
    file.append('color', $('.propertyForm input[name=color]').val());
    $.ajax({
        type:"POST",
        url:"/addSteel",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        switch(data['success']) {
            case 0: 
                if(data['res'] == ACCESS_ERROR) {
                    $('#alert_message .captionAlert').text('Вне вашей компетенции');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                } else {
                    $('#alert_message .captionAlert').text('Ошибка сохранения!');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                }
                break;
            case 1: 
                $('#alert_message .captionAlert').text('Сохранено!');
                $('#close_alert').attr('data-saved', 1);
                $('#success_message').css('display','block');
                break;
            case 2: 
                $('#alert_message .captionAlert').text('Размер картинки велик!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
                break;    
            case 3: 
                $('#alert_message .captionAlert').text('Разрешение картинки должно быть 720x225!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
                break;
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение стали*/
function updateSteel(id, action) {
    if (action === undefined) {
        action = 1;
    }
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('imageMain', $('#fileMain')[0].files[0]);
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    if ($('.propertyForm input[name=damask]').is(":checked")) {
        var damask = 2;
    } else {
        var damask = 1;
    }
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    file.append('damask', damask);
    file.append('viewable', viewableProp);
    file.append('color', $('.propertyForm input[name=color]').val());
    $.ajax({
        type:"POST",
        url:"/updateSteel/"+id+"/"+action,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        switch(data['success']) {
            case 0: 
                if(data['res'] == ACCESS_ERROR) {
                    $('#alert_message .captionAlert').text('Вне вашей компетенции');
                    $('#success_message').css('display','block');
                } else {
                    $('#alert_message .captionAlert').text('Ошибка изменения!');
                    $('#success_message').css('display','block');
                }
                break;
            case 1: 
                if (data['res']==2) {
                    window.location.href = document.referrer;
                } else {
                    $('#alert_message .captionAlert').text('Изменение успешно!');
                    $('#success_message').css('display','block');
                }
                break;
            case 2: 
                $('#alert_message .captionAlert').text('Размер картинки велик!');
                $('#success_message').css('display','block');
                break;    
            case 3: 
                $('#alert_message .captionAlert').text('Разрешение картинки должно быть 720x225!');
                $('#success_message').css('display','block');
                break;
        } 
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


/*Сохранение больстера*/
function addBolster() {
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('imageMain', $('#fileMain')[0].files[0]);
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('width', $('.propertyForm input[name=width]').val());
    file.append('path', $('.propertyForm textarea[name=path]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=restrict]').is(":checked")) {
        var restrictProp = 2;
    } else {
        var restrictProp = 1;
    }
    file.append('viewable', viewableProp);
    file.append('restrict', restrictProp);
    file.append('color', $('.propertyForm input[name=color]').val());
    $.ajax({
        type:"POST",
        url:"/addBolster",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        switch(data['success']) {
            case 0: 
                if(data['res'] == ACCESS_ERROR) {
                    $('#alert_message .captionAlert').text('Вне вашей компетенции');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                } else {
                    $('#alert_message .captionAlert').text('Ошибка сохранения!');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                }
                break;
            case 1: 
                $('#alert_message .captionAlert').text('Сохранено!');
                $('#close_alert').attr('data-saved', 1);
                $('#success_message').css('display','block');
                break;
            case 2: 
                $('#alert_message .captionAlert').text('Размер картинки велик!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
                break;    
            case 3: 
                $('#alert_message .captionAlert').text('Разрешение картинки должно быть 930x400!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
                break;
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение больстера*/
function updateBolster(id, action) {
    if (action === undefined) {
        action = 1;
    }
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('imageMain', $('#fileMain')[0].files[0]);
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('width', $('.propertyForm input[name=width]').val());
    file.append('path', $('.propertyForm textarea[name=path]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=restrict]').is(":checked")) {
        var restrictProp = 2;
    } else {
        var restrictProp = 1;
    }
    file.append('viewable', viewableProp);
    file.append('restrict', restrictProp);
    file.append('color', $('.propertyForm input[name=color]').val());
    $.ajax({
        type:"POST",
        url:"/updateBolster/"+id+"/"+action,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        switch(data['success']) {
            case 0: 
                if(data['res'] == ACCESS_ERROR) {
                    $('#alert_message .captionAlert').text('Вне вашей компетенции');
                    $('#success_message').css('display','block');
                } else {
                    $('#alert_message .captionAlert').text('Ошибка изменения!');
                    $('#success_message').css('display','block');
                }
                break;
            case 1:
                if (data['res']==2) {
                    window.location.href = document.referrer;
                } else {
                    $('#alert_message .captionAlert').text('Изменение успешно!');
                    $('#success_message').css('display','block');
                }
                break;
            case 2:
                $('#alert_message .captionAlert').text('Размер картинки велик!');
                $('#success_message').css('display','block');
                break;
            case 3:
                $('#alert_message .captionAlert').text('Разрешение картинки должно быть 930x400!');
                $('#success_message').css('display','block');
                break;
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


/*Сохранение рукояти*/
function addHandle() {
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    file.append('path', $('.propertyForm textarea[name=path]').val());
    file.append('pathFultang', $('.propertyForm textarea[name=pathFultang]').val());
    file.append('pathKlepka', $('.propertyForm textarea[name=pathKlepka]').val());
    file.append('pathFixBlade', $('.propertyForm textarea[name=pathFixBlade]').val());
    file.append('pathFixBladeFultang', $('.propertyForm textarea[name=pathFixBladeFultang]').val());
    file.append('heightPxHandle', $('.propertyForm select[name=heightPxHandle]').val());
    file.append('hardness', $('.propertyForm select[name=hardness]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=restrict]').is(":checked")) {
        var restrictProp = 2;
    } else {
        var restrictProp = 1;
    }
    file.append('viewable', viewableProp);
    file.append('restrict', restrictProp);
    $.ajax({
        type:"POST",
        url:"/addHandle",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        if (data['success']==1) {
            $('#alert_message .captionAlert').text('Сохранено!');
            $('#close_alert').attr('data-saved', 1);
            $('#success_message').css('display','block');
        } else { 
            if(data['res'] == ACCESS_ERROR) {
                $('#alert_message .captionAlert').text('Вне вашей компетенции');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            } else {
                $('#alert_message .captionAlert').text('Ошибка сохранения!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            }
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение рукояти*/
function updateHandle(id, action) {
    if (action === undefined) {
        action = 1;
    }
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    file.append('path', $('.propertyForm textarea[name=path]').val());
    file.append('pathFultang', $('.propertyForm textarea[name=pathFultang]').val());
    file.append('pathKlepka', $('.propertyForm textarea[name=pathKlepka]').val());
    file.append('heightPxHandle', $('.propertyForm select[name=heightPxHandle]').val());
    file.append('pathFixBlade', $('.propertyForm textarea[name=pathFixBlade]').val());
    file.append('pathFixBladeFultang', $('.propertyForm textarea[name=pathFixBladeFultang]').val());
    file.append('hardness', $('.propertyForm select[name=hardness]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=restrict]').is(":checked")) {
        var restrictProp = 2;
    } else {
        var restrictProp = 1;
    }
    file.append('viewable', viewableProp);
    file.append('restrict', restrictProp);
    $.ajax({
        type:"POST",
        url:"/updateHandle/"+id+"/"+action,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        if (data['success']==1) {
            if (data['res']==2) {
                window.location.href = document.referrer;
            } else{
                $('#alert_message .captionAlert').text('Сохранено!');
                $('#close_alert').attr('data-saved', 1);
                $('#success_message').css('display','block');
            }
        } else { 
            if(data['res'] == ACCESS_ERROR) {
                $('#alert_message .captionAlert').text('Вне вашей компетенции');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            } else {
                $('#alert_message .captionAlert').text('Ошибка изменения!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
            }
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Сохранение материала рукояти*/
function addHandleMaterial() {
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('imageMain', $('#fileMain')[0].files[0]);
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=nabor]').is(":checked")) {
        var naborProp = 1;
    } else {
        var naborProp = 0;
    }
    file.append('nabor', naborProp);
    file.append('viewable', viewableProp);
    file.append('color', $('.propertyForm input[name=color]').val());
    $.ajax({
        type:"POST",
        url:"/addHandleMaterial",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        switch(data['success']) {
            case 0:
                if(data['res'] == ACCESS_ERROR) {
                    $('#alert_message .captionAlert').text('Вне вашей компетенции');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                } else {
                    $('#alert_message .captionAlert').text('Ошибка сохранения!');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                }
                break;
            case 1: 
                $('#alert_message .captionAlert').text('Сохранено!');
                $('#close_alert').attr('data-saved', 1);
                $('#success_message').css('display','block');
                break;
            case 2: 
                $('#alert_message .captionAlert').text('Размер картинки велик!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
                break;    
            case 3: 
                $('#alert_message .captionAlert').text('Разрешение картинки должно быть 830x350!');
                $('#close_alert').attr('data-saved', 0);
                $('#success_message').css('display','block');
                break;
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение материала рукояти*/
function updateHandleMaterial(id, action) {
    if (action === undefined) {
        action = 1;
    }
    $('#saveProperty').prop('disabled', true);
    $("#saveProperty").addClass('button_pushed');
    file = new FormData();
    file.append('imageMain', $('#fileMain')[0].files[0]);
    file.append('name', $('.propertyForm input[name=name]').val());
    file.append('price', $('.propertyForm input[name=price]').val());
    file.append('popularity', $('.propertyForm input[name=popularity]').val());
    file.append('description', $('.propertyForm textarea[name=description]').val());
    if ($('.propertyForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    if ($('.propertyForm input[name=nabor]').is(":checked")) {
        var naborProp = 1;
    } else {
        var naborProp = 0;
    }
    file.append('nabor', naborProp);
    file.append('viewable', viewableProp);
    file.append('color', $('.propertyForm input[name=color]').val());
    $.ajax({
        type:"POST",
        url:"/updateHandleMaterial/"+id+"/"+action,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleOldToken(data)) return;
        $('#saveProperty').prop('disabled', false);
        $("#saveProperty").removeClass('button_pushed');
        switch(data['success']) {
            case 0: 
                if(data['res'] == ACCESS_ERROR) {
                    $('#alert_message .captionAlert').text('Вне вашей компетенции');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                } else {
                    $('#alert_message .captionAlert').text('Ошибка изменения!');
                    $('#close_alert').attr('data-saved', 0);
                    $('#success_message').css('display','block');
                }
                break;
            case 1: 
                if (data['res']==2) {
                    window.location.href = document.referrer;
                } else {
                    $('#alert_message .captionAlert').text('Изменение успешно!');
                    $('#success_message').css('display','block');
                }
                break;
            case 2:
                $('#alert_message .captionAlert').text('Размер картинки велик!');
                $('#success_message').css('display','block');
                break;    
            case 3: 
                $('#alert_message .captionAlert').text('Разрешение картинки должно быть 830x350!');
                $('#success_message').css('display','block');
                break;
        } 
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


/*Изменение высоты поля письма от ввода текста*/
function changeLettersHeight(newest){
    if (newest === undefined) {
        newest = false;
    }
    calculatedHeight  = $(window).height()-$('.messageBlock').height()-$('#eventsCaption').outerHeight()-5;
    $('#lettersScroll').css('height', calculatedHeight);
    if(calculatedHeight < $('#letters').height()){
        $('#lettersScroll').css('overflow-y', 'scroll');
    } else {
        $('#lettersScroll').css('overflow-y', 'visible');
    }
    $('#message').getNiceScroll().resize();
    if($('#message').height() >= 200){
        $('.letter').css('overflow-y', 'scroll');
    } else {
        $('.letter').css('overflow-y', 'visible');
    }
    $('#lettersScroll').scrollTop($('#letters').height());
}

//флаг картинки
var flagForm = true;

function helpImgSvg(evt) {
    var file = evt.target.files;
    var f = $('input[type=file]')[0].files[0];
    var tmpName =f['name'];
    var tmpSize = f['size'];
    var maxSize = 2*1024*1024;
        //var loadInterval= setInterval(loadView, 1000);
        if (!f.type.match('image.*')) {
            $('#note_error .captionAlert').text('Только картинки, пожалуйста...');
            $('#note_message').css('display','block');
        }else{
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var span = document.createElement('span');
                    $('#output2 span').remove();
                    span.innerHTML = ['<img id="imgForSvg" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
                    document.getElementById('output2').insertBefore(span, null);
                    var x=$('#imgForSvg')[0];
                    x.addEventListener('load', showPreview,false);
                };
            })(f);
            reader.readAsDataURL(f);
        }
}

function handleFileSelect(evt) {
    var file = evt.target.files; // FileList object
    var f = $('input[type=file]')[0].files[0];
    var tmpName =f['name'];
    var tmpSize = f['size'];
    var maxSize = 2*1024*1024;
    if (tmpSize >= maxSize || tmpSize == 0){
        $('#note_error .captionAlert').text('Картинка должны быть меньше 2 мб');
        $('#note_message').css('display','block');
    }else{
        $('.file_upload .button').text('загрузка');
        //var loadInterval= setInterval(loadView, 1000);
        if (!f.type.match('image.*')) {
            $('#note_error .captionAlert').text('Только картинки, пожалуйста...');
            $('#note_message').css('display','block');
        }else{
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var span = document.createElement('span');
                    $('#output span').remove();
                    span.innerHTML = ['<img id="preview" class="thumb" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
                    document.getElementById('output').insertBefore(span, null);
                    var x=$('#preview')[0];
                    x.addEventListener('load', showPreview,false);
                };
            })(f);
            reader.readAsDataURL(f);
            if(f){
                flagForm=false;
            }
        }
    }
}

var flag1File=true;
var flag2File = true;
var flag3File = true;
var flag4File = true;
var flag5File = true;
var flag6File = true;

function handleFileSelectPhoto(evt,id) {
    if (id === undefined) {
        id = 1;
    }
    var file = evt.target.files; // FileList object
    var f = $('#file'+id)[0].files[0];
    var tmpName =f['name'];
    var tmpSize = f['size'];
    var maxSize = 2*1024*1024;
    if (tmpSize >= maxSize || tmpSize == 0){
        $('#note_error .captionAlert').text('Картинка должны быть меньше 2 мб');
        $('#note_message').css('display','block');
    }else{
        $('mainImg .file_upload .button').text('загрузка');
        if (!f.type.match('image.*')) {
            $('#note_error .captionAlert').text('Только картинки, пожалуйста...');
            $('#note_message').css('display','block');
        }else{
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var span = document.createElement('span');
                    $('#output'+id+' span').remove();
                    span.innerHTML = ['<img id="preview'+id+'" class="thumb" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
                    document.getElementById('output'+id+'').insertBefore(span, null);
                    var x=$('#preview'+id)[0];
                    x.addEventListener('load', showPreviewKnife(id), false);
                };
            })(f);
            reader.readAsDataURL(f);
            if(f){
                switch(id){
                    case 1: flag1File=false;
                        break;
                    case 2: flag2File=false;
                        break;
                    case 3: flag3File=false;
                        break;
                    case 4: flag4File=false;
                        break;
                    case 5: flag5File=false;
                        break;
                    case 6: flag6File=false;
                        break;
                }
            }
        }
    }
}


/*Показ превью загружнной картинки ножа с id*/
function showPreviewKnife(id){
    $('#output'+id).css('z-index','5');
    $('.row'+id).css('display','block');
    $('.photo'+id+' .file_upload').css('display', 'none');
}

flagMainFile=true;

function handleFileSelectMain(evt) {
    var file = evt.target.files; // FileList object
    var f = $('#fileMain')[0].files[0];
    var tmpName =f['name'];
    var tmpSize = f['size'];
    var maxSize = 2*1024*1024;
    if (tmpSize >= maxSize || tmpSize == 0){
        $('#note_error .captionAlert').text('Картинка должны быть меньше 2 мб');
        $('#note_message').css('display','block');
    }else{
        $('mainImg .file_upload .button').text('загрузка');
        if (!f.type.match('image.*')) {
            $('#note_error .captionAlert').text('Только картинки, пожалуйста...');
            $('#note_message').css('display','block');
        }else{
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var span = document.createElement('span');
                    $('#outputMain span').remove();
                    span.innerHTML = ['<img id="previewMain" class="thumb" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
                    document.getElementById('outputMain').insertBefore(span, null);
                    var x=$('#previewMain')[0];
                    x.addEventListener('load', showPreviewMain, false);
                };
            })(f);
            reader.readAsDataURL(f);
            if(f){
                flagMainFile=false;
            }
        }
    }
}

/*Показ превью загружнной картинки главной*/
function showPreviewMain(){
    $('#outputMain').css('z-index','5');
    $('.rowMain').css('display','block');
    $('.mainImg .file_upload').css('display', 'none');
}

/*Показ превью загружнной картинки*/
function showPreview(){ 
    $('#file').blur();
    $('#message').css('width', 'calc(100% - 57px)');
    $('#message').css('margin-left', '0');
    $('#output').css('z-index','5');
    $('.row').css('display','block');
    changeLettersHeight();
}

/*Скрытие загружнной картинки*/
function hidePreview(){
    $('#message').css('margin-left', '');
    $('#message').css('width', 'calc(100% - 90px)');
    $('#output').css('z-index','-5');
    $('.row').css('display','none');
    $("#file")[0].value = "";
    changeLettersHeight();
}

/*Сохранение нового ножа*/
function saveKnifee() {
    $('#saveKnife').prop('disabled', true);
    $('#saveKnife').addClass('button_pushed');
    file = new FormData();
    var Image1,Image2,Image3,Image4,Image5,Image6 = '';
    if (!flag1File) Image1 = $('#file1')[0].files[0];
    if (!flag2File) Image2 = $('#file2')[0].files[0];
    if (!flag3File) Image3 = $('#file3')[0].files[0];
    if (!flag4File) Image4 = $('#file4')[0].files[0];
    if (!flag5File) Image5 = $('#file5')[0].files[0];
    if (!flag6File) Image6 = $('#file6')[0].files[0];
    file.append('image1', Image1);
    file.append('image2', Image2);
    file.append('image3', Image3);
    file.append('image4', Image4);
    file.append('image5', Image5);
    file.append('image6', Image6);
    file.append('name', $('#knifeForm input[name=name]').val());
    file.append('steel', $('#knifeForm select[name=steel]').val());
    file.append('blade_length', $('#knifeForm input[name=blade_length]').val());
    file.append('blade_width', $('#knifeForm input[name=blade_width]').val());
    file.append('blade_thickness', $('#knifeForm input[name=blade_thickness]').val());
    file.append('handle_length', $('#knifeForm input[name=handle_length]').val());
    file.append('price', $('#knifeForm input[name=price]').val());
    file.append('status', $('#knifeForm select[name=status]').val());
    file.append('handle', $('#knifeForm textarea[name=handle]').val());
    file.append('description', $('#knifeForm textarea[name=description]').val());
    $.ajax({
        type:"POST",
        url:"/saveKnife",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        $('#saveKnife').prop('disabled', false);
        $('#saveKnife').removeClass('button_pushed');
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
           location.reload();
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение ножа индивидуального*/
function updateKnife(id) {
    $('#saveKnife').prop('disabled', true);
    $('#saveKnife').addClass('button_pushed');
    file = new FormData();
    var Image1 = '',Image2 = '',Image3 = '',Image4 = '',Image5 = '',Image6 = '';
    if (!flag1File) Image1 = $('#file1')[0].files[0];
    if (!flag2File) Image2 = $('#file2')[0].files[0];
    if (!flag3File) Image3 = $('#file3')[0].files[0];
    if (!flag4File) Image4 = $('#file4')[0].files[0];
    if (!flag5File) Image5 = $('#file5')[0].files[0];
    if (!flag6File) Image6 = $('#file6')[0].files[0];
    var drop1 = 0, drop2 = 0, drop3 = 0, drop4 = 0, drop5 = 0, drop6 = 0;
    if ($('#drop1').is(":checked")) drop1 = 1;
    if ($('#drop2').is(":checked")) drop2 = 1;
    if ($('#drop3').is(":checked")) drop3 = 1;
    if ($('#drop4').is(":checked")) drop4 = 1;
    if ($('#drop5').is(":checked")) drop5 = 1;
    if ($('#drop6').is(":checked")) drop6 = 1;
    file.append('image1', Image1);
    file.append('image2', Image2);
    file.append('image3', Image3);
    file.append('image4', Image4);
    file.append('image5', Image5);
    file.append('image6', Image6);
    file.append('drop1', drop1);
    file.append('drop2', drop2);
    file.append('drop3', drop3);
    file.append('drop4', drop4);
    file.append('drop5', drop5);
    file.append('drop6', drop6);
    file.append('name', $('#knifeForm input[name=name]').val());
    file.append('steel', $('#knifeForm select[name=steel]').val());
    file.append('blade_length', $('#knifeForm input[name=blade_length]').val());
    file.append('blade_width', $('#knifeForm input[name=blade_width]').val());
    file.append('blade_thickness', $('#knifeForm input[name=blade_thickness]').val());
    file.append('handle_length', $('#knifeForm input[name=handle_length]').val());
    file.append('price', $('#knifeForm input[name=price]').val());
    file.append('status', $('#knifeForm select[name=status]').val());
    file.append('handle', $('#knifeForm textarea[name=handle]').val());
    file.append('description', $('#knifeForm textarea[name=description]').val());
    $.ajax({
        type:"POST",
        url:"/updateKnife/"+id,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        $('#saveKnife').prop('disabled', false);
        $('#saveKnife').removeClass('button_pushed');
        if (handleAjaxResponse(data)) return;
        if (data['success'] == 1) {
            $('#success_message').css('display','block');
            location.reload();
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Удаление ножа*/
function dropKnife(id){
    $.ajax({
        type:"POST",
        url:"/dropKnife/"+id,
        cache: false,
        processData: false,
        contentType: false,
        dataType:"json"
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        if (data['success'] == 1) {
           window.location.href = document.referrer;
        } 

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


/*Сохранение серийного нового ножа*/
function saveSerialKnifee() {
    $('#saveKnife').prop('disabled', true);
    $('#saveKnife').addClass('button_pushed');
    file = new FormData();
    var Image1,Image2,Image3,Image4,Image5,Image6 = '';
    if (!flag1File) Image1 = $('#file1')[0].files[0];
    if (!flag2File) Image2 = $('#file2')[0].files[0];
    if (!flag3File) Image3 = $('#file3')[0].files[0];
    if (!flag4File) Image4 = $('#file4')[0].files[0];
    if (!flag5File) Image5 = $('#file5')[0].files[0];
    if (!flag6File) Image6 = $('#file6')[0].files[0];
    file.append('image1', Image1);
    file.append('image2', Image2);
    file.append('image3', Image3);
    file.append('image4', Image4);
    file.append('image5', Image5);
    file.append('image6', Image6);
    file.append('name', $('#knifeForm input[name=name]').val());
    file.append('steel', $('#knifeForm select[name=steel]').val());
    file.append('blade_length', $('#knifeForm input[name=blade_length]').val());
    file.append('blade_width', $('#knifeForm input[name=blade_width]').val());
    file.append('blade_thickness', $('#knifeForm input[name=blade_thickness]').val());
    file.append('handle_length', $('#knifeForm input[name=handle_length]').val());
    file.append('price', $('#knifeForm input[name=price]').val());
    file.append('count', $('#knifeForm input[name=count]').val());
    file.append('handle', $('#knifeForm textarea[name=handle]').val());
    file.append('description', $('#knifeForm textarea[name=description]').val());
    if ($('#knifeForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    file.append('viewable', viewableProp);
    $.ajax({
        type:"POST",
        url:"/saveSerialKnife",
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        $('#saveKnife').prop('disabled', false);
        $('#saveKnife').removeClass('button_pushed');
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
           location.reload();
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение серийного ножа*/
function updateSerialKnife(id) {
    $('#saveKnife').prop('disabled', true);
    $('#saveKnife').addClass('button_pushed');
    file = new FormData();
    var Image1 = '',Image2 = '',Image3 = '',Image4 = '',Image5 = '',Image6 = '';
    if (!flag1File) Image1 = $('#file1')[0].files[0];
    if (!flag2File) Image2 = $('#file2')[0].files[0];
    if (!flag3File) Image3 = $('#file3')[0].files[0];
    if (!flag4File) Image4 = $('#file4')[0].files[0];
    if (!flag5File) Image5 = $('#file5')[0].files[0];
    if (!flag6File) Image6 = $('#file6')[0].files[0];
    var drop1 = 0, drop2 = 0, drop3 = 0, drop4 = 0, drop5 = 0, drop6 = 0;
    if ($('#drop1').is(":checked")) drop1 = 1;
    if ($('#drop2').is(":checked")) drop2 = 1;
    if ($('#drop3').is(":checked")) drop3 = 1;
    if ($('#drop4').is(":checked")) drop4 = 1;
    if ($('#drop5').is(":checked")) drop5 = 1;
    if ($('#drop6').is(":checked")) drop6 = 1;
    file.append('image1', Image1);
    file.append('image2', Image2);
    file.append('image3', Image3);
    file.append('image4', Image4);
    file.append('image5', Image5);
    file.append('image6', Image6);
    file.append('drop1', drop1);
    file.append('drop2', drop2);
    file.append('drop3', drop3);
    file.append('drop4', drop4);
    file.append('drop5', drop5);
    file.append('drop6', drop6);
    file.append('name', $('#knifeForm input[name=name]').val());
    file.append('steel', $('#knifeForm select[name=steel]').val());
    file.append('blade_length', $('#knifeForm input[name=blade_length]').val());
    file.append('blade_width', $('#knifeForm input[name=blade_width]').val());
    file.append('blade_thickness', $('#knifeForm input[name=blade_thickness]').val());
    file.append('handle_length', $('#knifeForm input[name=handle_length]').val());
    file.append('price', $('#knifeForm input[name=price]').val());
    file.append('count', $('#knifeForm input[name=count]').val());
    file.append('handle', $('#knifeForm textarea[name=handle]').val());
    file.append('description', $('#knifeForm textarea[name=description]').val());
    if ($('#knifeForm input[name=viewable]').is(":checked")) {
        var viewableProp = 0;
    } else {
        var viewableProp = 1;
    }
    file.append('viewable', viewableProp);
    $.ajax({
        type:"POST",
        url:"/updateSerialKnife/"+id,
        cache: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        $('#saveKnife').prop('disabled', false);
        $('#saveKnife').removeClass('button_pushed');
        if (handleAjaxResponse(data)) return;
        if (data['success'] == 1) {
            $('#success_message').css('display','block');
            location.reload();
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}
/*Изменение данных пользователя*/
function changeUser(){

    $('#noteEmail').css('display', 'none');
    $(".red").removeClass('red');
    var flagUnfill=0;
    $.each($('input'),function(){
        if($(this).val()=='' && !$(this).hasClass('notNecessary')){
            if (flagUnfill<1){
                $(this).focus();
                flagUnfill++;
            }
            $(this).addClass('red');
        }
    });
    if (flagUnfill != 0) {
        return false;
    }
    if (!validateEmail($("input[name='email']").val())) {
        $('#noteEmail').css('display', 'block');
        $("input[name='email']").addClass('red');
        return false;
    }
    $('.changeUserButton').prop('disabled', true);
    $('.changeUserButton').addClass('button_pushed');
    file = new FormData();
    file.append('region', $('#userChangeForm input[name=region]').val());
    file.append('locality', $('#userChangeForm input[name=locality]').val());
    file.append('street', $('#userChangeForm input[name=street]').val());
    file.append('house', $('#userChangeForm input[name=house]').val());
    file.append('flat', $('#userChangeForm input[name=flat]').val());
    file.append('mailIndex', $('#userChangeForm input[name=mailIndex]').val());
    file.append('zoneInd', $('#userChangeForm input[name=zoneInd]:checked').val());
    file.append('name', $('#userChangeForm input[name=name]').val());
    file.append('surname', $('#userChangeForm input[name=surname]').val());
    file.append('patronymic', $('#userChangeForm input[name=patronymic]').val());
    file.append('email', $('#userChangeForm input[name=email]').val());
    // if ($('#userChangeForm input[name=sms_alert]').is(":checked")) {
    //     var sms_alert = 1;
    // } else {
    //     var sms_alert = 2;
    // }
    // file.append('sms_alert', sms_alert);
    $.ajax({
        type:"POST",
        url:"/changeUser",
        cache: false,
        async: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        $('.changeUserButton').removeClass('button_pushed');
        $('.changeUserButton').prop('disabled', false);
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
            $('#success_message').css('display','block');
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Удаление пользователя*/
function dropUser() {
    $('#confirmDrop').addClass('button_pushed');
    $.ajax({
        type:"POST",
        url:"/dropUser",
        cache: false,
        dataType:"json"
    }).done(function(data){
        rejectDropUser();
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
            location.reload();
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


/*Удаление пользователя*/
function dropUserByOperator(userId) {
    $('#confirmDropByOperator').addClass('button_pushed');
    $.ajax({
        type:"POST",
        url:"/dropUserByOperator",
        data: {
            'userId': userId
        },
        async: false,
        dataType:'json',
    }).done(function(data){
        console.log(data);
        rejectDropUserByOperator();
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
            location.reload();
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
        console.log(xhr);
        console.log(status);
    });
}

function changePasswordd() {
    $('#notMatchPassword').css('display', 'none');
    $('#notLengthPassword').css('display', 'none');
    $(".red").removeClass('red');
    var flagUnfill=0;
    $.each($('input'),function(){
        if($(this).val()==''){
            if (flagUnfill<1){
                $(this).focus();
                flagUnfill++;
            }
            $(this).addClass('red');
        }
    });
    if (flagUnfill != 0) {
        return false;
    }
    if ($('input[name=newPassword]').val().length<5){
        $('#notLengthPassword').css('display', 'block');
        return false;
    }
    if ($('input[name=newPasswordCheck]').val() !== $('input[name=newPassword]').val()) {
        $('#notMatchPassword').css('display', 'block');
        return false;
    }
    $('#changePassword').addClass('button_pushed');
    var file = new FormData();
    file.append('password', $('input[name=password]').val());
    file.append('newPassword', $('input[name=newPassword]').val());
    file.append('newPasswordCheck', $('input[name=newPasswordCheck]').val());
    $.ajax({
        type:"POST",
        url:"/changePassword",
        cache: false,
        async: false,
        processData: false,
        contentType: false,
        data: file,
        dataType:"json"
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        if (data['success']==1) {
            $('#before_send').css('display', 'none');
            $('#success_change_password').css('display','block');
        }
        if (data['success'] == 5) {
            $('#alert_error .captionAlert').text('Не верный пароль');
            $('input[name=password]').addClass('.red');
            $('#error_message').css('display','block');
        }
        if (data['success'] == 6) {
            $('#notMatchPassword').css('display', 'block');
            $('input[name=newPasswordCheck]').addClass('.red');
        }
        if (data['success'] == 7) {
            $('#notLengthPassword').css('display', 'block');
        }
        $('#changePassword').removeClass('button_pushed');
    }).fail(function (xhr,status,error){  
        console.log(error);
    });

}

function confirmationDropUser(){
    $('#confirmDrop').removeClass('button_pushed');
    $('#confirmationDropUser').css('display','block');
}

/*Скрыть сообщение о подтверждении удаления пользователя*/
function rejectDropUser(){
    $('#confirmationDropUser').css('display','none');
}

function confirmationDropUserByOperator(){
    $('#confirmDropByOperator').removeClass('button_pushed');
    $('#confirmationDropUserByOperator').css('display','block');
}

/*Скрыть сообщение о подтверждении удаления пользователя*/
function rejectDropUserByOperator(){
    $('#confirmationDropUserByOperator').css('display','none');
}

function confirmationDropKnife(){
    $('#confirmationDropKnife').css('display','block');
}

/*Скрыть сообщение о подтверждении удаления ножа*/
function rejectDropKnife(){
    $('#confirmationDropKnife').css('display','none');
}

function confirmationDropPart(){
    $('#confirmDrop').removeClass('button_pushed');
    $('#confirmationDropPart').css('display','block');
}

/*Скрыть сообщение о подтверждении удаления пользователя*/
function rejectDropPart(){
    $('#confirmationDropPart').css('display','none');
}

confirmationDropUser
/*ссылки на страницы просмотра заказов для пользователя*/
function toOrderForCustomer(id, type) {
    switch(type) {
        case 1: 
            window.location.href='/home/ordersConstruct/'+id;
            break;
        case 2: 
            window.location.href='/home/ordersIndividual/'+id;
            break;    
        case 3: 
            window.location.href='/home/ordersCart/'+id;
            break;
    } 
}

/*ссылки на страницы просмотра заказов для мастера*/
function toOrderForMaster(id, type) {
    switch(type) {
        case 1: 
            window.location.href='/home/constructOrders/'+id;
            break;
        case 2: 
            window.location.href='/home/individualOrders/'+id;
            break;    
        case 3: 
            window.location.href='/home/cartOrders/'+id;
            break;
    } 
}

/*Получение сообщений*/
var changeFlag = false;
var messagesData = null;

var orderForError = 0; // При ошибке сервера данные на повторный запрос
var typeOrderForError = 0;

function getMessages(order, typeOrder) {
    orderForError = order;
    typeOrderForError = typeOrder;
    $.ajax({
        type:'POST',
        async: false,
        url:"/getMessages",
        data: {
            'orderId': order,
            'typeOrder': typeOrder
        },
        dataType:'json',
        success: function(data){
            if (handleAjaxResponse(data,true)) {
                messagesData = null;
                changeFlag = false;
                return;
            }
            if (data['success'] === 1) {
                messagesData = data['messages'];
                if (data['onlineCustomer']) {
                    if (data['onlineCustomer']['online'] == 1){
                        $('#online').text('online');
                    } else {
                        $('#online').text(data['onlineCustomer']['TimeOut'] + " " +data['onlineCustomer']['DateOut']);
                    }
                }
                changeFlag = true;
            } else {
                messagesData = null;
                changeFlag = false;
            }
        },
        error: function(xhr,status,error){  
            console.log(error);
            getMessages(orderForError, typeOrderForError);
        }
    });
}

/*формирование шаблона сообщений*/
function tmplMessages(order, typeOrder){
    $.when(getMessages(order, typeOrder)).then(function(){
        if (changeFlag) {
            var cartBox=$('#letters');
            $('.messageLine').remove();
            if (messagesData) {
                cartBox.find("#messageLineTemplate").tmpl(messagesData).appendTo("#letters");
                    setTimeout(function() {
                        changeLettersHeight();
                    }, 10);
                    $(".messageImage").load(function(){
                    setTimeout(function() {
                        if ($('.newMessageEvent').length > 0) {
                            changeLettersHeight(true);
                        } else {
                            changeLettersHeight();
                        }
                    }, 10);
                });
            }
        }
    });
}


/*Сообщение о подтверждении отказа от заказа*/
function confirmationRefuse(){
    $('#confirmationRefuse').css('display','block');
}

/*Скрыть сообщение о подтверждении отказа от заказа*/
function rejectRefuse(){
    $('#confirmationRefuse').css('display','none');
}

/*Отказ от заказа*/
function refuseOrder(orderId, typeOrder){
    file = new FormData();
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/refuseOrder",
        data: file,
        processData: false,
        contentType: false,
        async: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            rejectRefuse();
            return;
        }
        if(data['success'] == 1){
            sendSocket(); 
            rejectRefuse();
            window.location.href = document.referrer;
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

var prevMaster;
var prevMasterName;
var prevStatus;
var prevStatusName;
var prevSend;
var prevSendName;
var prevSum;
var prevPay;
var prevDay;
function setMasterPrev() {
    prevMaster = $('select[name=masterChanger]').val();
    prevMasterName = $('select[name=masterChanger] :selected').text();
}
function setStatusPrev() {
    prevStatus = $('select[name=statusChanger]').val();
    prevStatusName = $('select[name=statusChanger] :selected').text();
}
function setSendPrev() {
    prevSend = $('select[name=typeOfSend]').val();
    prevSendName = $('select[name=typeOfSend] :selected').text();
}
function setSumPrev() {
    prevSum = $('input[name=sumOrder]').val();
}
function setDayPrev() {
    prevDay = $('input[name=daysOfOrder]').val();
}
function setPayPrev() {
    prevPay = $('input[name=pay]').val();
}
/*Сообщение о подтверждении изменения заказа*/
function confirmation(){
    $('#prevStatus').text(prevStatusName);
    $('#newStatus').text($('select[name=statusChanger] :selected').text());
    $('#confirmation').css('display', 'block');
}

function reject(){
    $('select[name=statusChanger]').val(prevStatus);
    $('#confirmation').css('display','none');
}
/*Изменение статуса заказа*/
function confirm(orderId, typeOrder){
    file = new FormData();
    file.append('idStatus', $('select[name=statusChanger]').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/changeStatus",
        data: file,
        processData: false,
        contentType: false,
        async:false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            reject();
            return;
        }
        if (data['success'] == 1) {
            sendSocket();
            $('#confirmation').css('display','none');
            tmplMessages(orderId, typeOrder);
            if (data['refused'] == 1) {
                window.location.href = document.referrer;
            }
            if (data['reload'] == 1) {
                location.reload();
            }
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}


function confirmationMaster(){
    $('#prevMaster').text(prevMasterName);
    $('#newMaster').text($('select[name=masterChanger] :selected').text());
    $('#confirmationMaster').css('display', 'block');
}

function rejectMaster(){
    $('#confirmationMaster').css('display','none');
    $('select[name=masterChanger]').val(prevMaster);
}

/*Изменение мастера заказа*/
function confirmMaster(orderId, typeOrder){
    file = new FormData();
    file.append('idMaster', $('select[name=masterChanger]').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/changeMaster",
        data: file,
        processData: false,
        contentType: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            rejectMaster();
            return;
        }
        if(data['success'] == 1){
            subscribe("user"+$('select[name=masterChanger]').val());
            sendSocket();
            if (prevMaster != 0){
                unsubscribe("user"+prevMaster); 
            }
            $('#confirmationMaster').css('display','none');
            tmplMessages(orderId, typeOrder);
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Сообщение о подтверждении изменения доставки заказа*/
function confirmationSend(){
    $('#prevSend').text(prevSendName);
    $('#newSend').text($('select[name=typeOfSend] :selected').text());
    $('#confirmationSend').css('display', 'block');
}

function rejectSend(){
    $('select[name=typeOfSend]').val(prevSend);
    $('#confirmationSend').css('display','none');
}

/*Изменение доставки заказа*/
function confirmSend(orderId, typeOrder){
    file = new FormData();
    file.append('typeSend', $('select[name=typeOfSend]').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/changeSend",
        data: file,
        processData: false,
        contentType: false,
        async:false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            rejectSend();
            return;
        }
        if (data['success'] == 1) {
            sendSocket();
            $('#confirmationSend').css('display','none');
            tmplMessages(orderId, typeOrder);
            if (data['reload'] == 1) {
                location.reload();
            }
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Изменение цели заказа*/
function savePurpose(orderId, typeOrder){
    file = new FormData();
    file.append('purpose', $('#purposeTextarea').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/savePurpose",
        data: file,
        processData: false,
        contentType: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
        if(data['success'] == 1){
            $('#note_error .captionAlert').text('Изменение успешно!');
            $('#note_message').css('display','block');
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}
function enterSum(e) {
    if (event.keyCode==13) {
        confirmationSum();
    }
}
function confirmationSum(){
    if ($('input[name=sumOrder]').val() != prevSum) {
        $('#prevSum').text(prevSum);
        $('#newSum').text($('input[name=sumOrder]').val());
        $('#confirmationSum').css('display', 'block');
    }
}
function rejectSum(){
    $('input[name=sumOrder]').val(prevSum);
    $('#confirmationSum').css('display', 'none');
}


/*Изменение суммы заказа*/
function confirmSum(orderId, typeOrder){
    file = new FormData();
    file.append('sum', $('input[name=sumOrder]').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/changeSum",
        data: file,
        processData: false,
        contentType: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            rejectSum();
            return;
        };
        if(data['success'] == 1){
            sendSocket();
            tmplMessages(orderId, typeOrder);
            $('#confirmationSum').css('display', 'none');
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

function enterDay(e) {
    if (event.keyCode==13) {
        confirmationDay();
    }
}
function confirmationDay(){
    if ($('input[name=daysOfOrder]').val() != prevDay) {
        $('#prevDay').text(prevDay);
        $('#newDay').text($('input[name=daysOfOrder]').val());
        $('#confirmationDay').css('display', 'block');
    }
}
function rejectDay(){
    $('input[name=daysOfOrder]').val(prevDay);
    $('#confirmationDay').css('display', 'none');
}

/*Изменение дней на заказ*/
function confirmDay(orderId, typeOrder){
    file = new FormData();
    file.append('day', $('input[name=daysOfOrder]').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/changeDay",
        data: file,
        processData: false,
        contentType: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            rejectSum();
            return;
        };
        if(data['success'] == 1){
            sendSocket();
            tmplMessages(orderId, typeOrder);
            $('#confirmationDay').css('display', 'none');
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

function enterPay(e) {
    if (event.keyCode==13) {
        confirmationPay();
    }
}
function confirmationPay(){
    if ($('input[name=pay]').val() != prevPay) {
        $('#prevPay').text(prevPay);
        $('#newPay').text($('input[name=pay]').val());
        $('#confirmationPay').css('display', 'block');
    }
}
function rejectPay(){
    $('input[name=pay]').val(prevPay);
    $('#confirmationPay').css('display', 'none');
}

/*Изменение суммы заказа*/
function confirmPay(orderId, typeOrder){
    file = new FormData();
    file.append('pay', $('input[name=pay]').val());
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/changePay",
        data: file,
        processData: false,
        contentType: false,
        dataType:'json',
    }).done(function(data){
        if (handleAjaxResponse(data)) {
            rejectSum();
            return;
        };
        if(data['success'] == 1){
            sendSocket();
            tmplMessages(orderId, typeOrder);
            $('#confirmationPay').css('display', 'none');
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

var showFlag = false; //если скрыто false иначе true

/*Выдвижение заказа для маленьких экранов*/
function showOrderButton(){
    if (!showFlag) {
        $('.userBody .knifeProperties').css('right', 0);
        $('#showOrderButton').empty();
        $('#showOrderButton').addClass('showned');
        showFlag = true;
    } else {
        if ($('#flag600').is(':visible')) {
            var rightProperty = 'calc(-100% + 120px)';
        } else {
            var rightProperty = 'calc(-100% + 190px)';
        }
        $('.userBody .knifeProperties').css('right', rightProperty);
        $('#showOrderButton').text('заказ');
        $('#showOrderButton').removeClass('showned');
        showFlag = false;
    }
    $('#bodyKnifeProperties').getNiceScroll().resize();
}

function blockForShowChanger(){
    if ($('#flag900').is(':visible')) {
        if(!showFlag){
        var rightProperty = 'calc(-100% + 190px)';
        $('.userBody .knifeProperties').css('position', 'absolute');
        $('.userBody .knifeProperties').css('right', rightProperty);}
        $('.userBody .knifeProperties').css('width', 'calc(100% - 190px)');
    
    }
    if ($('#flag600').is(':visible')) {
        if(!showFlag){
        var rightProperty = 'calc(-100% + 120px)';
        $('.userBody .knifeProperties').css('position', 'absolute');
        $('.userBody .knifeProperties').css('right', rightProperty);
        }
        $('.userBody .knifeProperties').css('width', 'calc(100% - 120px)');
    }
    if ($('#flag1200').is(':visible')){
        $('.userBody .knifeProperties').css('position', 'static');
        $('.userBody .knifeProperties').css('width', '45%');
        $('#showOrderButton').text('заказ');
        $('#showOrderButton').removeClass('showned');
        showFlag = false;
        $('#bodyKnifeProperties').getNiceScroll().resize();
        //if(device.desktop()) $('#bodyKnifeProperties').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});
    }
    if ($('#flag600').is(':hidden') && $('#flag1200').is(':hidden') && $('#flag900').is(':hidden')) {
        $('.userBody .knifeProperties').css('position', 'static');
        $('.userBody .knifeProperties').css('width', '30%');
        $('#showOrderButton').text('заказ');
        $('#showOrderButton').removeClass('showned');
        showFlag = false;
        $('#bodyKnifeProperties').getNiceScroll().resize();
        //if(device.desktop()) $('#bodyKnifeProperties').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});
    }
    /*$('#bodyKnifeProperties').getNiceScroll().remove();
    if(device.desktop()) $('#bodyKnifeProperties').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled:false});
*/}

function openNote(){
    $('.customerNote').scrollTop(0);
    $('.customerNote').toggleClass('customerNoteOpened');
    $('#openNote').toggleClass('openedButtonNote');
    if ($('#openNote').hasClass('openedButtonNote')){
        if (device.desktop()) $('.customerNoteOpened').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:3, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
        $('.customerNote').css('overflow-y','scroll');
    } else {
        $('.customerNote').getNiceScroll().remove();
        $('.customerNote').css('overflow','hidden');
    }
}

/*Смена местами блоков array на вход*/
function reorderElements(elems) {
    // http://tanalin.com/articles/css-block-order/
    var count = elems.length;

    if (!count) {
        return;
    }

    var parent = elems[0].parentNode;

    for (var i = count - 1; i >= 0; i--) {
        parent.insertBefore(elems[i], parent.firstChild);
    }
}

function showMessageImg(e){
    $('#window').attr('src', $(e).attr('src'));
    $('#viewMessageImg').css('display', 'block');

}
function closeMessageImg(){
  $('#viewMessageImg').css('display','none');
};