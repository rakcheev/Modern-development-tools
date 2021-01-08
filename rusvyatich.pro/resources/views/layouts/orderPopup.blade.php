<div id="wrap_construct_order">
        <div id="way_to_buy">
            <div class="phoneLineClose">
                <span id="whatOrder">Заказ ножа</span>
            </div>
            <div class="close_order_construct">
                <button id="form_construct_close" class="window_close" onclick="@if(!Session::has('userId'))closeConstructOrderConfirmation(); @else closeConstructOrder(); @endif return false;">Закрыть</button>
            </div>
            <div class="wrapForScrollOrder" id="wrapForScrollOrder">
                <ul class="stagesSelector clearfix">
                    <li class="passed">
                        <a href="#" onclick="return false;">Телефон</a>
                    </li>
                    <li>
                        <a href="#" onclick="return false;">Имя</a>
                    </li>
                    <li>
                        <a href="#" onclick="return false;">Доставка</a>
                    </li>
                    <li>
                        <a href="#" onclick="return false;">Оплата</a>
                    </li>
                    <li>
                        <a href="#" >Готово</a>
                    </li>
                </ul>
                <form id="form_construct_order" method="POST" class="form_construct_order clearfix @if (!empty(Session::get('userId'))) autorizedConstructOrder
                    @endif">
                    <div id="stage1" class="stageOfOrder">
                        <h5>Введите ваш телефон и email</h5>
                        <p>
                            Мы перезвоним вам и сообщим сроки выполнения заказа. Так же укажите зону проживания, чтобы мы не разбудили вас ночью.
                        </p>
                        <div class="clearfix wrapLeftRight">
                            <div class="leftSide"> 
                                <input class="phone" name="phone" type="text" value="" autocomplete="off" placeholder ="Ваш телефон"  pattern="[0-9]*" inputmode="numeric" onclick="focusPhone('#form_construct_order'); return false;">
                                <label class="clearfix">
                                    <input class="email" name="email" type="text" value="" autocomplete="off" placeholder ="Ваша почта">
                                    <div id="noteEmail" class="necessary">Не верный email формат</div>
                                </label>
                                <div class="conditionCheck">
                                    <input id="acceptionConstruct" type="checkbox" name="conditions" checked="checked">
                                    <label class="checkConditions" for="acceptionConstruct"></label>
                                    <span class="textForCheckbox">
                                        <span>Я прочитал(а) и принимаю</span> <a href="/conditions" target="_blank">Условия использования</a>
                                    </span>
                                </div>
                            </div>
                                <div class="zonesBlock">
                                    <img src="{{ asset('img') }}/ZoneAll.png" width="285" height="160" alt="Карта России" title="Карта России">
                                    <input id="Zone1" class="" name="zone" type="radio" value="1">
                                    <label for="Zone1" class="Radio fstZone"></label>
                                    <input id="Zone2" class="" name="zone" type="radio" value="2">
                                    <label for="Zone2" class="Radio secondZone"></label>
                                    <input id="Zone3" class=""  name="zone" type="radio" value="3">
                                    <label for="Zone3" class="Radio thirdZone"></label>
                                    <div class="noteZone necessary">Укажите зону проживания</div>
                                </div>
                            </div>
                                <div id="alreadyAccounted">Есть аккаунт? <a href="/auth">Войти</a> </div>
                            <button type="button" class="button onlyNext next" onclick="checkStages(1); return false;">Далее</button>
                        </div>
                    <div  id="stage2" class="stageOfOrder">
                        <h5>Введите ваше ФИО</h5>
                        <p>
                            Эти данные нужны нам для отправки вашего заказа.
                        </p>
                            <div class="input_block clearfix">
                                <input name="surname" type="text" value="" spellcheck="false" autocomplete="off" placeholder="Фамилия">
                                <input name="name" type="text" value="" spellcheck="false" autocomplete="off" placeholder ="Имя">
                                <input name="patronymic" type="text" value="" spellcheck="false" autocomplete="off" placeholder="Отчество">
                            </div>
                            <button type="button" class="button prev" onclick="toPrev(1); return false;">Назад</button>
                            <button type="button" class="button next" onclick="checkStages(2); return false;">Далее</button>
                    </div>
                    <div  id="stage3" class="stageOfOrder">
                        <h5>Доставка</h5>
                        <p>
                            Пожалуйста, укажите адрес и способ доставки.
                        </p>
                            <div class="input_block clearfix">
                                <input name="locality" type="text" value="" class="first_line first_input" spellcheck="false" autocomplete="off" placeholder="Город, населенный пункт">  
                                <input name="street" type="text" value="" class="first_line" spellcheck="false" autocomplete="off" placeholder="Улица">
                                <input name="house" type="text" value="" class="first_line home" spellcheck="false" autocomplete="off" placeholder="Дом">
                                <input name="flat" type="text" value="" class="flat notNecessary" spellcheck="false" autocomplete="off" placeholder="Квартира">
                                <input name="region" type="text" value="" class="first_line first_input" spellcheck="false" autocomplete="off" placeholder="Область">
                                <input name="mailIndex" type="text" value="" class="first_line" spellcheck="false" autocomplete="off" placeholder="Почтовый индекс"> 
                                <div class="typeSendBlock">
                                    @foreach ($typeOfSends as $typeOfSend)
                                        <div class="typeSend">
                                            <a href="#" class="info_link" onclick="showDescriptionSend({{$typeOfSend->id}});">!</a>
                                            <label class="preventLabel">
                                                <span class="typeSendsName unselected">{{$typeOfSend->name}}({{$typeOfSend->price}}) р.</span>
                                                <input id="typeSend{{$typeOfSend->id}}" name="typeSend" type="radio" value="{{$typeOfSend->id}}">
                                                <label for="typeSend{{$typeOfSend->id}}" class="Radio helpClass"></label>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" class="button prev" onclick="toPrev(2);return false;">Назад</button>
                            <button type="button" class="button next" onclick="checkStages(3); return false;">Далее</button>
                    </div>
                    <div id="stage4" class="stageOfOrder">
                        <h5>Оплата</h5>
                        <p>
                            <span class="constructorOrderText">
                                <span id="aboutPay">Сумма заказа > {{WITHOUT_PAY}} р. Вам нужно будет внести {{PERSENT}}% от суммы закза</span>
                            </span>
                            <span class="cartOrderText">
                                После согласования заказа, вам сообщат реквизиты нашего сервиса.
                            </span>
                            @if (empty(Session::get('userId')))
                                <div class="captchaBlockConstruct">
                                    <div class="forCaptchaBlock">
                                        @captcha
                                        <svg enable-background="new 0 0 32 32" height="32px" id="Refresh2" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path class="refreshPath" d="M25.032,26.16c2.884-2.883,4.184-6.74,3.928-10.51c-1.511,0.013-3.021,0.021-4.531,0.034  c0.254,2.599-0.603,5.287-2.594,7.277c-3.535,3.533-9.263,3.533-12.796,0c-3.534-3.533-3.534-9.26,0-12.794  c3.015-3.016,7.625-3.446,11.109-1.314c-1.181,1.167-2.57,2.549-2.57,2.549c-1,1.062,0.016,1.766,0.69,1.77h8.828  c0.338,0,0.611-0.274,0.612-0.612V3.804c0.041-0.825-0.865-1.591-1.756-0.7c0,0-1.495,1.48-2.533,2.509  C18.112,1.736,10.634,2.175,5.841,6.967c-5.3,5.3-5.3,13.892,0,19.193C11.141,31.459,19.733,31.459,25.032,26.16z" fill="#555555" /></svg>
                                    </div>
                                    <div class="wrapNecessaryCaptcha">
                                        <input type="text" name="captcha" placeholder="Капча" autocomplete="off">
                                        <div class="necessary" id="captchaConstrucNecessary">Не верные символы</div>
                                    </div>
                                </div>
                            @endif
                            <div id="sendTypeAuth" class="typeSendBlock">
                                <h5 class="h5Second">Доставка</h5>
                                    @foreach ($typeOfSends as $typeOfSend)
                                        <div class="typeSend">
                                            <a href="#" class="info_link" onclick="showDescriptionSend({{$typeOfSend->id}});">!</a>
                                            <label class="preventLabel">
                                                <span class="typeSendsName unselected">{{$typeOfSend->name}}({{$typeOfSend->price}}) р.</span>
                                                <input id="typeSendAuth{{$typeOfSend->id}}" name="typeSend" type="radio" value="{{$typeOfSend->id}}">
                                                <label for="typeSendAuth{{$typeOfSend->id}}" class="Radio helpClass"></label>
                                            </label>
                                        </div>
                                    @endforeach
                            </div>

                            <!--<span class="constructorOrderText">
                                Пожалуйста, выберите тип оплаты.
                            </span>
                            <span class="cartOrderText">
                           Пожалуйста, выберите тип оплаты.</span>
                        </p>
                            <div id="payReady" class="wrapForAboutPay pay_for_construct" style="position: relative;">
                                <label class="clearfix button" >
                                    <span>Оплатить по готовности</span>
                                       <input type="radio" name="type_of_payment" value="1">
                                </label>
                                <a href="#" class="info_link info_pay_link" onclick="showDescriptionPay(1);">!</a>
                            </div>
                            <div id="payPersent" class="wrapForAboutPay pay_for_construct" style="position: relative;">
                                <label class="clearfix button ">
                                    <span>Внести залог в  {{PERSENT}}%</span>
                                       <input type="radio" name="type_of_payment" value="4">
                                </label>
                                <a href="#" class="info_link info_pay_link" onclick="showDescriptionPay(4);">!</a>
                            </div>
                            <div id="payNow" class="wrapForAboutPay pay_for_construct" style="position: relative;">
                                <label class="clearfix button">
                                    <span>Оплатить полностью</span>
                                       <input type="radio" name="type_of_payment" value="3">
                                </label>
                                <a href="#" class="info_link info_pay_link" onclick="showDescriptionPay(3);">!</a>
                            </div>
                            <div class="wrapForAboutPay pay_for_cart" style="position: relative;">
                                <label class="clearfix button">
                                    <span>Оплатить cейчас</span>
                                       <input type="radio" name="type_of_payment" value="3">
                                </label>
                            </div>-->
                        @if (empty(Session::get('userId')))
                            <button type="button" class="button prev" onclick="toPrev(3); return false;">Назад</button>
                        @endif
                        <button id="sendButton" name="send" type="button" class="button 
                        @if (empty(Session::get('userId'))) 
                            next 
                        @else
                            onlyNext
                        @endif" onclick="checkStages(4); return false;">Отправить</button>
                    </div>
                    <div id="stage5" class="stageOfOrder">
                        <h5 class="successOrder">Готово!</h5> <h5 class="phoneError">Пользователь уже зарегистрирован!</h5>
                        <p class="successOrder">Готово! Наш оператор свяжется с вами в ближайшее время. @if(!Session::has('userId'))
                        Теперь осталось получить доступ к личному кабинету, где вы сможете отслеживать свои заказы и вести диалог со своим кузнецом.<br><span class="wrapPreLogin">Ваш логин: <span id="preLogin"></span></span>@endif  @if(Session::has('userId'))<a href="" id="toOrderLink" style="color:black;">К заказу</a>@endif</p><p class="phoneError">Пользователь с таким телефоном уже зарегистрирован, пожалуйста войдите в свой аккаунт и повторите заказ.</p>
                        <a href="/auth" class="phoneError" style="color:black;">Войти</a>
                        @if(!Session::has('userId'))
                            <div class="passwordBlockWrap">
                                <div class="input_block clearfix successOrder">
                                    <input name="password" type="password" value="" spellcheck="false" autocomplete="off" placeholder="Введите Пароль">    
                                    <input name="passwordCheck" type="password" value="" spellcheck="false" autocomplete="off" placeholder="Повторите пароль">
                                </div>
                                <div id="validation"></div>
                            </div>
                        @endif

                        <button type="button" class="button onlyNext" onclick=" @if(!Session::has('userId')) confirmationAddPassword(); @else closeConstructOrder(); @endif return false;">принять</button>
                    </div>
                    <div class="footer" id="footerOrderPhone">
                        <div class="container">
                            <div class="bond_footer_block clearfix">
                                <a href="https://vk.com/rusvyatich" class="vkontakte" target="_blank">Вконтакте</a>
                                <a href="https://www.instagram.com/rusvyatich/" class="instagram" target="_blank">Инстаграм</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id='confirmationPassword' class="success">
        <div class="alert alertPasswordAdd">
                <span class="captionAlert"><p class="noteAboutReset successOrder"> Вы сможете задать пароль потом с помощью <a href="/auth/resetPwd" target="_blank">восстановления пароля.</a></p>
                </span>
                
                <button class="button leftButton" onclick="addPassword(); return false;">Потом</button>
                <button class="button rightButton" onclick="rejectAddPassword(); return false">Сейчас</button>
        </div>
    </div>
    <div id='confirmationOut' class="success">
        <div class="alert alertDaNet">
                <span class="captionAlert">Выйти?
                </span>
                <button class="button leftButton" onclick="outUser(); return false;">Да</button>
                <button class="button rightButton" onclick="rejectOut(); return false">Нет</button>
        </div>
    </div>
    <div id='confirmationCloseConstruct' class="success">
        <div class="alert alertDaNet">
                <span class="captionAlert">Введенные данные не будут сохранены. Выйти из заказа?
                </span>
                <button class="button leftButton" onclick="closeConstructOrder(); return false;">Да</button>
                <button class="button rightButton" onclick="refuseCloseConstructOrder(); return false">Нет</button>
        </div>
    </div>
    <div id="aboutPartWrap">
        <div id="aboutPart" class="aboutPart">
            <div class="close">
                <button id="about_close" class="window_close" onclick="closeAboutPart();">Закрыть</button>
            </div>
            <div id="aboutPartScrollable">
                <span id="captionAbout"></span>
                <p id="textAbout" class="textAbout"></p>
                <img id="imageAbout">
            </div>
        </div>
    </div>