/* коды ошибок */
var ACCESS_ERROR = 99;
var UNAUTH_ERROR = 98;
var CSRF_NOT_VALID = 97;
var KNIFE_IN_ORDER = 96;
var WRONG_LOGIN_PASSWORD = 95;
var UNCHANGE_ERROR = 94;
var ALREADY_BUYED = 93;

/* pay_id */
var PAY_LATER = 1;
var PAY_MAIL = 2;
var PAY_CARD = 3;
var PAY_PERSENT = 4;

var isIE = /*@cc_on!@*/false || !!document.documentMode;
var isEdge = !isIE && !!window.StyleMedia;

var conn = new WebSocket('wss://rusvyatich.pro/wss2/NNN:32769');

//
conn.onopen = function(e) {
    subscribe('newOrders');
    console.log('opened');
}

conn.onmessage = function(e) {
    if (typeof window[tmplConstructOrders] == 'function'){
        tmplConstructOrders();
    }
}

function subscribe(channel) {
    conn.send(JSON.stringify({command: "subscribe", channel: channel}));
}

/*Сделан заказ*/
function orderChanged(orderId, typeOrder){
    var msg = "Sended";
    conn.send(JSON.stringify({command: "message", message: msg}));
}

var withOutAlert = true; // Отсутствие сообщение в первую загрузку

/*Открытие или скрытие меню по клику*/
function showMenu() {
    $('.menu').toggleClass('showMenu');
    $('.open_menu').toggleClass('opened');
    if (parseInt($('.menu').css('left'))<0){ 
        if($('.open_menu').hasClass('opened')) {
            hideMainScroll();
        } else {
            showMainScroll();
        }
    } 
}

/*Видно ли элемент el на экране*/
function checkVisibility(el){
    var Top = $(window).scrollTop(),
    Bot = Top + $(window).height(),
    elTop = $(el).offset().top,
    elHeight = $(el).height();
    elBot = elTop + elHeight;
    var visibleFlag = false;
    if (Top >= elTop) {
        if ((Top - elTop) <= elHeight) {
            visibleFlag = true;
        }
    } else {
        if (Bot <= elBot) {
            if ((elBot - Bot) <= elHeight) {
                visibleFlag = true;
            }
        } else {
            if ((elTop >= Top) && (elBot < Bot)) {
                visibleFlag = true;
            }
        }
    }
    return visibleFlag;
}

function confirmationOut() {
    $('#confirmationOut').css('display', 'block');
    hideMainScroll();
}
function rejectOut() {
    $('#confirmationOut').css('display', 'none');
    showMainScroll();
}

/*Получение данных с формы obj_form*/
function getData(obj_form) {
    var hData;
    $(obj_form).each(function() {
    hData=$(this).serialize();
    });
    return hData;
}

/*Получение данных с двух форм*/
function getDataFromTwoForms(obj_form,obj_form_1) {
    var hData;
    $(obj_form).each(function() {
        hData=$(this).serialize();
    });
    $(obj_form_1).each(function() {
        hData+='&'+$(this).serialize();
    });
    return hData;
}

/*Обработка неуспешного ajax запроса токена*/     
function handleAjaxResponse(data, reloaded){
    if (reloaded === undefined) {
        reloaded = false;
    }
    var errorFlag = false;
    if (data['success'] == 0){
        if (reloaded) location.reload();
        if (data['note'] && !data['res']) {
            $('#alert_error .captionAlert').text(data['note']);
            $('#error_message').css('display','block');
            errorFlag = true;
            return errorFlag;
        }
        switch(data['res']) {
            case CSRF_NOT_VALID:
                $('#token_message .captionAlert').text('Сессия устарела. Пожалуйста перезагрузите страницу');
                $('#token_message').css('display', 'block');
                $('body').addClass('unclicked');
                errorFlag = true;
                break;
            case UNAUTH_ERROR:
                closeConstructOrder();
                $('#alert_error .captionAlert').text('Ошибка аутентификации');
                $('#alert_error').addClass('reloadThis');
                $('#error_message').css('display','block');
                errorFlag = true;
                break;
            case ACCESS_ERROR:
                closeConstructOrder();
                $('#alert_error .captionAlert').text('Вы не можете совершать данные действия');
                $('#error_message').css('display','block');
                errorFlag = true;
                break;
            case WRONG_LOGIN_PASSWORD:
                // $('#alert_error .captionAlert').text('Не верный логин или пароль');
                // $('#error_message').css('display','block');
                $('.wrongLogin').css('display', 'inline-block');
                $('#auth_form input[name=password]').val('');
                errorFlag = true;
                break;
            case ALREADY_BUYED:
                closeConstructOrder();
                $('#token_message .captionAlert').text('Один из товаров не доступен. Пожалуйста перезагрузите страницу и повторите заказ');
                $('#token_message').css('display', 'block');
                $('body').addClass('unclicked');
                errorFlag = true;
                break;
            default:
                if (data['note']) {
                    $('#alert_error .captionAlert').text(data['note']);
                    $('#error_message').css('display','block');
                    errorFlag = true;
                    return errorFlag;
                }
                $('#token_message .captionAlert').text('Ошибка. Перезагрузите страницу и повторите ваши действия.');
                $('#token_message').css('display','block');
                $('body').addClass('unclicked');
        }
    }
    return errorFlag;
}

/*переопределение высоты блока right_product от высоты описания продукта
function resizeDescriptionPopup(){
    var nameHeight=$('#nameKnife').height();
    var firstHeight=$('#popup_first_desc').height();
    var secondHeight=$('#popup_second_desc').height();
    if( $('.right_product').css('float')=='none'){
        if($('.product_popup_description').css('float')=='none'){
            $('.right_product').height(nameHeight+firstHeight+secondHeight+265);
        }else{
            if(firstHeight>secondHeight){
                $('.right_product').height(nameHeight+firstHeight+265);
            }else{
                $('.right_product').height(nameHeight+secondHeight+265);
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

/*Изменение размера блока покупок в корзине*/
function sizeCartPopup(){
    /*if ($('#cart .phoneLineClose').is(':visible')){
        //var top = $('#cart_slider_wrap').offset().top-$('#cart').offset().top;
        var height_slide_cart=$(window).height()-top-80;
        $('#cart_slider_wrap').css('height', height_slide_cart);
    }else{
         $('#cart_slider_wrap').css('height', '360px');
    }*/
}

function showBladePhone(){
    if($('#bladePhone').css('display') == 'block') {
        $('#bladePhone').css('display', 'none');
    } else {
        $('#bladePhone').css('display', 'block');
    }
}
function showSteelPhone(){
    if($('#steelPhone').css('display') == 'block') {
        $('#steelPhone').css('display', 'none');
    } else {
        $('#steelPhone').css('display', 'block');
    }
}
function showBolsterPhone(){
    if($('#bolsterPhone').css('display') == 'block') {
        $('#bolsterPhone').css('display', 'none');
    } else {
        $('#bolsterPhone').css('display', 'block');
    }
}
function showHandlePhone(){
    if($('#handlePhone').css('display') == 'block') {
        $('#handlePhone').css('display', 'none');
    } else {
        $('#handlePhone').css('display', 'block');
    }
}
function showMaterialPhone(){
    if($('#materialPhone').css('display') == 'block') {
        $('#materialPhone').css('display', 'none');
    } else {
        $('#materialPhone').css('display', 'block');
    }
}
/*
*
*Добавлениае товара в корзину
*
*/
function addToCart(){
    $.ajax({
        type:'POST',
        async: false,
        url:"/addToCart",
        data:{
            "id": idKnife
        },
        dataType:'json',/// ошибка при джейсон
        success: function(data){
        if (handleAjaxResponse(data)) return;
            if(data['success']==1){
                $('#basket-cost').html(data['sum']+' <span class="rub">р.</span>');
                $('#sum_inner_cart').html(data['sum']+' <span class="rub">р.</span>');
                $('#to_form').text('уже в корзине');
                $('#to_form').addClass('button_pushed');
                $('#to_form').prop('disabled', true);
                $('#success_after_add_to_cart').css('display','block');
                hideMainScroll();
            }  
            if (data['quantity'] < 2){
                $('#svg_cart_knife_2').attr('transform','translate(50 -58) rotate(-60)');
            }
            if (data['quantity'] < 3){
                $('#svg_cart_knife_3').attr('transform','translate(60 -58) rotate(-55)');
            }
            if (data['quantity'] < 4){
                $('#svg_cart_knife_4').attr('transform','translate(35 -58) rotate(-55)');
            }
            if (data['quantity'] < 5){
                $('#svg_cart_knife_5').attr('transform','translate(45 -58) rotate(-55)');
            }  
            if (data['quantity']>=1){
                $('#to_cart').css('left','0px');
            }
            if (data['quantity']>=2){
                $('#svg_cart_knife_2').attr('transform','translate(-20 113) rotate(-70)');
            }
            if (data['quantity']>=3){
                $('#svg_cart_knife_3').attr('transform','translate(-12 113) rotate(-70)');
            }
            if (data['quantity']>=4){
                $('#svg_cart_knife_4').attr('transform','translate(-27 113) rotate(-72)');
            }
            if (data['quantity']>=5){
                $('#svg_cart_knife_5').attr('transform','translate(-33 117) rotate(-68)');
            }
        },
        error: function(xhr,status,error){ 
             console.log(error);
            }
    });
}

/*
*
*Добавлениае товара (серийного) в корзину
*
*/
function addToCartSerial(id){
    $.ajax({
        type:'POST',
        async: false,
        url:"/addToCartSerial",
        data:{
            "id": id,
            "count": $('input[name=countAdd]').val()
        },
        dataType:'json',/// ошибка при джейсон
        success: function(data){
        if (handleAjaxResponse(data)) return;
            if(data['success']==1){
                $('#basket-cost').html(data['sum']+' <span class="rub">р.</span>');
                $('#sum_inner_cart').html(data['sum']+' <span class="rub">р.</span>');
                $('#success_after_add_to_cart').css('display','block');
                $('input[name=countAdd]').val(1);
                hideMainScroll();
            }  
            if (data['quantity'] < 2){
                $('#svg_cart_knife_2').attr('transform','translate(50 -58) rotate(-60)');
            }
            if (data['quantity'] < 3){
                $('#svg_cart_knife_3').attr('transform','translate(60 -58) rotate(-55)');
            }
            if (data['quantity'] < 4){
                $('#svg_cart_knife_4').attr('transform','translate(35 -58) rotate(-55)');
            }
            if (data['quantity'] < 5){
                $('#svg_cart_knife_5').attr('transform','translate(45 -58) rotate(-55)');
            }  
            if (data['quantity']>=1){
                $('#to_cart').css('left','0px');
            }
            if (data['quantity']>=2){
                $('#svg_cart_knife_2').attr('transform','translate(-20 113) rotate(-70)');
            }
            if (data['quantity']>=3){
                $('#svg_cart_knife_3').attr('transform','translate(-12 113) rotate(-70)');
            }
            if (data['quantity']>=4){
                $('#svg_cart_knife_4').attr('transform','translate(-27 113) rotate(-72)');
            }
            if (data['quantity']>=5){
                $('#svg_cart_knife_5').attr('transform','translate(-33 117) rotate(-68)');
            }
        },
        error: function(xhr,status,error){ 
             console.log(error);
            }
    });
}
/*Минуточку проверяем пароль*/
function startWait(time) {
    $('#waitLogin').css('display', 'block');
    setTimeout(function(){authUser()}, (1000*time));
}
/*Авторизация пользователя*/
function authUser(){
    $('.wrongLogin').css('display', 'none');
    var flag = 0;
    $('.red').removeClass('red');
    $.each($('#auth_form input'),function(){
        if($(this).val()==''){
            if (flag<1){
                $(this).focus();
                flag++;
            }
            $(this).addClass('red');
        }
    });
    if (flag>0) return false;
    $('#auth_form input').prop('disabled', true);
    var username = $('#auth_form input[name=username]').val();
    var password = $('#auth_form input[name=password]').val();
    var rememberme = 0;
    $('#entranceButton').addClass('button_pushed');
    $('#entranceButton').prop('disabled', true);
    if ($('#auth_form input[name=rememberme]').is(":checked")) {
        var rememberme = 1;
    }
        $.ajax({
        type:'POST',
        async: false,
        url:"/authUser",
        data:{
            "username": username,
            "password": password,
            "rememberme": rememberme
        },
        dataType:'json',/// ошибка при джейсон
        success: function(data){
            $('#auth_form input').prop('disabled', false);
            if (data['success'] == 7) {
                startWait(data['reRequest']);
                return;
            } else {
                $('#waitLogin').css('display', 'none');
            }
        $('#entranceButton').removeClass('button_pushed');
        $('#entranceButton').prop('disabled', false);
        if (handleAjaxResponse(data)) return;
            if( data['success'] == 1){
                if(data['id'] !==''){
                    document.location.href="../home";
                }
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });

}

function resetPassword() {
    var flag = 0;
    $('.red').removeClass('red');
    $.each($('#reset_form input'),function(){
        if($(this).val()==''){
            if (flag<1){
                $(this).focus();
                flag++;
            }
            $(this).addClass('red');
        }
    });
    if (flag>0) return false;
    var username = $('#reset_form input[name=username]').val();
    if (!username || $('.timerAlert').is(':visible')) return ;
        $.ajax({
        type:'POST',
        async: false,
        url:"/resetPassword",
        data:{
            "username": username
        },
        dataType:'json',
        success: function(data){
            if (data['success'] == 1){
                $('#resetButton').css('display', 'block');
                $(".dayLimit").css('display', 'none');
                if ((data['timeOut']) > 0) {
                    $('.afterSend').css('display', 'block');
                    $('.beforeSend').css('display', 'none');
                    $('#phoneSended').text(username);
                    //$('#resetButton').css('display', 'none');
                    if(data['limit'] <= 0) {
                        $(".dayLimit").css('display', 'block');
                    } else {
                        $(".timerAlert").css('display', 'block');
                        $('#timer_inp').html(data['timeOut']);
                    }
                } else {
                    // $('#resetButton').css('display', 'block');
                    $(".timerAlert").css('display', 'none');
                }
            }
            if (data['success'] == 2){ 
                $(".dayLimit").css('display', 'block');
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });

}

/*Восстановка пароля с отправкой почты*/
function resetPasswordByEmail() {
    var flag = 0;
    $('.dayLimit').css('display', 'none');
    $('.timerAlert').css('display', 'none');
    $('#sended').hide();
    $('#alreadySended').hide();   
    $('.red').removeClass('red');
    $.each($('#reset_form input'),function(){
        if($(this).val()==''){
            if (flag<1){
                $(this).focus();
                flag++;
            }
            $(this).addClass('red');
        }
    });
    if (flag>0) return false;
    var username = $('#reset_form input[name=username]').val();
    if (!username || $('.timerAlert').is(':visible')) return ;
    $('#resetButton').addClass('button_pushed');
        $.ajax({
        type:'POST',
        async: false,
        url:"/resetPasswordByEmail",
        data:{
            "username": username
        },
        dataType:'json',
        success: function(data){
            $('#resetButton').removeClass('button_pushed');
            if (handleAjaxResponse(data)) return;
            if (data['success'] == 1){
                $(".dayLimit").css('display', 'none');
                if ((data['timeOut']) > 0) {
                    $('.afterSend').css('display', 'block');
                    $('.beforeSend').css('display', 'none');
                    if(data['limit'] <= 0) {
                        $(".dayLimit").css('display', 'block');
                    } else {
                        $(".timerAlert").css('display', 'block');
                        $('#timer_inp').html(data['timeOut']);
                    }
                } else {
                    $('.afterSend').css('display', 'block');
                    $('.beforeSend').css('display', 'none');
                    $(".timerAlert").css('display', 'none');
                }   
                if(data['alreadySended']) {
                    $('#sended').hide();
                    $('#alreadySended').show();
                } else {
                    $('#sended').show();
                    $('#alreadySended').hide();   
                }
                $('#emailSended').text(data['email']);    
                $('.afterSend').css('display', 'block');
            }
            if (data['success'] == 4) {
                $('.beforeSend').css('display', 'none');
                $('.dayLimit').css('display', 'block');
            }
        },
        error: function(xhr,status,error){  
            console.log(error);
        }
    });

}

/*Новый пароль пользователя по ссылке*/
function newPassword(){
    $('.res').removeClass('red');
    var password = $('input[name=password]').val(); 
    var passwordCheck = $('input[name=passwordCheck]').val();
    var id_user = $('input[name=id_user]').val();
    var access_hash = $('input[name=access_hash]').val();
    $('#validation').hide();
    var flag=0;
    $.each($('#reset_form input[type=password]'),function(){
        if($(this).val()==''){
            if (flag<1){
                $(this).focus();
                flag++;
            }
            $(this).addClass('red');
        }
    });
    if (password !== passwordCheck) {
        $('#validation').text('Пароли не совпадают');
        setTimeout(function(){$('#validation').show()}, 100);
        return;
    }
        if (password.length < 8 || password.length > 20)  {
            $('#validation').text('Пароль должен содержать от 8 до 20 символов');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
        if (password.search(/[A-ZА-Я]/g) == -1) {
            $('#validation').text('Пароль должен содержать буквы верхнего и нижнего регистров');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
        if (password.search(/[a-zа-я]/g) == -1) {
            $('#validation').text('Пароль должен содержать буквы верхнего и нижнего регистров');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
        if (password.search(/[0-9]/g) == -1) {
            $('#validation').text('Пароль должен содержать хотя бы 1 цифру');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
    $('#validation').hide();
    $('#changeButton').addClass('button_pushed');
    $('#changeButton').prop('disabled', true);
    $.ajax({
        type:'POST',
        async: false,
        url:"/newPassword",
        data:{
            "id_user": id_user,
            "access_hash": access_hash,
            "password": password
        },
        dataType:'json',/// ошибка при джейсон
        success: function(data){
            $('#changeButton').removeClass('button_pushed');
            $('#changeButton').prop('disabled', false);
            if (handleAjaxResponse(data)) return;
            if( data['success'] == 1){
                document.location.href="../../home";
            } else {
                alet('ошибка');
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });
}
/*Выйти пользователю*/
function outUser(){
        $.ajax({
        type:'POST',
        async: false,
        url:"/outUser",
        dataType:'json',/// ошибка при джейсон
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if( data['success'] == 1){
                $('#confirmationOut').css('display', 'none');
                location.reload();
            } 
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });

}

/*Поиск в корзине ножа с id*/
function searchInCart(id){
    var bool = false;
    var boolSec = false;
    $.ajax({
        type:'POST',
        url:"/searchInCart",
        data:{
            "id": id
        },
        dataType: 'json',
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if(data['bool']!==false){
                bool = true;
            }
            if(data['buyed']!==false){
                boolSec = true;
            }
            var response = [bool, boolSec];
            if (response[0]){
                $('#to_form').text('уже в корзине');
                $('#to_form').addClass('button_pushed');
                $('#to_form').prop('disabled',true);
            } else if (response[1]) {
                $('#to_form').text('уже куплен');
                $('#to_form').addClass('button_pushed');
                $('#to_form').prop('disabled',true);
            } else {
                $('#to_form').text('в корзину');
                $('#to_form').removeClass('button_pushed');
                $('#to_form').prop('disabled',false);
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });
}
/*Проверка на наличие товара в корзине для первоначального показа корзины*/
function checkCart(){
    $.ajax({
        type:'POST',
        url:"/checkCart",
        dataType:'json',/// ошибка при джейсон
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if(data['success']==1){
                $('#basket-cost').html(data['sum']+' <span class="rub">р.</span>');
                $('#sum_inner_cart').html(data['sum']+' <span class="rub">р.</span>');
            }
            if (data['quantity']>=1){
                $('#to_cart').css('left','0px');
            } else {
                $('#to_cart').css('left','-120px');

            }
            if (data['quantity']>=2){
                $('#svg_cart_knife_2').attr('transform','translate(-20 113) rotate(-70)');
            }
            if (data['quantity']>=3){
                $('#svg_cart_knife_3').attr('transform','translate(-12 113) rotate(-70)');
            }
            if (data['quantity']>=4){
                $('#svg_cart_knife_4').attr('transform','translate(-27 113) rotate(-72)');
            }
            if (data['quantity']>=5){
                $('#svg_cart_knife_5').attr('transform','translate(-33 117) rotate(-68)');
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });
}

var cartData=null;

/*Получение ножей для отображении в корзине*/
function getKnifesForCart(){
    $.ajax({
        type:'POST',
        async: false,
        url:"/getKnifesForCart",
        dataType:'json',
        success: function(data){
        if (handleAjaxResponse(data)) return;
            if(data['success']==1){
                cartData=(data['items']).concat(data['itemsSerial']);
            } else {
                cartData=false;
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });

}

/*Изменеие высоты скролла корзины*/
function resizeCart() {
    var h = $(window).height();
    if ($('#cart').height() == h) {
        $('.wrap_slider').height(h-190);
    } else {
        $('.wrap_slider').height(360);
    }
}

/*формирование шаблона корзины + её открытие*/
function tmplKnife(){
    $.when(getKnifesForCart()).then(function(){
        var cartBox=$('#cart');
        $('.cartElement').remove();
        if(cartData.length !== 0){
            cartBox.find("#cartTemplate").tmpl(cartData).appendTo("#cart_slider");
            $('#wrap_cart').css('display','block');
            hideMainScroll();
            sizeCartPopup();
            $(".wrap_slider").getNiceScroll().resize();
        } else {
            checkCart();
            closeCart();
            if(idKnife) searchInCart(idKnife);
        }
        resizeCart();
        $('.hoveredClose').removeClass('hoveredClose');
    });
}

/*Подбор ножа по паараметрам*/
function getKnifesByParameters(){
    if ($('.parameterButton').hasClass('button_pushed')) return false;
    $('.takeByParameters').addClass('whileFind');
    $('.loadBlock').css('display', 'block');
    $('.parameterButton').addClass('button_pushed');
    var postData = getData('#searchByParameters');
    var steels = [];

    $('.steelInput').each(function(){

        if($(this).is(":checked"))
        {
            steels.push($(this).val());
        }

    });
    $('.emptyProducts').css('display', 'none');
    $.ajax({
        type:'POST',
        url:"/getKnifesByParameters",
        dataType:'json',
        data: {
            postData: postData,
            steels: steels
        },
        success: function(data){
        if (handleAjaxResponse(data)) return;
            if(data['success']==1){
                productData=data['knifes'];
            } else {
                productData=null;
            }
            var productsBox=$('#productsBox');
            $('.product').remove();
            if(productData.length !== 0){
                productsBox.find("#productsTemplate").tmpl(productData).appendTo("#productsBox");
            } else {
                $('.emptyProducts').css('display', 'block');
            }
            $('.takeByParameters').removeClass('whileFind');
            $('.parameterButton').removeClass('button_pushed');
            $('.loadBlock').css('display', 'none');
        },
        error: function(xhr,status,error){
            console.log(error);
            $('.takeByParameters').removeClass('whileFind');
            $('.parameterButton').removeClass('button_pushed');
            $('.loadBlock').css('display', 'none');
            getKnifesByParameters();
        }
    });
};

function eraseSteels(){
    $('input[type=checkbox]').prop('checked', false);
    getKnifesByParameters();
}
/*Закрытие корзины*/
function closeCart(bool){
    if (bool === undefined) {
        bool = false;
    }
    if (!bool) showMainScroll();
    $('#wrap_cart').css('display','none');
    $('.hoveredClose').removeClass('hoveredClose');
}

/*удаление из корзины продукта с id*/
function removeFromCart(itemId, event){
    event.stopPropagation();
    $.ajax({
        type:'POST',
        async: false,
        url:"/removeFromCart",
        data: {
            "id": itemId
        }, 
        dataType:'json',
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if(data['success'] == 1){
                $('#basket-cost').html(data['sum']+' <span class="rub">р.</span>');
                $('#sum_inner_cart').html(data['sum']+' <span class="rub">р.</span>');
                $('#in_cart_'+itemId).remove();
                $(".wrap_slider").getNiceScroll().resize();
                if(data['quantity'] == 0){
                    $('#to_cart').css('left','-120px');
                    closeCart();
                }
                if (idKnife) searchInCart(idKnife);
                if (data['quantity'] < 2){
                    $('#svg_cart_knife_2').attr('transform','translate(50 -58) rotate(-60)');
                }
                if (data['quantity'] < 3){
                    $('#svg_cart_knife_3').attr('transform','translate(60 -58) rotate(-55)');
                }
                if (data['quantity'] < 4){
                    $('#svg_cart_knife_4').attr('transform','translate(35 -58) rotate(-55)');
                }
                if (data['quantity'] < 5){
                    $('#svg_cart_knife_5').attr('transform','translate(45 -58) rotate(-55)');
                }
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });
}

/*удаление из корзины продукта с id*/
function removeFromCartSerial(itemId, event){
    event.stopPropagation();
    $.ajax({
        type:'POST',
        async: false,
        url:"/removeFromCartSerial",
        data: {
            "id": itemId
        }, 
        dataType:'json',
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if(data['success'] == 1){
                $('#basket-cost').html(data['sum']+' <span class="rub">р.</span>');
                $('#sum_inner_cart').html(data['sum']+' <span class="rub">р.</span>');
                $('#in_cart_serial_'+itemId).remove();
                $(".wrap_slider").getNiceScroll().resize();
                if(data['quantity'] == 0){
                    $('#to_cart').css('left','-120px');
                    closeCart();
                }
                if (data['quantity'] < 2){
                    $('#svg_cart_knife_2').attr('transform','translate(50 -58) rotate(-60)');
                }
                if (data['quantity'] < 3){
                    $('#svg_cart_knife_3').attr('transform','translate(60 -58) rotate(-55)');
                }
                if (data['quantity'] < 4){
                    $('#svg_cart_knife_4').attr('transform','translate(35 -58) rotate(-55)');
                }
                if (data['quantity'] < 5){
                    $('#svg_cart_knife_5').attr('transform','translate(45 -58) rotate(-55)');
                }
            }
        },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });
}

var way_from_cart=false; //указатель на то откуда пришел пользователь к продукту true из корзины


/*скрытие блока картинки, выведенной во весь экран*/
function closeMainImg(){
    $('#wrap_for_product').css('display','none');
    $('#wrap').css('display','block');
    $('.hoveredClose').removeClass('hoveredClose');
    showMainScroll();
}

/*Вернуться к покупкам*/
function returnToBuy(){
    $('#success_after_add_to_cart').css('display','none');
    showMainScroll();
}
/*Начальный вид козины*/
function initialCartView(bool){
    if (bool === undefined) {
        bool = false;
    }
    closeCart(bool);
    $('#to_cart').css('left','-120px');
    $('#svg_cart_knife_2').attr('transform','translate(50 -58) rotate(-60)');
    $('#svg_cart_knife_3').attr('transform','translate(60 -58) rotate(-55)');
    $('#svg_cart_knife_4').attr('transform','translate(35 -58) rotate(-55)');
    $('#svg_cart_knife_5').attr('transform','translate(45 -58) rotate(-55)');
}

/*Очистка корзины*/
function cleanCart(){
    $.ajax({
        type:'POST',
        async: false,
        url:"/cleanCart", 
        dataType:'json',
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if (data['success'] === 1) {
                initialCartView();
            }
    },
        error: function(xhr,status,error){  
             console.log(error);
            }
    });
}


/*После esc*/
function doEsc(){
    if ($('body').hasClass('unclicked') || $('#error_message').is(':visible') || $('#note_message').is(':visible')) return false;
    if ($('#aboutPartWrap').is(':visible')){
        closeAboutPart();
        if ($('#wrap_construct_order').is(':visible') && $('#elementId').length > 0) {
            hideMainScroll();
        }
        return;
    }
    if ($('#wrap_opros').is(':visible')){
       $('#form_consult_close').click();
    }
    wrap_opros
    if($('#wrap_for_product').is(':visible')){
        closeMainImg();
    }else if($('#success_after_add_to_cart').is(':visible')){
        $('#return_to_buy').click();
    }else if($('#wrap_cart').is(':visible') && !$('#wrap_construct_order').is(':visible')){
        closeCart();
    }else if($('#wrap_construct_order').is(':visible') && !$('#stage4 .next').hasClass('button_pushed')){
        if ($('#toHome').hasClass('accounted')) {
            closeConstructOrder();
        } else {
            closeConstructOrderConfirmation();
        }
    }
    if($('#success_message').is(':visible')){
        $('#close_alert').click();
    }
}


var kFix = 0;
var firstLengthHandle = 100;
var typeHandle = 1;
var handleFixBlade = 'M645,119 L630,119 Q597,119 591,139 L591, 300 L645,300z';
var handleFixBladeFultang = 'M645,119 Q607,135 591,156 Q593,150 585,170 L581,300 L645,300z';

/*Отрисовка рукоятки и больстера по длине и ширине рукояти */
function driveKnifeHandle(handleLength,originalHandleLength,kHeight){
    if(handleLength == 320) {
        handleLength =330;
    }
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
    showNewSum();
}
var i=1;
/*Отрисовка клинка по длине, ширне*/
function driveKnife(bladeLength, bladeHeight, originalBladeLength, originalBladeHeight){
    var transform_x = bladeLength / originalBladeLength;
    var transform_y = bladeHeight / originalBladeHeight;
    $('#blade_svg').attr('transform','scale(' + transform_x + ' ' + transform_y + ') translate(0.3 0)');
    $('#blade_wrap_svg').attr('transform','translate(-' + ((transform_x - 1) * 640) + ' -' + ((transform_y - 1) * 40) + ')');
    showNewSum();
}

var PrevSteelTexture = false;
var PrevSteelColor = false;
var addedTexture = false;

function setted() {
}
var wait = false;
/*Установка цвета/текстуры клинку/ручке*/
function setTexture(id, part, withoutCheck){
    
    switch(part){
        case 1:
            $('input[name=steel_type_select]').parent("label").addClass("not-active");
            break;
        case 2:
            $('input[name=handle_material_type_select]').parent("label").addClass("not-active");
            break;
    }
    wait = true;
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
                $('#steelPhone').css('display', 'none');
                $('#blade_svg').attr('fill',data['color']);
                PrevSteelColor = data['color'];
                $('#steelImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#steelImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                PrevSteelTexture = data['texture'];
                if(isIE || isEdge) {
                    $('#blade_svg').attr('fill','url(#patternSteel)');
                } else {
                    img = document.getElementById('steelImg');
                    img.addEventListener('load', function() { 
                        $('#blade_svg').attr('fill','url(#patternSteel)');
                    });
                }
                if (mobile == 1) {
                    $('#steelPhone label').removeClass('construct_selected');
                    $('#phone-steel_construct_'+id).addClass('construct_selected');
                } else {
                    $('.down_slid_steel label').removeClass('construct_selected');
                    $('#steel_construct_'+id).addClass('construct_selected');   
                }
                price_steel=data['price'];
                showNewSum();
                if (data['damask'] == 2) {
                    $('.notForDamask input').prop('disabled', true);
                    $('.notForDamask input').prop('checked', false);
                    $('.notForDamask').addClass('disabledAddition');
                } else {
                    if (addedTexture) $('.notForDamask input').trigger('change');
                    $('.notForDamask').removeClass('disabledAddition');
                    $('.notForDamask input').prop('disabled', false);
                }
                $('input[name=steel_type_select]').parent("label").removeClass("not-active");
                break;
            case 2:
                $('#materialPhone').css('display', 'none');
                $('#handle_svg').attr('fill',data['color']);
                $('#handleImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#handleImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                if(isIE || isEdge) {
                    $('#handle_svg').attr('fill','url(#patternHandle)');
                } else {
                    img = document.getElementById('handleImg');
                    img.addEventListener('load', function() { 
                        $('#handle_svg').attr('fill','url(#patternHandle)');
                    });
                }
                if (mobile == 1) {
                    $('#materialPhone label').removeClass('construct_selected');
                    $('#phone-handle_material_construct_'+id).addClass('construct_selected');
                } else {
                    $('.down_slid_handle_material label').removeClass('construct_selected');
                    $('#handle_material_construct_'+id).addClass('construct_selected');
                }
                //sum+=data['price'];
                nabor = data['nabor'];
                price_handle_material=data['price'];
                showNewSum();
                $('input[name=handle_material_type_select]').parent("label").removeClass("not-active");
                break;
        }

    }).fail(function (xhr,status,error){  
        console.log(error);
        setTexture(id, part, true);
    });

};
    
/*фикс выхода бегунка за линию слайда */
function fixMarginSlide(slid,max,min ){
    /* Fix handler to be inside of slider borders */
    var $Handle = slid.find('.ui-slider-handle');
    if(slid.slider('value')==min){
        $Handle.css('margin-left', '-2px');
    }else if(slid.slider('value')==max){
        $Handle.css('margin-left', -1 * $Handle.width() * (slid.slider('value') / max)+2);
    }
}

var new_max_length = 150; //значение макс длины клинка при развитом ограничителе
var new_max_butt_width = 2.4; //значение макс толшины обуха при развитом ограничителе
old_butt_width = $("input#butt_width_construct").val(); //предыдущая толщина обуха
var ABLE_PRICK = 2;  //приспособлен для укола ограничитель
var UNABLE_PRICK = 1; //не приспособлен для укола ограничитель
var flag_prick_bolster = false;
var flag_prick_handle = false;
var freed = false;
var bented = false;
var bent_restrict = false;

var HEIGHT_BENT_10 = 40;
var LENGTH_BENT_5 = 180;

var FREE = 1; // любые характеристики возможны
var NOT_FREE = 2; // любые характеристики возможны

var BENT = 1; //нож изогнут 10мм 5мм
var NOT_BENT = 2; //не изогнут 10 мм 5 мм

var already150 = false; // был ли уже restrict на 150x2.5
var some_changes = false; // Менялось ли что то функциями ограничений
var show = false; //Были ли изменены параметры конструктора что задал пользователь

/*Функция показывающая изменение range для размеров ножа*/
function showChanges(showed) {
    if(withOutAlert || !showed) return false;
    if (mobile == 1) {
        var maxBladeLength = $('#phone-sliderBladeLength').slider("option", "max");
        var maxBladeHeight = $("#phone-sliderBladeHeight").slider("option", "max");
        var maxButtWidth = $('#phone-sliderButtWidth').slider("option", "max");
        var maxHandleLength = $('#phone-sliderHandle').slider("option", "max");
        var minBladeLength = $('#phone-sliderBladeLength').slider("option", "min");
        var minBladeHeight = $("#phone-sliderBladeHeight").slider("option", "min");
        var minButtWidth = $('#phone-sliderButtWidth').slider("option", "min");
        var minHandleLength = $('#phone-sliderHandle').slider("option", "min");
    } else {
        var maxBladeLength = $('#sliderBladeLength').slider("option", "max");
        var maxBladeHeight = $("#sliderBladeHeight").slider("option", "max");
        var maxButtWidth = $('#sliderButtWidth').slider("option", "max");
        var maxHandleLength = $('#sliderHandle').slider("option", "max");
        var minBladeLength = $('#sliderBladeLength').slider("option", "min");
        var minBladeHeight = $("#sliderBladeHeight").slider("option", "min");
        var minButtWidth = $('#sliderButtWidth').slider("option", "min");
        var minHandleLength = $('#sliderHandle').slider("option", "min");
    }
    $('#rangeBladeHeight').text(minBladeHeight + " — " + maxBladeHeight + "мм");
    $('#rangeBladeLength').text(minBladeLength + " — " + maxBladeLength + "мм");
    $('#rangeButtWidth').text(minButtWidth + " — " + maxButtWidth + "мм");
    $('#rangeHandleLength').text(minHandleLength + " — " + maxHandleLength + "мм");
    $('#alertAboutRange').fadeIn('500');
    setTimeout(function(){$('#alertAboutRange').fadeOut('5000')}, 7000);
}

var timeout1 = null;
var timeout2 = null;
/*Ограничить конструктор до 150 x 2.5*/
function restrictTo150() {
    some_changes = false;
    show = false;
    bent_restrict = false;
    if ($("#sliderBladeHeight").slider('option', 'min') != MIN_BLADE_HEIGHT || $("#sliderBladeHeight").slider('option', 'max') != MAX_BLADE_HEIGHT){ 
        $("#sliderBladeHeight").slider('option',{min: MIN_BLADE_HEIGHT, max: MAX_BLADE_HEIGHT});
        some_changes = true;
    }
    if ($("#sliderBladeLength").slider('option', 'max') != new_max_length){
        hs=$("#sliderBladeLength").slider();
        hs.slider('option', {max: new_max_length});
        if ($("input#length_blade_construct").val() >= new_max_length) {
            show = true;
            if ($("input#length_blade_construct").val() == new_max_length) {
                show = false;
            } else {
                $("#contentSliderBladeLength").addClass('redRestrict');
                if (timeout1) {
                    clearTimeout(timeout1);
                    timeout1 = null;
                }
                timeout1 = setTimeout(function(){
                    $("#contentSliderBladeLength").removeClass('redRestrict');
                }, 2000);

            }
            hs.slider('option', 'value', new_max_length);
            hs.slider('option', 'slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: new_max_length });
           fixMarginSlide($("#sliderBladeLength"), new_max_length, MIN_BLADE_LENGTH );
        }
        some_changes = true;
    }
    if ($("#sliderButtWidth").slider('option', 'max') != new_max_length){
        hs=$("#sliderButtWidth").slider();
        valWidthButt = $("input#butt_width_construct").val();
        if (valWidthButt!=new_max_butt_width) {
            old_butt_width = $("input#butt_width_construct").val();
        }
        hs.slider('option', {max: new_max_butt_width});
        if ($("input#butt_width_construct").val() >= new_max_butt_width) {
            if ($("input#butt_width_construct").val()>new_max_butt_width){
                $('#contentSliderButtWidth').addClass('redRestrict');
                if (timeout2) {
                    clearTimeout(timeout2);
                    timeout2 = null;
                }
                timeout2 = setTimeout(function(){
                    $('#contentSliderButtWidth').removeClass('redRestrict');
                }, 2000);
            }
            if (!show) {
                show = true;
                if ($("input#butt_width_construct").val() == new_max_butt_width) {
                    show = false;
                }
            }
            hs.slider('option', 'value', new_max_butt_width);
            hs.slider('option','slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: new_max_butt_width });
           fixMarginSlide($("#sliderButtWidth"), new_max_butt_width, MIN_BUTT_WIDTH );
        }
        some_changes = true;
    }
    if (some_changes) showChanges(show);
}

/*Ограничить конструктор до 150 x 2.5*/
function restrictTo150Phone() {
    some_changes = false;
    show = false;
    bent_restrict = false;
    if ($("#phone-sliderBladeHeight").slider('option', 'min') != MIN_BLADE_HEIGHT || $("#phone-sliderBladeHeight").slider('option', 'max') != MAX_BLADE_HEIGHT){ 
        $("#phone-sliderBladeHeight").slider('option',{min: MIN_BLADE_HEIGHT, max: MAX_BLADE_HEIGHT});
        some_changes = true;
    }
    if ($("#phone-sliderBladeLength").slider('option', 'max') != new_max_length){
        hs=$("#phone-sliderBladeLength").slider();
        hs.slider('option', {max: new_max_length});
        if ($("input#phone-length_blade_construct").val() >= new_max_length) {
            show = true;
            if ($("input#phone-length_blade_construct").val() == new_max_length) {
                show = false;
            } else {
                $("#phone-contentSliderBladeLength").addClass('redRestrict');
                if (timeout1) {
                    clearTimeout(timeout1);
                    timeout1 = null;
                }
                timeout1 = setTimeout(function(){
                    $("#phone-contentSliderBladeLength").removeClass('redRestrict');
                }, 2000);

            }
            hs.slider('option', 'value', new_max_length);
            hs.slider('option', 'slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: new_max_length });
           fixMarginSlide($("#phone-sliderBladeLength"), new_max_length, MIN_BLADE_LENGTH );
        }
        some_changes = true;
    }
    if ($("#phone-sliderButtWidth").slider('option', 'max') != new_max_length){
        hs=$("#phone-sliderButtWidth").slider();
        valWidthButt = $("input#phone-butt_width_construct").val();
        if (valWidthButt!=new_max_butt_width) {
            old_butt_width = $("input#phone-butt_width_construct").val();
        }
        hs.slider('option', {max: new_max_butt_width});
        if ($("input#phone-butt_width_construct").val() >= new_max_butt_width) {
            if ($("input#phone-butt_width_construct").val()>new_max_butt_width){
                $('#phone-contentSliderButtWidth').addClass('redRestrict');
                if (timeout2) {
                    clearTimeout(timeout2);
                    timeout2 = null;
                }
                timeout2 = setTimeout(function(){
                    $('#phone-contentSliderButtWidth').removeClass('redRestrict');
                }, 2000);
            }
            if (!show) {
                show = true;
                if ($("input#phone-butt_width_construct").val() == new_max_butt_width) {
                    show = false;
                }
            }
            hs.slider('option', 'value', new_max_butt_width);
            hs.slider('option','slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: new_max_butt_width });
           fixMarginSlide($("#phone-sliderButtWidth"), new_max_butt_width, MIN_BUTT_WIDTH );
        }
        some_changes = true;
    }
    if (some_changes) showChanges(show);
}
/*Снять все ограничения*/
function unRestrict() {
    $('.redRestrict').removeClass('redRestrict');
    some_changes = false;
    bent_restrict = false;
    if ($("#sliderButtWidth").slider('option', 'min') != MIN_BUTT_WIDTH || $("#sliderButtWidth").slider('option', 'max') != MAX_BUTT_WIDTH){
        $("#sliderButtWidth").slider('option', {min: MIN_BUTT_WIDTH , max: MAX_BUTT_WIDTH});
        $("#butt_width_construct").val(old_butt_width);
        $("#sliderButtWidth").slider('option', 'value', old_butt_width);
        $('#contentSliderButtWidth').text(old_butt_width + ' мм');
        some_changes = true;
    }
    if ($("#sliderBladeLength").slider('option', 'min') != MIN_BLADE_LENGTH || $("#sliderBladeLength").slider('option', 'max') != MAX_BLADE_LENGTH){
        $("#sliderBladeLength").slider('option', {min: MIN_BLADE_LENGTH, max: MAX_BLADE_LENGTH});
        some_changes = true;
    }
    if ($("#sliderBladeHeight").slider('option', 'min') != MIN_BLADE_HEIGHT || $("#sliderBladeHeight").slider('option', 'max') != MAX_BLADE_HEIGHT){
        $("#sliderBladeHeight").slider('option', {min: MIN_BLADE_HEIGHT, max: MAX_BLADE_HEIGHT});
        some_changes = true; 
    }
    if (some_changes) showChanges(false);
}
/*Снять все ограничения*/
function unRestrictPhone() {
    $('.redRestrict').removeClass('redRestrict');
    some_changes = false;
    bent_restrict = false;
    if ($("#phone-sliderButtWidth").slider('option', 'min') != MIN_BUTT_WIDTH || $("#phone-sliderButtWidth").slider('option', 'max') != MAX_BUTT_WIDTH){
        $("#phone-sliderButtWidth").slider('option', {min: MIN_BUTT_WIDTH , max: MAX_BUTT_WIDTH});
        $("#phone-butt_width_construct").val(old_butt_width);
        $("#phone-sliderButtWidth").slider('option', 'value', old_butt_width);
        $('#phone-contentSliderButtWidth').text(old_butt_width + ' мм');
        some_changes = true;
    }
    if ($("#phone-sliderBladeLength").slider('option', 'min') != MIN_BLADE_LENGTH || $("#phone-sliderBladeLength").slider('option', 'max') != MAX_BLADE_LENGTH){
        $("#phone-sliderBladeLength").slider('option', {min: MIN_BLADE_LENGTH, max: MAX_BLADE_LENGTH});
        some_changes = true;
    }
    if ($("#phone-sliderBladeHeight").slider('option', 'min') != MIN_BLADE_HEIGHT || $("#phone-sliderBladeHeight").slider('option', 'max') != MAX_BLADE_HEIGHT){
        $("#phone-sliderBladeHeight").slider('option', {min: MIN_BLADE_HEIGHT, max: MAX_BLADE_HEIGHT});
        some_changes = true; 
    }
    if (some_changes) showChanges(false);
}
function heightMoreThan40() {
    some_changes = false;
    show = false;
    if ($("#sliderButtWidth").slider('option', 'min') != MIN_BUTT_WIDTH || $("#sliderButtWidth").slider('option', 'max') != MAX_BUTT_WIDTH){
        $("#sliderButtWidth").slider('option', {min: MIN_BUTT_WIDTH , max: MAX_BUTT_WIDTH});
        some_changes = true;
    }
    if ($("#sliderBladeLength").slider('option', 'min') != MIN_BLADE_LENGTH || $("#sliderBladeLength").slider('option', 'max') != MAX_BLADE_LENGTH){
        $("#sliderBladeLength").slider('option', {min: MIN_BLADE_LENGTH, max: MAX_BLADE_LENGTH});
        some_changes = true;
    }
    if ($("#sliderBladeHeight").slider('option', 'min') != HEIGHT_BENT_10){
        hs = $("#sliderBladeHeight").slider();
        hs.slider('option',{min: HEIGHT_BENT_10});
        if ($("input#height_blade_construct").val() <= HEIGHT_BENT_10) {
            show = true;
            if ($("input#height_blade_construct").val() == HEIGHT_BENT_10) {
                show = false;
            }
            hs.slider('option', 'value', HEIGHT_BENT_10);
            hs.slider('option', 'slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: HEIGHT_BENT_10 });
           fixMarginSlide($("#sliderBladeHeight"), MAX_BLADE_HEIGHT, HEIGHT_BENT_10 );
        }
        some_changes = true;
    }
    $('#choose_construct_way').css('display', 'none');
    bent_restrict = true;
    showMainScroll();
    if (some_changes) showChanges(show);
}

function lengthLessThan180() {
    some_changes = false;
    show = false;
    if ($("#sliderButtWidth").slider('option', 'min') != MIN_BUTT_WIDTH || $("#sliderButtWidth").slider('option', 'max') != MAX_BUTT_WIDTH){
        $("#sliderButtWidth").slider('option', {min: MIN_BUTT_WIDTH , max: MAX_BUTT_WIDTH});
        $("#butt_width_construct").val(old_butt_width);
        $("#sliderButtWidth").slider('option', 'value', old_butt_width);
        $('#contentSliderButtWidth').text(old_butt_width + ' мм');
        some_changes = true;
    }
    if ($("#sliderBladeHeight").slider('option', 'min') != MIN_BLADE_HEIGHT || $("#sliderBladeHeight").slider('option', 'max') != MAX_BLADE_HEIGHT){
        $("#sliderBladeHeight").slider('option', {min: MIN_BLADE_HEIGHT, max: MAX_BLADE_HEIGHT});
        some_changes = true;
    }
    if ($("#sliderBladeLength").slider('option', 'max') != LENGTH_BENT_5){
        hs = $("#sliderBladeLength").slider();
        hs.slider('option',{max: LENGTH_BENT_5});
        if ($("input#length_blade_construct").val() >= LENGTH_BENT_5) {
            show = true;
            if ($("input#length_blade_construct").val() == HEIGHT_BENT_10) {
                show = false;
            }
            hs.slider('option', 'value', LENGTH_BENT_5);
            hs.slider('option', 'slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: LENGTH_BENT_5 });
           fixMarginSlide($("#sliderBladeLength"), LENGTH_BENT_5, MIN_BLADE_LENGTH );
        }
        some_changes = true;
    }
    $('#choose_construct_way').css('display', 'none');
    bent_restrict = true;
    showMainScroll();
    if (some_changes) showChanges(show);
}

function lengthLessThan180Phone() {
    some_changes = false;
    show = false;
    if ($("#phone-sliderButtWidth").slider('option', 'min') != MIN_BUTT_WIDTH || $("#phone-sliderButtWidth").slider('option', 'max') != MAX_BUTT_WIDTH){
        $("#phone-sliderButtWidth").slider('option', {min: MIN_BUTT_WIDTH , max: MAX_BUTT_WIDTH});
        $("#phone-butt_width_construct").val(old_butt_width);
        $("#phone-sliderButtWidth").slider('option', 'value', old_butt_width);
        $('#phone-contentSliderButtWidth').text(old_butt_width + ' мм');
        some_changes = true;
    }
    if ($("#phone-sliderBladeHeight").slider('option', 'min') != MIN_BLADE_HEIGHT || $("#phone-sliderBladeHeight").slider('option', 'max') != MAX_BLADE_HEIGHT){
        $("#phone-sliderBladeHeight").slider('option', {min: MIN_BLADE_HEIGHT, max: MAX_BLADE_HEIGHT});
        some_changes = true;
    }
    if ($("#phone-sliderBladeLength").slider('option', 'max') != LENGTH_BENT_5){
        hs = $("#phone-sliderBladeLength").slider();
        hs.slider('option',{max: LENGTH_BENT_5});
        if ($("input#phone-length_blade_construct").val() >= LENGTH_BENT_5) {
            show = true;
            if ($("input#phone-length_blade_construct").val() == HEIGHT_BENT_10) {
                show = false;
            }
            hs.slider('option', 'value', LENGTH_BENT_5);
            hs.slider('option', 'slide')
           .call(hs,null,{ handle: $('.ui-slider-handle', hs), value: LENGTH_BENT_5 });
           fixMarginSlide($("#phone-sliderBladeLength"), LENGTH_BENT_5, MIN_BLADE_LENGTH );
        }
        some_changes = true;
    }
    $('#choose_construct_way').css('display', 'none');
    bent_restrict = true;
    showMainScroll();
    if (some_changes) showChanges(show);
}
/*Ограничение длины и ширины клинка*/
function restrict(data, part){
    switch(part){
        case 1:
            if(data['free'] == FREE) { 
                freed = true;
                (mobile == 1) ? unRestrictPhone() : unRestrict();
            } else{
                freed = false;
                if((flag_prick_handle || flag_prick_bolster) && data['bent'] == NOT_BENT && !already150) {
                    (mobile == 1) ? restrictTo150Phone() : restrictTo150();
                    already150 = true;
                }
            }

            if(data['bent'] == BENT) {
                bented = true;
                if(flag_prick_handle || flag_prick_bolster) {
                   // $('#choose_construct_way').css('display', 'block'); без выбора больше 180 или меньше
                   // hideMainScroll();
                   (mobile == 1) ? lengthLessThan180Phone() : lengthLessThan180();
                }

            } else {
                bented = false;
                if((flag_prick_handle || flag_prick_bolster) && !freed && !already150) {
                    (mobile == 1) ? restrictTo150Phone() : restrictTo150();
                    already150 = true;
                }
            }
            already150 = false;
            break;
        case 2:
        case 3:
            if (data['restricted'] == ABLE_PRICK) {

                if(!flag_prick_handle && !flag_prick_bolster && !freed && !bented) {
                    (mobile == 1) ? restrictTo150Phone() : restrictTo150();
                }
                if (part == 3) {
                    flag_prick_handle = true;
                }
                if (part == 2) {
                    flag_prick_bolster = true;
                }
                if (bented && !bent_restrict) {
                    // $('#choose_construct_way').css('display', 'block'); без выбора больше 180 или меньше
                    // hideMainScroll();
                   (mobile == 1) ? lengthLessThan180Phone() : lengthLessThan180();
                }
            } 
            if(data['restricted'] == UNABLE_PRICK) {
                if (part == 3) {
                    flag_prick_handle = false;
                }
                if (part == 2) {
                    flag_prick_bolster = false;
                }
                if(!flag_prick_handle && !flag_prick_bolster) {
                    (mobile == 1) ? unRestrictPhone() : unRestrict();
                }
            }
            break;
    }
}


/*Блок реакций на изменение параметров ножа*/
var price_steel=0;
var hardness_blade=0;
var hardness_handle=0;
var price_bolster=0;
var price_handle_material=0;
var nabor=0;
var fultang=0;
var simmetrical=0;
var addition_price=0;

function getHandleKoef(handleLengthFunc) {
    var afterDot = handleLengthFunc/100 - 1;
    (handleLengthFunc<100) ? (afterDot = 0):(afterDot = afterDot*2.5);
    return (1+afterDot);
}
function getBladeKoef(mBlade) {
    (mBlade<3510) ? (mBlade=1.03) : mBlade = mBlade/3510;
    return (mBlade);
}
function showNewSum() {
    if (mobile == 1) {
        var lenghtBladeConst = $('input#phone-length_blade_construct').val();
    } else {
        var lenghtBladeConst = $('input#length_blade_construct').val();
    }
    if ((nabor == 1 || lenghtBladeConst >= 140 || skvozByUser) && (fultang != 1)) {
        $('#additionBlade_3').prop('checked', true);
        $('#additionBladePhone_3').prop('checked', true);
        $('.skvoznoi').removeClass('disabledAddition');
    } else {
        if (fultang == 1) {
            $('.skvoznoi').addClass('disabledAddition');
            $('#additionBlade_3').prop('checked', false);
            $('#additionBladePhone_3').prop('checked', false);
        } else {
            $('.skvoznoi').removeClass('disabledAddition');
            if (!skvozByUser) {
                $('#additionBlade_3').prop('checked', false);
                $('#additionBladePhone_3').prop('checked', false);
            }
        }
    }
    if (mobile == 1) {
        var sBlade = lenghtBladeConst*$('input#phone-height_blade_construct').val();
        var buttWidthBlade = $("input#phone-butt_width_construct").val();
    } else {
        var sBlade = lenghtBladeConst*$('input#height_blade_construct').val();
        var buttWidthBlade = $("input#butt_width_construct").val();
    }
    var buttKoef = 1;
    if (buttWidthBlade >3.5) {
        buttKoef = 1.1;
    }
    if (buttWidthBlade >4.5) {
        buttKoef = 1.2;
    }
    var sum = 200+hardness_blade*price_steel*3.5*buttKoef*getBladeKoef(sBlade)*sBlade/3510+price_bolster+price_handle_material*hardness_handle*((mobile == 1) ? getHandleKoef($('input#phone-length_handle_construct').val()) : getHandleKoef($('input#length_handle_construct').val()))*3+500;
    
    var selectorAddition = ((mobile == 1) ? $('#mobileConstructForm .additionInput') : $('#form_constructor .additionInput'));
    $.each(selectorAddition,function(){
        if($(this).is(":checked")){
            sum+=parseInt($(this).attr('data-price'));
        }
    });
    if (sum > 4500) {
        sum = sum*1.20;
    } else {
        sum = sum*1.25;
    }
    sum = (Math.round(Math.round(sum)/10))*10;
    $('#sum').text(sum+' р.');
    $('#sumNew').text('цена: '+sum*0.9+' р.');
    $('#sumNewOld').text(sum*0.9+' р.');

    $('#sumPhone').text(sum+' р.');
    $('#sumNewPhone').text('цена: '+sum*0.9+' р.');
    $('#sumNewOldPhone').text(sum*0.9+' р.');

    //$('#sumNewOldPhone').text(sum*0.9+' р.');
    if (mobile == 1) {
        if ($('#mobileConstructForm input[name=oprosSale]').val()==1) {
            $('#sumNewOldPhone').css('display', 'block');
            $('#sumNewPhone').text('цена: '+ Math.round(sum*0.85)+' р.');
        }
    } else {
        if ($('#form_constructor input[name=oprosSale]').val()==1) {
            $('#sumNewOld').css('display', 'inline-block');
            $('#sumNew').text('цена: '+ Math.round(sum*0.85)+' р.');
        }
    }
    // $('#sumNewConsult').text(sum*0.9+' р.');
    // $('#sumNewNewConsult').text('цена: '+Math.round(sum*0.85)+' р.');
}

var fultangPathTo = '.'; // путь фултанг
var klepkaPathTo = '.'; // путь клепки для фултанг

var fultangPathPrev = '.'; // без фултанг
var klepkaPathPrev = '.'; // путь клепки без фултанг

function getPath(id, part, withoutCheck){
    switch(part){
        case 1:
            $("input[name=blade_type_select]").parent("label").addClass('not-active');
            break;
        case 2:
            $("input[name=bolster_type_select]").parent("label").addClass('not-active');
            break;
        case 3:
            $("input[name=handle_type_select]").parent("label").addClass('not-active');
            break;
    }
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
                $("#bladePhone").css('display', 'none');
                $('#blade_svg').attr('d',data['path']);
                if (mobile == 1) {
                    var height=$('input#phone-height_blade_construct').val()*4;
                    var length=$('input#phone-length_blade_construct').val()*4;
                } else {
                    var height=$('input#height_blade_construct').val()*4;
                    var length=$('input#length_blade_construct').val()*4;
                }
                driveKnife(length,height,320,80);
                restrict(data, 1);

                if (mobile == 1) {
                    $('#bladePhone label').removeClass('construct_selected');
                    $('#phone-blade_construct_'+id).addClass('construct_selected');
                } else {
                    $('.down_slid_blade label').removeClass('construct_selected');
                    $('#blade_construct_'+id).addClass('construct_selected');
                }
                hardness_blade=data['hardness'];
                showNewSum();
                $("input[name=blade_type_select]").parent("label").removeClass('not-active');
                break;
            case 2:
                $("#bolsterPhone").css('display', 'none');
                if (data['id'] == 5) {
                    fultang = 1;
                    if (nabor == 1 ) {
                        if(mobile == 1){
                            var inputToClick = $('#materialPhone .construct_selected').closest('.nabor').nextAll('.notNabor').first().find('input');
                            if (inputToClick.length>0) {
                                inputToClick.click();
                            } else {
                                $('#materialPhone .construct_selected').closest('.nabor').prevAll('.notNabor').first().find('input').click();
                            }            
                        } else {
                            var inputToClick = $('.down_slid_handle_material .construct_selected').closest('.nabor').nextAll('.notNabor').first().find('input');
                            if (inputToClick.length>0) {
                                inputToClick.click();
                            } else {
                                $('.down_slid_handle_material .construct_selected').closest('.nabor').prevAll('.notNabor').first().find('input').click();
                            }            
                        }
                    }
                    $('.nabor').addClass('disabledProperty');
                    $('.nabor label').addClass('not-active-property');
                    $('#patternHandle').attr('x', '-298');
                    $('#handleImg').attr('height', '270');
                    $('#handle_svg').attr('d',fultangPathTo);
                    $('#klepka').attr('d',klepkaPathTo);
                } else {
                    fultang = 0;
                    $('.nabor').removeClass('disabledProperty');
                    $('.nabor label').removeClass('not-active-property');
                    $('#patternHandle').attr('x', '-285');
                    $('#handleImg').attr('height', '250');
                    $('#handle_svg').attr('d',fultangPathPrev);
                    $('#klepka').attr('d',klepkaPathPrev);
                }
                simmetrical=data['simmetrical'];

                $('#bolster_svg').attr('d',data['path']);
                $('#bolster_svg').attr('fill',data['color']);
                $('#bolsterImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#bolsterImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                $('#bolster_svg').attr('data-width',data['width']);
                img = document.getElementById('bolsterImg');
                if (isIE || isEdge) {
                    $('#bolster_svg').attr('fill','url(#patternBolster)');
                } else {
                    img.addEventListener('load', function() { 
                        $('#bolster_svg').attr('fill','url(#patternBolster)');
                    });
                }
                if (mobile == 1) {
                    var lengthHandle=$('input#phone-length_handle_construct').val()*4;
                } else {
                    var lengthHandle=$('input#length_handle_construct').val()*4;
                }
                driveKnifeHandle(lengthHandle,280,0.214);
                restrict(data, 2);
                if (mobile == 1) {
                    $('#bolsterPhone label').removeClass('construct_selected');
                    $('#phone-bolster_construct_'+id).addClass('construct_selected');
                } else {
                    $('.down_slid_bolster label').removeClass('construct_selected');
                    $('#bolster_construct_'+id).addClass('construct_selected');

                }
                price_bolster=data['price'];
                showNewSum();
                $("input[name=bolster_type_select]").parent("label").removeClass('not-active');
                break;
            case 3:
                $("#handlePhone").css('display', 'none');
                fultangPathTo = data['pathFultang'];
                klepkaPathTo = data['pathKlepka'];
                handleFixBlade = data['pathFixBlade'];
                handleFixBladeFultang = data['pathFixBladeFultang'];
                fultangPathPrev = data['path'];
                if (!fultang) {
                    $('#handle_svg').attr('d',fultangPathPrev);
                    $('#klepka').attr('d',klepkaPathPrev);
                } else {
                    $('#handle_svg').attr('d',fultangPathTo);
                    $('#klepka').attr('d',klepkaPathTo);
                }
                restrict(data, 3);
                if (mobile == 1) {
                    $('#handlePhone label').removeClass('construct_selected');
                    $('#phone-handle_construct_'+id).addClass('construct_selected');
                } else {
                    $('.down_slid_handle label').removeClass('construct_selected');
                    $('#handle_construct_'+id).addClass('construct_selected');
                }
                $('.down_slid_handle label').removeClass('construct_selected');
                $('#handle_construct_'+id).addClass('construct_selected');
                typeHandle = data['heightHandle'];
                if (mobile == 1) {
                    var lengthHandle=$('input#phone-length_handle_construct').val()*4;
                } else {
                    var lengthHandle=$('input#length_handle_construct').val()*4;
                }
                driveKnifeHandle(lengthHandle,280,0.214);
                hardness_handle=data['hardness'];
                showNewSum();
                $("input[name=handle_type_select]").parent("label").removeClass('not-active');
                break;
        }
    }

    }).fail(function (xhr,status,error){
        console.log(error);
        getPath(id, part, true); 
    });
}

function sortConstruct(type){
            $('#svg').addClass('opacited');
    $.ajax({
        type:"POST",
        url:"/sortConstruct",
        data:{
            "type": type
        },
        dataType:"json"
    }).done(function(data){
        if (handleAjaxResponse(data)) return;
            $('.bladeTemplateRow').remove();
            $('#down_slid_blade').find("#bladesTypeTemplate").tmpl(data['blades']).appendTo("#down_slid_blade");
            $('.steelTemplateRow').remove();
            $('#down_slid_steel').find("#steelsTypeTemplate").tmpl(data['steels']).appendTo("#down_slid_steel");
            $('.bolsterTemplateRow').remove();
            $('#down_slid_bolster').find("#bolstersTypeTemplate").tmpl(data['bolsters']).appendTo("#down_slid_bolster");
            $('.handleTemplateRow').remove();
            $('#down_slid_handle').find("#handlesTypeTemplate").tmpl(data['handles']).appendTo("#down_slid_handle");
            $('.handleMaterialTemplateRow').remove();
            $('#down_slid_handle_material').find("#handlesMaterialTypeTemplate").tmpl(data['handleMaterials']).appendTo("#down_slid_handle_material");
            withOutAlert = true;
            $('.circleBlock').css('display', 'block');
            $('#svg').addClass('opacited');
            $('#blade_construct_'+data['blades'][0]['id']+' input').click();
            $('#steel_construct_'+data['steels'][0]['id']+' input').click();
            $('#bolster_construct_'+data['bolsters'][0]['id']+' input').click();
            $('#handle_construct_'+data['handles'][0]['id']+' input').click();
            $('#handle_material_construct_'+data['handleMaterials'][0]['id']+' input').click();
            setTimeout(function(){
                withOutAlert = false;
                $('.circleBlock').css('display', 'none');
                $('#svg').removeClass('opacited');
            },1300);
            // $('.down_slid_steel li:first-child input').click();
            // $('.down_slid_bolster li:first-child input').click();
            // $('.down_slid_handle li:first-child input').click();
            // $('.down_slid_handle_material .handleMaterialTemplateRow:first-child input').click();

    }).fail(function (xhr,status,error){
        console.log(error);
        sortConstruct();
    });
}
function setAdditionTexture(image, nameInp) {
    if ($('input[name='+nameInp+']').prop('checked')) {
        $('#blade_svg').attr('fill', PrevSteelColor);
        $('#steelImg').attr('href', patternPath + image + '?' + VERSION);
        $('#steelImg').attr('xlink:href', patternPath + image + '?' + VERSION);
        addedTexture = true;
    } else {
        $('#blade_svg').attr('fill', PrevSteelColor);
        $('#steelImg').attr('href', patternPath + PrevSteelTexture + '?' + VERSION);
        $('#steelImg').attr('xlink:href', patternPath + PrevSteelTexture + '?' + VERSION);
        addedTexture = false;
    }
    img = document.getElementById('steelImg');
    if (isEdge || isIE) {
        $('#blade_svg').attr('fill','url(#patternSteel)');
    }
    img.addEventListener('load', function() { 
        $('#blade_svg').attr('fill','url(#patternSteel)');
    });
}

/*Установка цвета клинку/ручке для отрисовки вне конструктора*/
function setTextureSec(id, part){
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
                $('#blade_svg').attr('fill',data['color']);
                $('#steelImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#steelImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION); 
                if(isIE || isEdge) {
                    $('#blade_svg').attr('fill','url(#patternSteel)');
                } else {
                    img = document.getElementById('steelImg');
                    img.addEventListener('load', function() { 
                        $('#blade_svg').attr('fill','url(#patternSteel)');
                    });
                }
                break;
            case 2:
                $('#handle_svg').attr('fill',data['color']);
                $('#handleImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#handleImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                if(isIE || isEdge) {
                    $('#handle_svg').attr('fill','url(#patternHandle)');
                } else {
                    img = document.getElementById('handleImg');
                    img.addEventListener('load', function() { 
                        $('#handle_svg').attr('fill','url(#patternHandle)');
                    });
                }
                break;
        }
    
    }).fail(function (xhr,status,error){  
        console.log(error);
        setTextureSec(id, part);
    });
}

/*Получение части ножа svg path для отрисовки вне конструктора*/
function getPathSec(id, part){
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
                $('#bolster_svg').attr('d',data['path']);
                $('#bolster_svg').attr('fill',data['color']);
                $('#bolsterImg').attr('href', patternPath + data['texture'] + '?' + VERSION);
                $('#bolsterImg').attr('xlink:href', patternPath + data['texture'] + '?' + VERSION);
                $('#bolster_svg').attr('data-width',data['width']);
                if (isIE || isEdge) {
                    $('#bolster_svg').attr('fill','url(#patternBolster)');
                } else {
                    img = document.getElementById('bolsterImg');
                    img.addEventListener('load', function() { 
                        $('#bolster_svg').attr('fill','url(#patternBolster)');
                    });
                }
                var lengthHandle=parseFloat($('#handle_length').text())*4;
                driveKnifeHandle(lengthHandle,280,0.214);
                break;
            case 3: 
                $('#handle_svg').attr('d',data['path']);
                typeHandle = data['heightHandle'];
                var lengthHandle=parseFloat($('#handle_length').text())*4;
                driveKnifeHandle(lengthHandle,280,0.214);
                break;
        }
    }

    }).fail(function (xhr,status,error){  
        console.log(error);
        getPathSec(id, part); 
    });
}

var flagForm=true;  //флаг для обязательности запонения описания ножа при не добавленном фото
/*Проверка на заполненност формы продукта*/
function handleFileSelect(evt) {
    var file = evt.target.files; // FileList object
    var f = $('input[type=file]')[0].files[0];
    var tmpName =f['name'];
    var tmpSize = f['size'];
    var maxSize = 2*1024*1024;
    if (tmpSize >= maxSize || tmpSize == 0){
        $('#note_error .captionAlert').text('Картинка должны быть меньше 2 мб');
        $('#note_message').css('display','block');
    } else {
        $('.file_upload .button').text('загрузка');
        if (!f.type.match('image.*')) {
            $('#note_error .captionAlert').text('Только картинки, пожалуйста...');
            $('#note_message').css('display','block');
        } else {
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


/*Показ превью загружнной картинки*/
function toggleFlag(){
    flagForm=true;
}

/*Показ превью загружнной картинки*/
function showPreview(){
    $('.row').css('z-index','5');
}

function validateEmail(address) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(reg.test(address) == false) {
        return false;
    } else {
        return true;
    }
}

/*Проверка на заполненность полей формы для заказа ножа*/
function checkInput(){
    if ($('#form_order input[name=phone]').val()=='' || $('#form_order input[name=name]').val()=='' || $('#form_order input[name=email]').val()=='' || $('#form_order input[name=captcha]').val()=='' || (flagForm && $('#form_order textarea[name=description]').val()=='') || !$('input[name=zoneInd]').is(':checked') || !$('#form_order input[name=conditions]').is(':checked')){
        return false;
    } else {

        if (!validateEmail($("#form_order input[name='email']").val())) {
            return false;
        } else {
            return true;
        }
    }
}

/*Проверка на заполненность полей формы для заказа ножа*/
function checkInputAuth(){
    if (flagForm && $('#form_order textarea[name=description]').val()=='') {
        return false;
    } else {
        return true;
    }
}

/*Отправка формы ножа на заказ*/
function sendImage(){
    $(".red").removeClass('red');
    $('#form_order .necessary').css('display', 'none');
    $('#noteIndividualEmail').text('Это обязательное поле');
    $('#form_order .captchaBlock .necessary').text('Это обязательное поле');
    file = new FormData();
    var f = $('input[type=file]')[0].files[0];
    if (!$('#form_order').hasClass('autorized')) {

            $('#form_order .zonesBlock').removeClass('unchoosenZone');
        if (checkInput()) {
            file.append( 'phone', $('#form_order input[name=phone]').val());
            file.append( 'email', $('#form_order input[name=email]').val());
            file.append( 'name', $('#form_order input[name=name]').val());
            file.append( 'zone', $('#form_order input[name=zoneInd]:checked').val());
            file.append( 'captcha', $('#form_order input[name=captcha]').val());
        } else {
            var flag=0;
            $('#form_order input[type=text]').next().css('display','none');
            $.each($('#form_order .form_left input[type=text]'),function(){
                if($(this).val()==''){
                    $(this).next().css('display','block');
                    if (flag<1){
                        $(this).focus();
                        flag++;
                    }
                    $(this).addClass('red');
                }
            });

            if (flag != 0) {
                $('body, html').scrollTop($('#form_order').offset().top-80);
                return false;
            }
            if (!validateEmail($("#form_order input[name='email']").val())) {
                $('#noteIndividualEmail').text('Не верный email формат');
                $('#noteIndividualEmail').css('display', 'block');
                $("#form_order input[name='email']").addClass('red');
                $("#form_order input[name='email']").focus();
                return false;
            }


            if(!$('#form_order input[name=zoneInd]').is(':checked')) {
                setTimeout(function(){$('#form_order .zonesBlock').addClass('unchoosenZone')}, 100);
            }
            $('#form_order textarea').next().css('display','none');
            if ($('#form_order textarea').val()=='') {
                $('#form_order textarea').next().css('display','block');
                if (flag<1) {
                    $(this).focus();
                        flag++;

                }
            }
            if (flag != 0) {
                return false;
            }
            flag = 0;
            $.each($('#form_order .form_right input[type=text]'),function(){
                if($(this).val()==''){
                    $(this).next().css('display','block');
                    if (flag<1){
                        $(this).focus();
                        flag++;
                    }
                    $(this).addClass('red');
                }
            });
            if (flag != 0) {
                return false;
            }
            $('#form_order .checkConditions').removeClass('notCheckedConditions');
            if (!$('#form_order input[name=conditions]').is(':checked')){
                setTimeout(function(){$('#form_order .checkConditions').addClass('notCheckedConditions')}, 100);
            }
            return;
        }
    } else {
        if (!checkInputAuth()) {
            var flag=0;
            $('#form_order textarea').next().css('display','none');
            if ($('#form_order textarea').val()=='') {
                $('#form_order textarea').next().css('display','block');
                if (flag<1) {
                    $(this).focus();
                        flag++;
                }
            }
            return;
        }
    }
   
    if (!flagForm) file.append( 'file', $('input[type=file]')[0].files[0] );
    file.append( 'description', $('#form_order textarea[name=description]').val());
    $("input").prop('disabled', true);
    $("textarea").prop('disabled', true);
    $("button[name='send']").addClass('button_pushed');
    $(".file_upload .button").addClass('button_pushed');
    $("button[name='send']").prop('disabled',true);
    $("#image_close").css('display','none');
    $.ajax({
        type:"POST",
        url:"/sendDrawing",
        data: file,
        processData: false,
        contentType: false,
        dataType:'json',
    }).done(function(data){
        handleAjaxResponse(data);
        flagForm = true;
        if (data['success'] == 1 || data['success'] == 3) {
            if (data['wrongCaptcha'] == 1) {
                $('#form_order .captchaBlock .necessary').text('Символы введены неверно');
                $('#form_order .captchaBlock .necessary').css('display','block');
                //$('#form_order input[name=captcha]']).val('');
                $("input").prop('disabled', false);
                $("textarea").prop('disabled', false);
                $("button[name='send']").removeClass('button_pushed');
                $(".file_upload .button").removeClass('button_pushed');
                $("button[name='send']").prop('disabled',false);
                $("#image_close").css('display','block');
                $('#form_order .forCaptchaBlock img').click();
                $('#form_order input[name=captcha]').val('');
                $('#form_order input[name=captcha]').focus();
                $('#form_order input[name=captcha]').addClass('red');
                return;
            } else {
                $('#form_order .forCaptchaBlock img').click();
            }
            closeDrawingOrder();
            if (data['success']==3) {
                $('.successOrder').css('display','none');
                $('.phoneError').css('display', 'block');
            } else {
                orderChanged();
                tmplNewChanges();
            }
            $('#preLogin').text(data['phone']);
            $('.close_order_construct').css('display', 'none');
            $('#wrap_construct_order').css('display','block');
            $('.stageOfOrder').css('display','none');
            $('#stage5 .onlyNext').prop('disabled', true);
            setTimeout(function(){$('#stage5 .onlyNext').prop('disabled', false)}, 600);
            $('#stage5').css('display','block');
            hideMainScroll();
            $('.stagesSelector').css('display','none');
            $('#toOrderLink').attr("href", '/home/ordersIndividual/'+data['orderId']);
        } 
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
    return false;
}


/*Получение description для конструктора*/
function showDescription(id,stage){
    $.ajax({
        type:"POST",
        url:"/getDescription",
        cache: true,
        data:{
            "id": id,
            "stage": stage
        },
        dataType:"json"
        }).done(function(data){
        if (handleAjaxResponse(data)) return;
            $('#textAbout').text(data['description']);
            if (data['image_description']) {
                $('#imageAbout').attr('src', (pathToImageDescription + data['image_description'] + '?' + VERSION));
                $('#imageAbout').css('display', 'block');
            } else {
                $('#imageAbout').css('display', 'none');
            }
            $('#aboutPartWrap').css('display', 'block');
            $('#aboutPartScrollable').getNiceScroll().resize();
            hideMainScroll();
            document.getElementById('aboutPartScrollable').scrollTop = 0;
            $('.hoveredClose').removeClass('hoveredClose');
            switch (stage) {
              case 1:
                    $('#captionAbout').text("Тип стали: "+data['name']);
                break;
              case 2:
                    $('#captionAbout').text("Клинок: "+data['name']);
                break;
              case 3:
                    $('#captionAbout').text(data['name']);
                break;
              case 4:
                    $('#captionAbout').text("Форма ручки: "+data['name']);
                break;
              case 5:
                    $('#captionAbout').text("Материал ручки: "+data['name']);
                break;
              case 6:
                    $('#captionAbout').text(data['name']);
                break;
              case 7:
                    $('#captionAbout').text(data['name'] + ' спуски');
                break;
            }
        }).fail(function (xhr,status,error){  
                console.log(error);
        });
        return false;
}

function closeAboutPart() {
    $('#aboutPartWrap').css('display', 'none');
    showMainScroll();
}
/*Проверка на наличие у элемента горизонтального скролла*/
function hasVerticalScroll(node) {
    if ( node == undefined ) {
       if ( window.innerHeight )
            return document.body.offsetHeight > innerHeight;
        else
            return document.documentElement.scrollHeight >
            document.documentElement.offsetHeight ||
            document.body.scrollHeight > document.body.offsetHeight;
    }
    else { return node.scrollHeight > node.offsetHeight; }
}

/*Проверка  поддержки css свойства*/
function supportCSS(prop) {
    var yes = false; // по умолчанию ставим ложь
    if('Moz'+prop in document.body.style) {
        yes = true; // если FF поддерживает, то правда
    }
    if('webkit'+prop in document.body.style) {
        yes = true; // если Webkit поддерживает, то правда
    }
    if('ms'+prop in document.body.style) {
        yes = true; // если IE поддерживает, то правда
    }
    if(prop in document.body.style) {
        yes = true; // если поддерживает по умолчанию, то правда
    }
    return yes; // возращаем ответ
}
/*Оплата заказа*/
function payOrder(id) {
    location.href="/payNow/"+id;
}

/*Оплата заказа*/
function payOrderAuth(id) {
    location.href="/payNowAuth/"+id;
}

/*Ajax получение продукта*/
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
                    //resizeDescriptionPopup();
                    document.getElementsByClassName('product_itself_popup')[0].scrollTop = 0; 
                });
                hideMainScroll();
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}

/*Заказ конструктора если вы авторизованы*/
function orderConstruct(){
    if (mobile==1) {
        var postData=getDataFromTwoForms('#form_construct_order','#mobileConstructForm'); 
        postData+='&blade_length_select='+$('.phoneLengths input[name=blade_length_select]').val()+'&blade_height_select='+$('.phoneLengths input[name=blade_height_select]').val()+'&butt_width_select='+$('.phoneLengths input[name=butt_width_select]').val()+'&handle_length_select='+$('.phoneLengths input[name=handle_length_select]').val()+'&additionallyConstruct='+$('#phoneAdditionText').val();
    } else {  
        var postData=getDataFromTwoForms('#form_construct_order','#form_constructor');
    }
    $('#sendButton').addClass('button_pushed');
    $('#sendButton').prop('disabled',true);
    $('#stage4 label').addClass('unhovered');
    $('#form_construct_order input').prop("disabled", true);
    $('#form_construct_close').prop("disabled", true);
        $.ajax({
            type:"POST",
            url:"/sendConstructAuth",
            async: false,
            data: postData,
            dataType:'json',
        }).done(function(data){
            if (handleAjaxResponse(data)) {
                closeConstructOrder();
                return;
            }
            if (data['success'] == 1) {
                if ((data['payId'] == PAY_CARD)|| (data['payId'] == PAY_PERSENT)) {
                    orderChanged();
                    payOrderAuth(data['orderId']);
                    return;
                } 
                $('.stageOfOrder').css('display','none');
                $('#stage5 .onlyNext').prop('disabled', true);
                setTimeout(function(){$('#stage5 .onlyNext').prop('disabled', false)}, 600);
                $('#stage5').css('display','block');
                document.getElementById('wrapForScrollOrder').scrollTop = 0;
                $('#sendButton').removeClass('button_pushed');
                $('#sendButton').prop('disabled', false);
                $('#stage4 label').removeClass('unhovered');
                $('#form_construct_order input').prop("disabled", false);
                $('#form_construct_close').prop('disabled',false);
                $('.close_order_construct').css('display', 'none');
                orderChanged();
                tmplNewChanges();
                $('#toOrderLink').attr("href", '/home/ordersConstruct/'+data['orderId']);
            }
        }).fail(function (xhr,status,error){
        console.log(error);
        });
}

/*Заказ корзины если вы авторизованы*/
function orderCart(){
    var postData = getData('#form_construct_order');  
    $('#sendButton').addClass('button_pushed');
    $('#sendButton').prop('disabled',true);
    $('#stage4 label').addClass('unhovered');
    $('#form_construct_order input').prop("disabled", true);
    $('#form_construct_close').prop("disabled", true); 
        $.ajax({
            type:"POST",
            url:"/sendCartAuth",
            async: false,
            data: postData,
            dataType:'json',
        }).done(function(data){
            if (handleAjaxResponse(data)) {
                closeConstructOrder();
                if(data['emptyCart'] == 1) {
                    checkCart();
                    closeCart();
                    if(idKnife) searchInCart(idKnife);
                }
                return;
            }
            if (data['success'] == 1) { 
                if (data['payId'] == PAY_CARD) {
                    orderChanged();
                    payOrderAuth(data['orderId']);
                    return;
                } 
                $('#wrap_cart').css('display', 'none');
                $('.stageOfOrder').css('display','none');
                $('#stage5 .onlyNext').prop('disabled', true);
                setTimeout(function(){$('#stage5 .onlyNext').prop('disabled', false)}, 600);
                $('#stage5').css('display','block');
                document.getElementById('wrapForScrollOrder').scrollTop = 0;
                $('#sendButton').removeClass('button_pushed');
                $('#sendButton').prop('disabled', false);
                $('#stage4 label').removeClass('unhovered');
                $('#form_construct_order input').prop("disabled", false);
                $('#form_construct_close').prop('disabled',false);
                $('.close_order_construct').css('display', 'none');
                initialCartView(true);
                orderChanged();
                tmplNewChanges();
                $('#toOrderLink').attr("href", '/home/ordersCart/'+data['orderId']);
            }
        }).fail(function (xhr,status,error){
        console.log(error);
        });
}

/*Возвращение назад по заполнению формы*/
function toPrev(id){ 
    $('.stageOfOrder').css('display','none');
    $('#stage'+id).css('display','block');
    $('.stagesSelector li:nth-child('+id+')').removeClass('passed_prev');
    id = parseInt(id) + 1;
    $('.stagesSelector li:nth-child('+id+')').removeClass('passed');
    $('#stage'+id+ ' li:nth-child('+id+')').css('background-color','#f2efef');
    document.getElementById('wrapForScrollOrder').scrollTop = 0;
}

function countDigits(n) {
   for(var i = 0; n > 1; i++) {
      n /= 10;
   }
   return i;
}

function showDescriptionPay(id) {
    switch(id) {
        case PAY_LATER:
            $('#textAbout').text('При выборе данного типа оплаты, мастер начинает работать над вашим изделием без предварительной оплаты. По готовности ножа вы можете отказаться от него или же если нож вам понравиться - оплатить, после чего мы вышлем его вам.');
            $('#captionAbout').text("Оплатить по готовности")
            break;
        case PAY_CARD:
            $('#textAbout').text('При выборе данного типа оплаты, вы оплачиваете полную стоимость заказа. В случае отказа от заказа на сумму до '+PAY_WITHOUT+' р. вам будут возвращены все средства. При заказе больше чем на '+PAY_WITHOUT+' р. вам будет возвращено ' +(100-PERSENT)+'% от оплаченной суммы.');
            $('#captionAbout').text("Оплатить полностью")
            break;
        case PAY_PERSENT:
            $('#textAbout').text('При выборе данного типа оплаты, вы вносите залог в ' +PERSENT+'% от стоимости заказа. В случае отказа сумма залога будет удержана в нашу пользу.');
            $('#captionAbout').text("Внести залог в 50%")
            break;
        default:
            return false;
            break;
    }
    $('#aboutPartWrap').css('display', 'block');
}

/*Показать описание типа оплаты*/
function showDescriptionSend(id) {
    $.ajax({
        type:"POST",
        url:"/getDescriptionSend",
        cache: true,
        data:{
            "id": id
        },
        dataType:"json"
        }).done(function(data){
            if (handleAjaxResponse(data)) return;
            $('#textAbout').text(data['description']);
            $('#captionAbout').text(data['name']);
            $('#aboutPartWrap').css('display', 'block');
        }).fail(function (xhr,status,error){  
            console.log(error);
        });
        return false;
}

function takeKnife(idBlade, idSteel, idBolster, idHandle, idHandleMaterial, kovka, spuskConsult){
    preSelector = ((mobile == 1) ? '#phone-' : '#');
    $(preSelector+'bolster_construct_'+idBolster+' input').click();
    $(preSelector+'blade_construct_'+idBlade+' input').click();
    $(preSelector+'steel_construct_'+idSteel+' input').click();
    $(preSelector+'handle_construct_'+idHandle+' input').click();
    if (idHandleMaterial) $(preSelector+'handle_material_construct_'+idHandleMaterial+' input').click();
    if (spuskConsult) {
        if(mobile == 1) {
            $('#spuskPhone_'+spuskConsult).click();
        } else {
            $('#spusk_'+spuskConsult).click();
        }
    }
    //$('.up_slid_blade').scrollTo('#blade_construct_'+idBlade);
    if(kovka) {
        if($('#additionBlade_2').prop('checked', false)){
            $('#additionBlade_2').click();
        };
        if($('#additionBladePhone_2').prop('checked', false)){
            $('#additionBladePhone_2').click();
        };
    } else {
        if($('#additionBlade_2').prop('checked', true)){
            $('#additionBlade_2').click();
        };   
        if($('#additionBladePhone_2').prop('checked', true)){
            $('#additionBladePhone_2').click();
        };   
    }
    $('.order_button').click();
}

function showFormConsult(){
    if (!$('#form_order').hasClass('autorized')) {
        $('#formConsult').css('display', 'block');
    }
}
function closeFormConsult(){
    $('#formConsult').css('display', 'none');
    $('#formConsult form').trigger('reset');
}
var fisher = 0;
var hunter = 0;
var tourist = 0;
var other = 0;
var zatoch = 0;
var pravka = 0;
var collection = 0;
var steelConsult = 3;
var bladeConsult = 3;
var lengthBLadeConsult = 130;
var heightBLadeConsult = 29;
var buttWidthConsult = 3.5;
var handleLengthConsult = 120;
var materialConsult = 0;
var handleConsult = 12;
var kovka = 0;
var spuskConsult = 0;

function checkOpros(id) {
    $(".red").removeClass('red');
     var flagChecked=0;
        $.each($('#stageOpros'+id+ ' input'),function(){
            if($(this).prop("checked")){
                flagChecked++;
            }
        });
        if (flagChecked==0){
            setTimeout(function(){$('#stageOpros'+id+ ' .typeSend').addClass('red')}, 100);
            return false;
        }
        $('#stageOpros'+(id+1)+' .typeSend').css('display', 'none');
        switch(id) {
            case 1:
                switch(parseInt($('#stageOpros'+id+ ' input:checked').val())) {
                    case 1:
                        fisher = 1;
                        id=parseInt(id)+1;
                        break;
                    case 2:
                        hunter = 1;
                        $('#scndQuest1').parents('.typeSend').css('display', 'block');
                        $('#scndQuest2').parents('.typeSend').css('display', 'block');
                        break;
                    case 3:
                        tourist = 1;
                        $('#scndQuest2').parents('.typeSend').css('display', 'block');
                        $('#scndQuest3').parents('.typeSend').css('display', 'block');
                        break;
                    case 4:
                        other = 1;
                        $('#scndQuest3').parents('.typeSend').css('display', 'block');
                        $('#scndQuest4').parents('.typeSend').css('display', 'block');
                        break;
                }
                break;
            case 2:
                if ((parseInt($('#stageOpros'+id+ ' input:checked').val())) == 4) {
                    id=parseInt(id)+1;
                    $('#fourthQuest1').parents('.typeSend').css('display', 'block');
                    $('#fourthQuest2').parents('.typeSend').css('display', 'block');
                }
                $('#thrdQuest1').parents('.typeSend').css('display', 'block');
                $('#thrdQuest2').parents('.typeSend').css('display', 'block');
                break;
            case 3:
                if (fisher) {
                    id=parseInt(id)+1;
                } else {
                    $('#fourthQuest1').parents('.typeSend').css('display', 'block');
                    $('#fourthQuest2').parents('.typeSend').css('display', 'block');

                }
                break;
            case 4:
                break;

        }
        if (!fisher) {
            switch(parseInt($('#stageOpros2 input:checked').val())){
                case 1:
                    handleConsult = 31;
                    bladeConsult = 7;
                    lengthBLadeConsult = 110;
                    heightBLadeConsult = 33;
                    buttWidthConsult = 3;
                    handleLengthConsult = 115;
                    spuskConsult = 1;
                    break;
                case 2:
                    handleConsult = 31;
                    bladeConsult = 3;
                    lengthBLadeConsult = 150;
                    heightBLadeConsult = 35;
                    buttWidthConsult = 4.5;
                    handleLengthConsult = 120;
                    break;
                case 3:
                    handleConsult = 31;
                    bladeConsult = 18;
                    lengthBLadeConsult = 110;
                    heightBLadeConsult = 27;
                    buttWidthConsult = 2.7;
                    handleLengthConsult = 115;
                    break;
                case 4:
                    collection = 1;
                    handleConsult = 31;
                    bladeConsult = 21;
                    lengthBLadeConsult = 135;
                    heightBLadeConsult = 29;
                    buttWidthConsult = 3;
                    handleLengthConsult = 120;
                    materialConsult = 2;
                    break;
            }
        } else {
            spuskConsult = 1;
            bladeConsult = 5;
            lengthBLadeConsult = 135;
            heightBLadeConsult = 27;
            buttWidthConsult = 2;
            handleLengthConsult = 120;
            materialConsult = 4;
            handleConsult = 18;
        }
        if (id==4) {
            switch(parseInt($('#stageOpros3 input:checked').val())){
                case 1:
                    if (fisher) {
                        steelConsult = 3;
                    } else {
                        zatoch  = 1;
                    }
                    break;
                case 2:
                    if (fisher) {
                        steelConsult = 2;
                    } else {
                        pravka = 1;
                    }
                    break;
            }
            switch(parseInt($('#stageOpros4 input:checked').val())){
                case 1:
                    if (!fisher) {
                        if (pravka) {
                            steelConsult = 2;
                        }else {
                            steelConsult = 3;
                        }
                        if(collection) {
                            steelConsult = 3;
                            kovka = 1;
                        }
                    }
                    break;
                case 2:
                    if (!fisher) {
                        if (pravka) {
                            steelConsult = 17;
                        }else {
                            steelConsult = 14;
                        }
                        if(collection) {
                            steelConsult = 16;
                        }
                    }
                    break;
            }
            takeKnife(bladeConsult,steelConsult,4,handleConsult,materialConsult, kovka, spuskConsult);
            var preSelector = ((mobile == 1) ? '#phone-' : '#');
            $( preSelector+"contentSliderBladeHeight" ).html(heightBLadeConsult.toFixed(1)+' мм');
            $("input"+preSelector+"height_blade_construct").val(heightBLadeConsult);
            $("input"+preSelector+"height_blade_construct").trigger('change');
            $( preSelector+"contentSliderBladeLength" ).html(lengthBLadeConsult.toFixed(1)+' мм');
            $("input"+preSelector+"length_blade_construct").val(lengthBLadeConsult);
            $("input"+preSelector+"length_blade_construct").trigger('change');
            $( preSelector+"contentSliderHandle" ).html(handleLengthConsult.toFixed(1)+' мм');
            $("input"+preSelector+"length_handle_construct").val(handleLengthConsult);
            $("input"+preSelector+"length_handle_construct").trigger('change');
            hs=$(preSelector+"sliderBladeLength").slider();
            hs.slider('option', 'value', lengthBLadeConsult);
            hs.slider('option', 'slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: lengthBLadeConsult });
            hs=$(preSelector+"sliderHandle").slider();
            hs.slider('option', 'value', handleLengthConsult);
            hs.slider('option', 'slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: handleLengthConsult });
            hs=$(preSelector+"sliderBladeHeight").slider();
            hs.slider('option', 'value', heightBLadeConsult);
            hs.slider('option', 'slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: heightBLadeConsult });
            hs=$(preSelector+"sliderButtWidth").slider();
            hs.slider('option', 'value', buttWidthConsult);
            hs.slider('option', 'slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: buttWidthConsult });
            closeOpros();
            (mobile == 1) ? $('#mobileConstructForm input[name=oprosSale]').val(1) : $('#form_constructor input[name=oprosSale]').val(1);
            //showFormConsult();
            fisher = 0;
            hunter = 0;
            tourist = 0;
            other = 0;
            zatoch = 0;
            pravka = 0;
            kovka = 0;
            collection = 0;
            bladeConsult = 3;
            handleLengthConsult = 120;
            lengthBLadeConsult = 130;
            heightBLadeConsult = 29;
            buttWidthConsult = 3.5;
            handleConsult = 12;
            steelConsult = 3;
            materialConsult = 0;
        }
    $('.stageOfOrder').css('display','none');
    id=parseInt(id)+1;
    //$('.stagesSelector li:nth-child('+id+')').addClass('passed');
    $('#stageOpros'+id).css('display','block');
}

function consultSuccessClose(){
    showMainScroll();
    $('#consultSuccess').css('display', 'none');   
}

function formConsultHide() {
    if ($('#formConsult').hasClass('hidedConsult')) {
        formConsultShow();
        return;
    }
    $('#formConsult').css('right', '-200px')
    $('#formConsult').addClass('hidedConsult');
}
function formConsultShow(){
    $('#formConsult').css('right', '0')
    $('#formConsult').removeClass('hidedConsult');
}

/*Проверка заполненности частей формы заказа конструктора по id*/
function checkStages(id) {
    $(".red").removeClass('red');
    $('#captchaConstrucNecessary').css('display', 'none');
    var flag=0;
    var typeOfOrder=$('#wrap_construct_order').data("typeoforder");
    $.each($('#stage'+id+' input'),function(){
        if($(this).val()=='' && !$(this).hasClass('notNecessary')){
            if (flag<1){
                $(this).focus();
                flag++;
            }
            $(this).addClass('red');
        }
    });
    if (id == 1) {

    }
    if (flag != 0) {
        return false;
    }
    if (id == 3) { //IE bug
        var index = parseInt($('input[name=mailIndex]').val().replace(/\D+/g,""));
        if(index != '' && countDigits(index) < 6 ){
            $('input[name=mailIndex]').focus();
            return false;
        }
        //if (parseInt($('#sum').text().replace(/\D+/g,""))>PAY_WITHOUT) {
            $('#payReady').css('display', 'none');
        //} else {
        //    $('#payPersent').css('display', 'none');
        //}
        var flagChecked = 0;
        $.each($('#stage'+id+ ' .typeSend input'),function(){
            if($(this).prop("checked")){
                flagChecked++;
            }
        });
        if (flagChecked==0){
            setTimeout(function(){$('#stage'+id+ ' .typeSend').addClass('red')}, 100);
            return false;
        }
    }
    if (!$('#form_construct_order').hasClass('autorizedConstructOrder')){
        $('#noteEmail').css('display', 'none');
        if (!validateEmail($("#stage1 input[name='email']").val())) {
            setTimeout(function(){$('#noteEmail').css('display', 'block')}, 100);;
            $("#stage1 input[name='email']").addClass('red');
            $("#stage1 input[name='email']").focus();
            return false;
        }
        $('#form_construct_order .zonesBlock').removeClass('unchoosenZonePopup');
        if(!$('#form_construct_order input[name=zone]').is(':checked') && id == 1) {
            $('#form_construct_order .noteZone').css('display', 'block');
            setTimeout(function(){$('#form_construct_order .zonesBlock').addClass('unchoosenZonePopup')}, 100);
            return false;
        }
        $('#form_construct_order .noteZone').css('display', 'none');
        $('#form_construct_order .checkConditions').removeClass('notCheckedConditions');
        if (!$('#form_construct_order input[name=conditions]').is(':checked')) {
            setTimeout(function(){$('#form_construct_order .checkConditions').addClass('notCheckedConditions')}, 100);
            return false;
        }
    }
    if (flag != 0) {
        return false;
    } else if ( id === 4 && typeOfOrder === construct) {
        //if (!$("#stage4 input[name=type_of_payment]").is(":checked")) return false;
        if (mobile==1) {
            var postData=getDataFromTwoForms('#form_construct_order','#mobileConstructForm'); 
            postData+='&blade_length_select='+$('.phoneLengths input[name=blade_length_select]').val()+'&blade_height_select='+$('.phoneLengths input[name=blade_height_select]').val()+'&butt_width_select='+$('.phoneLengths input[name=butt_width_select]').val()+'&handle_length_select='+$('.phoneLengths input[name=handle_length_select]').val()+'&additionallyConstruct='+$('#phoneAdditionText').val();
        } else {  
            var postData=getDataFromTwoForms('#form_construct_order','#form_constructor');
        }
        var captcha = $('#form_construct_order input[name=captcha]').val();
        postData+=('&captcha='+captcha);
        $('#sendButton').addClass('button_pushed');
        $('#sendButton').prop('disabled',true);
        $('#stage4 label').addClass('unhovered');
        $('#form_construct_order input').prop("disabled", true);
        $('#form_construct_close').prop("disabled", true);
        $.ajax({
            type:"POST",
            url:"/sendConstruct",
            async: false,
            data: postData,
            dataType:'json',
        }).done(function(data){
            if (handleAjaxResponse(data)) {
                closeConstructOrder();
                return;
            }
            if (data['success']==1 || data['success']==3) {
                if (data['wrongCaptcha'] == 1) {
                    $('#captchaConstrucNecessary').css('display', 'block');
                    $('#sendButton').removeClass('button_pushed');
                    $('#sendButton').prop('disabled',false);
                    $('#stage4 label').removeClass('unhovered');
                    $('#form_construct_order input').prop("disabled", false);
                    $('#form_construct_close').prop("disabled", false);
                    $('#form_construct_order input[name=captcha]').val('');
                    $('#form_construct_order .forCaptchaBlock img').click();
                    $('#form_construct_order input[name=captcha]').focus();
                    setTimeout(function(){$('#form_construct_order input[name=captcha]').addClass('red')}, 150);
                    return;
                }
                if (data['success']==3) {
                    $('.successOrder').css('display','none');
                    $('.phoneError').css('display', 'block');
                } else {
                    orderChanged();
                    tmplNewChanges();
                }
                if (data['success']==1 && ((data['payId'] == PAY_CARD) || (data['payId'] == PAY_PERSENT))) {
                    payOrder(data['orderId']);
                    return;
                }
                $('#preLogin').text(data['phone']);
                $('.stageOfOrder').css('display','none');
                $('.stagesSelector li:nth-child('+id+')').addClass('passed_prev');
                id=parseInt(id)+1;
                $('.stagesSelector li:nth-child('+id+')').addClass('passed');
                $('#stage5 .onlyNext').prop('disabled', true);
                setTimeout(function(){$('#stage5 .onlyNext').prop('disabled', false)}, 600);
                $('#stage'+id).css('display','block');
                $('#stage'+id+ ' li:nth-child('+id+')').css('background-color','#555555');
                document.getElementById('wrapForScrollOrder').scrollTop = 0;
                $('#sendButton').removeClass('button_pushed');
                $('#sendButton').prop('disabled', false);
                $('#stage4 label').removeClass('unhovered');
                $('#form_construct_order input').prop("disabled", false);
                $('#form_construct_close').prop('disabled',false);
                $('.close_order_construct').css('display', 'none');
                $('#toOrderLink').attr("href", '/home/ordersConstruct/'+data['orderId']);
                $('.passwordBlockWrap input[name=password]').focus();
            }
        }).fail(function (xhr,status,error){
        console.log(error);
        });
    } else if (id === 4 && typeOfOrder === cart) {
        //if (!$("#stage4 input[name=type_of_payment]").is(":checked")) return false;
        var postData = getData('#form_construct_order'); 
        var captcha = $('#form_construct_order input[name=captcha]').val();
        postData+=('&captcha='+captcha);
        $('#sendButton').addClass('button_pushed');
        $('#sendButton').prop('disabled', true);
        $('#stage4 label').addClass('unhovered');
        $('#form_construct_order input').prop("disabled", true);
        $('#form_construct_close').prop("disabled", true);
        $.ajax({
            type:"POST",
            url:"/sendCart",
            async: false,
            data: postData,
            dataType:'json',
        }).done(function(data){
            if (handleAjaxResponse(data)) {
                closeConstructOrder();
                if(data['emptyCart'] == 1) {
                    checkCart();
                    closeCart();
                    if(idKnife) searchInCart(idKnife);
                }
                return;
            }
            if (data['success'] == 1|| data['success']==3) {
                if (data['wrongCaptcha'] == 1) {
                    $('#captchaConstrucNecessary').css('display', 'block');
                    $('#sendButton').removeClass('button_pushed');
                    $('#sendButton').prop('disabled',false);
                    $('#stage4 label').removeClass('unhovered');
                    $('#form_construct_order input').prop("disabled", false);
                    $('#form_construct_close').prop("disabled", false);
                    $('#form_construct_order input[name=captcha]').val('');
                    $('#form_construct_order .forCaptchaBlock img').click();
                    $('#form_construct_order input[name=captcha]').focus();
                    setTimeout(function(){$('#form_construct_order input[name=captcha]').addClass('red')}, 150);
                    return;
                }
                if (data['success']==3) {
                    $('.successOrder').css('display','none');
                    $('.phoneError').css('display', 'block');
                } else {
                    orderChanged();
                    tmplNewChanges();
                }  
                if (data['success']==1 && (data['payId'] == PAY_CARD)) {
                    payOrder(data['orderId']);
                    return;
                }
                $('#preLogin').text(data['phone']);
                $('#wrap_cart').css('display', 'none');
                $('.stageOfOrder').css('display','none');
                $('.stagesSelector li:nth-child('+id+')').addClass('passed_prev');
                id = parseInt(id) + 1;
                $('.stagesSelector li:nth-child('+id+')').addClass('passed');
                $('#stage5 .onlyNext').prop('disabled', true);
                setTimeout(function(){$('#stage5 .onlyNext').prop('disabled', false)}, 600);
                $('#stage'+id).css('display','block');
                $('#stage'+id+ ' li:nth-child('+id+')').css('background-color','#555555');
                document.getElementById('wrapForScrollOrder').scrollTop = 0;
                $('#sendButton').removeClass('button_pushed');
                $('#sendButton').prop('disabled', false);
                $('#stage4 label').removeClass('unhovered');
                $('#form_construct_order input').prop("disabled", false);
                $('#form_construct_close').prop('disabled',false);
                $('.close_order_construct').css('display', 'none');
                if (data['success'] == 1) initialCartView(true);
                $('#toOrderLink').attr("href", '/home/ordersCart/'+data['orderId']);
                $('.passwordBlockWrap input[name=password]').focus();
            }
        }).fail(function (xhr,status,error){
        console.log(error);
        });
    } else if (id === 4 && typeOfOrder === constructAuth) {
        //if (!$("#stage4 input[name=type_of_payment]").is(":checked")) return false;
        var flagChecked = 0;
        $.each($('#stage'+id+ ' .typeSend input'),function(){
            if($(this).prop("checked")){
                flagChecked++;
            }
        });
        if (flagChecked==0){
            setTimeout(function(){$('#stage'+id+ ' .typeSend').addClass('red')}, 100);
            return false;
        }
        orderConstruct();
    } else if (id === 4 && typeOfOrder === cartAuth) {
        //if (!$("#stage4 input[name=type_of_payment]").is(":checked")) return false;
        var flagChecked = 0;
        $.each($('#stage'+id+ ' .typeSend input'),function(){
            if($(this).prop("checked")){
                flagChecked++;
            }
        });
        if (flagChecked==0){
            setTimeout(function(){$('#stage'+id+ ' .typeSend').addClass('red')}, 100);
            return false;
        }
        orderCart();
    } else if(id === 4){
        alert("Ошибка отправки формы");
    } else {
        $('.stageOfOrder').css('display','none');
        $('.visibleStage').removeClass('visibleStage');
        $('.stagesSelector li:nth-child('+id+')').addClass('passed_prev');
        id=parseInt(id)+1;
        $('.stagesSelector li:nth-child('+id+')').addClass('passed');
        $('#stage'+id).css('display','block');
        if ($('#stage'+id+ ' input[type=text]:first-child').val() == '' && mobile !=1) {
            $('#stage'+id+ ' input[type=text]:first-child').focus();
        }
        if (id == 4) {
            if (clickedCaptcha) {
                $('#wrap_construct_order .forCaptchaBlock img').click();
                clickedCaptcha = false;
            }
            if(mobile !=1) $('#form_construct_order input[name=captcha]').focus();
        }
        $('#stage'+id).addClass('visibleStage');
        $('#stage'+id+ ' li:nth-child('+id+')').css('background-color','#555555');
        document.getElementById('wrapForScrollOrder').scrollTop = 0;
    }
}

function focusPhone(selector) {
    if(selector == undefined) {
        selector = "";
    }
    selector = selector + ' .phone';
    var phone = $(selector).val();
    if (phone == "" || phone == "+7(___) ___-__-__"){
        $(selector).attr('placeholder', '');
        $(selector).focus();
        setTimeout(function(){$(selector).attr('placeholder', 'Ваш телефон')}, 200);
    }
}

/*Ширина скролла*/
function scrollbarWidth() {
  var documentWidth = parseInt(document.documentElement.clientWidth);
  var windowsWidth = parseInt(window.innerWidth);
  var scrollbarWidth = windowsWidth - documentWidth;
  return scrollbarWidth;
}

var scrollWidthConst = scrollbarWidth();
/*Ресайз футера телефона в попапах*/
function resizePhoneFooter(){
    var windowsWidth = parseInt(window.innerWidth);
    $(".footer_for_phone").css('width', windowsWidth-scrollWidthConst);
}
var hiden=false
/*Скрыть главный скролл*/
var prevMargin = parseInt($(".header_right").css("margin-right"));
function hideMainScroll(){
    if(!hiden){
        var windowsWidth = parseInt(window.innerWidth);
        if (!$('header').hasClass('mainHeaderHided') && $('body').hasClass('indexBody')) {
            if(windowsWidth>1240){
               $("header .container").css("right", scrollWidthConst/2+'px');
            } else if (windowsWidth<=1200){
                $("header .container").css("right", '0px');
                $(".header_right").css("margin-right", prevMargin+scrollWidthConst+'px')
            } else {
                $("header .container").css("right", scrollWidthConst+'px');
            }
        }
        //resizePhoneFooter();
        $("body").css("margin-right", scrollWidthConst+'px');
        $("html, body").css("overflow","hidden");
        if ($('body').hasClass('productBody')) {
            $("html, body, .container").css("height","auto");
        }
        $("body").css("position","relative");

    }
}
function showOpros() {
    $('#wrap_opros').css('display', 'block');
    $('.stageOfOrder').css('display','none');
    $('#stageOpros1').css('display','block'); 
    hideMainScroll();
}
function closeOpros() {
    $('#wrap_opros').css('display', 'none');
    $('#opros').trigger('reset');
    showMainScroll();
}
/*Показать главный скролл*/
function showMainScroll(){
    $("html, body").css("overflow","auto");
    $("html, body").css("overflow-x","hidden");
    $("body").css("position","static");
    $("body").css("margin-right",'0px');
    if (!$('header').hasClass('mainHeaderHided')) {
        $(".header_right").css("margin-right",'');
    }
    if ($('body').hasClass('indexBody')) $("header .container").css("right",'0px');
    //$("html, body, .container").css("height","100%");
    hiden=false;
}
/*const*/ var construct = 1;
/*const*/ var cart = 2;
/*const*/ var constructAuth = 3;
/*const*/ var cartAuth = 4;
/*const*/ var clickedCaptcha = true;
/*Открытие формы заказа ножа по конструктору или корзине*/
function orderShow(typeOfOrder){
    clickedCaptcha = true;
    $('#sendTypeAuth').css('display','none');
    $('.stagesSelector').css('display','block');
    $('.pay_for_construct').css('display', 'none');
    $('.pay_for_cart').css('display', 'none');
    $('.constructorOrderText').css('display', 'none');
    $('.cartOrderText').css('display', 'none');
    var flagAuthHeight = false;
    if (typeOfOrder == construct || typeOfOrder == constructAuth) {
        var sumConstruct = ((mobile == 1) ? parseInt($('#sumNewPhone').text().replace(/\D+/g,"")) : parseInt($('#sumNew').text().replace(/\D+/g,"")));
        $('#aboutPay').html('Чтобы кузнец начал изготовление ножа, вам нужно будет внести предоплату в ' + sumConstruct + ' * ' + PERSENT + '% = <strong>' + (sumConstruct*PERSENT/100).toFixed(0)+' рублей.</strong><br> <span class="rekvizity">Реквизиты нашего сервиса вам сообщат после согласования заказа.</span>');
    }
    if (typeOfOrder == construct) {
        $('#stage1').css('display','block');
        $('#whatOrder').text('Заказ ножа');
        $('#wrap_construct_order').data("typeoforder", construct);
        $('#stage1 p').text("Мы перезвоним вам и сообщим сроки выполнения заказа. Так же укажите зону проживания, чтобы мы не разбудили вас ночью.");
        $('.pay_for_construct').css('display', 'block');
        $('.constructorOrderText').css('display', '');
    } else if (typeOfOrder == cart) {
        $('#stage1').css('display','block');
        $('#whatOrder').text('Заказ, корзина');
        $('#wrap_construct_order').data("typeoforder", cart);
        $('#stage1 p').text("Мы перезвоним вам для согласования заказа. Так же укажите зону проживания, чтобы мы не разбудили вас ночью.");
        $('#wrap_cart').css('display', 'none');
        $('.pay_for_cart').css('display', 'block');
        $('.cartOrderText').css('display', '');
    } else if (typeOfOrder == constructAuth) {
        $('#whatOrder').text('Заказ ножа');
        $('#wrap_construct_order').data("typeoforder", constructAuth);
        $('.stageOfOrder').css('display','none');
        $('#stage4').css('display','block');
        $('#sendTypeAuth').css('display','block');
        $('.stagesSelector').css('display','none');
        $('.pay_for_construct').css('display', 'block');
        $('.constructorOrderText').css('display', '');
        //if (parseInt($('#sum').text().replace(/\D+/g,""))>PAY_WITHOUT) {
            $('#payReady').css('display', 'none');
        //} else {
        //    $('#payPersent').css('display', 'none');
        //}
        flagAuthHeight = true;
    } else if (typeOfOrder == cartAuth) {
        $('#whatOrder').text('Заказ, корзина');
        $('#wrap_cart').css('display', 'none');
        $('#wrap_construct_order').data("typeoforder", cartAuth);
        $('#sendTypeAuth').css('display','block');
        $('.stageOfOrder').css('display','none');
        
        $('#stage1').css('display','block');
        $('.stagesSelector').css('display','none');
        $('.pay_for_cart').css('display', 'block');
        $('.cartOrderText').css('display', '');
        //if (parseInt($('#sum').text().replace(/\D+/g,""))>PAY_WITHOUT) {
            $('#payReady').css('display', 'none');
        //} else {
        //    $('#payPersent').css('display', 'none');
        //}
        flagAuthHeight = true;
    }
    hideMainScroll();
    $('#wrap_construct_order').css('display','block');
    $('#wrap_construct_order .forCaptchaBlock img').click();
    document.getElementById('wrapForScrollOrder').scrollTop = 0;
}

function closeConstructOrderConfirmation() {
    if ($('#stage1').is(':visible')) {
        var closeNow = 0;
        $.each($('#stage1 .leftSide input[name=phone], #stage1 .leftSide input[name=email]'),function(){
            if($(this).val() !== ''){
                closeNow++;
            }
        });
        if ($('input[name=zone]').is(':checked')) closeNow++;
        if(closeNow > 0) {
            $("#confirmationCloseConstruct").css('display', 'block');
        } else {
            closeConstructOrder();
        }
    } else if(!$('#stage5').is(':visible')){
        $("#confirmationCloseConstruct").css('display', 'block');
    }
}
function refuseCloseConstructOrder() {
    $("#confirmationCloseConstruct").css('display', 'none');
}

/*Сообщение о подтверждении отказа от заказа*/
function confirmationAddPassword(){
    password = $('input[name=password]').val(); 
    passwordCheck = $('input[name=passwordCheck]').val();
    if (!$('.successOrder').is(':visible')) {
        closeConstructOrder();
    } else {
        if (password != '' || passwordCheck != '') {
            addPassword();
        } else {
            $('#confirmationPassword').css('display','block');
        }
    }
}

/*Скрыть сообщение о подтверждении отказа от заказа*/
function rejectAddPassword(){
    $('#confirmationPassword').css('display','none');
}
/*Добавление пароля*/
function addPassword(){
    $('#confirmationPassword').css('display','none');
    password = $('input[name=password]').val(); 
    passwordCheck = $('input[name=passwordCheck]').val();
    $('#validation').hide(); 
    if (password !== passwordCheck && (passwordCheck || password)) {
        $('#validation').text('Пароли не совпадают');
        setTimeout(function(){$('#validation').show()}, 100);
        return;
    }
    if (password) {
        if (password.length < 8 || password.length > 20)  {
            $('#validation').text('Пароль должен содержать от 8 до 20 символов');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
        if (password.search(/[A-ZА-Я]/g) == -1) {
            $('#validation').text('Пароль должен содержать буквы верхнего и нижнего регистров');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
        if (password.search(/[a-zа-я]/g) == -1) {
            $('#validation').text('Пароль должен содержать буквы верхнего и нижнего регистров');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
        if (password.search(/[0-9]/g) == -1) {
            $('#validation').text('Пароль должен содержать хотя бы 1 цифру');
            setTimeout(function(){$('#validation').show()}, 100);
            return;
        }
    }
    $('#validation').hide(); 
    $.ajax({
        type:'POST',
        async: false,
        url:"/passwordAdd",
        data: {
            "password": password
        }, 
        dataType:'json',
        success: function(data){
            if (handleAjaxResponse(data)) return;
            if(data['success'] == 1){
                closeConstructOrder();
                document.location.href = '/home';
            } else if (data['success'] == 2) {
                closeConstructOrder();
            }
        },
        error: function(xhr,status,error){  
            console.log(error);
        }
    });
}
/*Действия по закрытии формы отправки заказа*/
function closeConstructOrder(){
    $('#confirmationCloseConstruct').css('display', 'none');
    $(".red").removeClass('red');
    if ($('#stage5').is(':hidden') && ($('#wrap_construct_order').data("typeoforder") == cart || $('#wrap_construct_order').data("typeoforder") == cartAuth)) {
        $('#wrap_cart').css('display', 'block');
    }
    $('.successOrder').css('display','block');
    $('.phoneError').css('display','none');
    $('#wrap_construct_order').css('display','none');
    $('#form_construct_order').trigger('reset');
    $('.stagesSelector li').removeClass('passed');
    $('.stagesSelector li').removeClass('passed_prev');
    $('.stagesSelector li:first-child').addClass('passed');
    $('.stageOfOrder').css('display','none');
    $('#stage4 label').removeClass('button_pushed');
    $('.stageOfOrder:first-child').css('display','block');
    $('#stage4 .next').removeClass('button_pushed');
    $('#stage4 label').removeClass('unhovered');
    $('#form_construct_order input').prop("disabled", false);
    $('#form_construct_close').prop('disabled',false);
    $('#stage4 .next').prop('disabled',false);
    $('#sendButton').removeClass('button_pushed');
    $('#sendButton').prop('disabled', false);
    if(!$('#cart').is(':visible')){
        showMainScroll();
    }
    $('#form_order .forCaptchaBlock img').click();
    $('.close_order_construct').css('display', 'block');
}

/*Действия по закрытии формы отправки индвидуального заказа*/   
function closeDrawingOrder(){
    $('.successOrder').css('display','block');
    $('.phoneError').css('display','none');
    $('#form_order').trigger('reset');
    $("input").prop('disabled', false);
    $("textarea").prop('disabled', false);
    $('#form_order input[type=text]').next().css('display','none');
    $('#form_order textarea').next().css('display','none');
    $('.file_upload .button').text('выбрать картинку');
    $('.row').css('z-index','-5');
    $("button[name='send']").removeClass('button_pushed');
    $(".file_upload .button").removeClass('button_pushed');
    $("button[name='send']").prop('disabled',false);
    $("#image_close").css('display','block');
}
/*Клики на свойства ножа при загрузке сайта*/
function chooseFirstsConstruct(){
    if (mobile != 1) {
        $('.down_slid_blade li:first-child input').click();
        $('.down_slid_steel li:first-child input').click();
        $('.down_slid_bolster li:first-child input').click();
        $('.down_slid_handle li:first-child input').click();
        $('.down_slid_handle_material li:first-child input').click();
    }else{
        $('#bladePhone li:first-child input').click();
        $('#steelPhone li:first-child input').click();
        $('#bolsterPhone li:first-child input').click();
        $('#handlePhone li:first-child input').click();
        $('#materialPhone li:first-child input').click();
    }
    setTimeout(function(){withOutAlert = false;},2000);   
}
            
// function browser(){
//     // получаем данные userAgent
//     var ua = navigator.userAgent;    
//     return ua;
//     // с помощью регулярок проверяем наличие текста,
//     // соответствующие тому или иному браузеру
//     if (ua.search(/Chrome/) > 0) return 'Google Chrome';
//     if (ua.search(/Firefox/) > 0) return 'Firefox';
//     if (ua.search(/Opera/) > 0) return 'Opera';
//     if (ua.search(/Safari/) > 0) return 'Safari';
//     if (ua.search(/MSIE/) > 0) return 'Internet Explorer';
//     if (ua.search(/Edge/) > 0) return 'Edge';
//     // условий может быть и больше.
//     // сейчас сделаны проверки только 
//     // для популярных браузеров
//     return 'Не определен';
// }
 


/*Сообщение о подтверждении отказа от заказа*/
function confirmationRefuse(){
    $('#confirmationRefuse').css('display','block');
    hideMainScroll();
}

/*Скрыть сообщение о подтверждении отказа от заказа*/
function rejectRefuse(){
    $('#confirmationRefuse').css('display','none');
    showMainScroll();
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
        orderChanged();
        if (handleAjaxResponse(data)) {
            rejectRefuse();
            return;
        }
        if(data['success'] == 1){
            rejectRefuse();
            location.reload();
           // sendSocket(); 
        }
    }).fail(function (xhr,status,error){ 
        console.log(error);
    });
}

/*Отказ от заказа и удаление аккаунта неавторизованным*/
function refuseOrderUnauth(orderId, typeOrder){
    file = new FormData();
    file.append('orderId', orderId);
    file.append('typeOrder', typeOrder);
    $.ajax({
        type:"POST",
        url:"/refuseOrderUnauth",
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
            rejectRefuse();
            location.reload();
        }
    }).fail(function (xhr,status,error){  
        console.log(error);
    });
}



var countData = null;

/*формирование шаблона изменений (новых сообщений)*/
function tmplNewChanges(){
    $.ajax({
        type:'POST',
        url:"/getNewChanges",
        dataType:'json',
        success: function(data){
            if (data['success'] === 1) {
                countData = data['newCount'];
                var countBox=$('#countBox');
                if(countData){
                    $('.newMessageCount').remove();
                    countBox.find("#countTemplate").tmpl(countData).appendTo("#countBox");
                }
            } else {
                countData = null;
            }
            handleAjaxResponse(data, true);
        },
        error: function(xhr,status,error){  
                console.log(error);
            }
    });
}

