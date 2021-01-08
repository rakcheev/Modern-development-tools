<div id="wrap_opros">
    <div id="way_opros">
            <div class="close_order_construct">
                <button id="form_consult_close" class="window_close" onclick="closeOpros(); return false;">Закрыть</button>
            </div>
            <form id="opros" method="POST" class="form_construct_order clearfix">
                <div id="stageOpros1" class="stageOfOrder">
                    <h5>Кто вы?</h5>
                    <div class="typeSend">   
                        <label>
                            <input id="fstQuest1" type="radio" name="fstQuest" value="1">
                            <label class="Radio" for="fstQuest1"></label>
                            <span>Рыболов</span>
                        </label>
                    </div>
                    <div class="typeSend">   
                        <label>
                            <input id="fstQuest2" type="radio" name="fstQuest" value="2">
                            <label class="Radio" for="fstQuest2"></label>
                            <span>Охотник</span>
                        </label>
                    </div>
                    <div class="typeSend">   
                        <label>
                            <input id="fstQuest3" type="radio" name="fstQuest" value="3">
                            <label class="Radio" for="fstQuest3"></label>
                            <span>Турист</span>
                        </label>
                    </div>
                    <div class="typeSend">   
                        <label>
                            <input id="fstQuest4" type="radio" name="fstQuest" value="4">
                            <label class="Radio" for="fstQuest4"></label>
                            <span>Другое</span>
                        </label>
                    </div>
                    <button type="button" class="button onlyNext next" onclick="checkOpros(1); return false;">Далее</button>
                </div>
                <div id="stageOpros2" class="stageOfOrder">
                    <h5>Для чего вам нож?</h5>
                    <div class="typeSend">   
                        <label>
                            <input id="scndQuest1" type="radio" name="scndQuest" value="1">
                            <label class="Radio" for="scndQuest1"></label>
                            <span>Для снятия шкур</span>
                        </label>
                    </div>
                    <div class="typeSend">   
                        <label>
                            <input id="scndQuest2" type="radio" name="scndQuest" value="2">
                            <label class="Radio" for="scndQuest2"></label>
                            <span>Для лагерных работ</span>
                        </label>
                    </div>
                    <div class="typeSend">   
                        <label>
                            <input id="scndQuest3" type="radio" name="scndQuest" value="3">
                            <label class="Radio" for="scndQuest3"></label>
                            <span>Для каждодневного использования (EDC)</span>
                        </label>
                    </div>
                    <div class="typeSend">   
                        <label>
                            <input id="scndQuest4" type="radio" name="scndQuest" value="4">
                            <label class="Radio" for="scndQuest4"></label>
                            <span>Для коллекции</span>
                        </label>
                    </div>
                    <button type="button" class="button onlyNext next" onclick="checkOpros(2); return false;">Далее</button>
                </div>
                <div id="stageOpros3" class="stageOfOrder">
                    <h5>Что для вас важнее в клинке?</h5>
                    <div class="typeSend">   
                        <label>
                            <input id="thrdQuest1" type="radio" name="thrdQuest" value="1">
                            <label class="Radio" for="thrdQuest1"></label>
                            <span>Чтобы долго держал заточку</span>
                        </label>
                    </div>
                    <div class="typeSend">
                        <label>
                            <input id="thrdQuest2" type="radio" name="thrdQuest" value="2">
                            <label class="Radio" for="thrdQuest2"></label>
                            <span>Чтобы легко точился в походных условиях</span>
                        </label>
                    </div>
                    <button type="button" class="button onlyNext next" onclick="checkOpros(3); return false;">Далее</button>
                </div>
                <div id="stageOpros4" class="stageOfOrder">
                    <h5>Важна ли вам коррозийная стойкость ножа?</h5>
                    <div class="typeSend">   
                        <label>
                            <input id="fourthQuest1" type="radio" name="fourthQuest" value="1">
                            <label class="Radio" for="fourthQuest1"></label>
                            <span>Да</span>
                        </label>
                    </div>
                    <div class="typeSend">
                        <label>
                            <input id="fourthQuest2" type="radio" name="fourthQuest" value="2">
                            <label class="Radio" for="fourthQuest2"></label>
                            <span>Нет</span>
                        </label>
                    </div>
                    <button type="button" class="button onlyNext next" onclick="checkOpros(4); return false;">Готово</button>
                </div>
            </form>
    </div>
</div>
<div id="formConsult">
        <div class="close_order_construct">
            <button id="consult_close" class="window_hide" onclick="formConsultHide(); return false;">Закрыть</button>
        </div>
    <form>
        <p>Оставь заявку на собранный нож и получи дополнительную скидку в 5%</p>
        <span id="sumNewConsult"></span>
        <span id="sumNewNewConsult"></span>
        <input name="name" type="text" value="" spellcheck="false" autocomplete="off" placeholder ="Ваше имя"> 
        <input class="phone" name="phone" type="text" value="" autocomplete="off" placeholder ="Ваш телефон"  pattern="[0-9]*" inputmode="numeric" onclick="focusPhone('#formConsult'); return false;">
        <button class="button consultButton" onclick="sendConsultConstruct(); return false;" type="button">отправить</button>

    </form>
</div>
<div id="consultSuccess" class="form_construct_order">
    <div id="consultSuccessInner">
        <p>Готово! Наш оператор свяжется с вами в ближайшее время.</p>
        <button class="button onlyNext next" onclick="consultSuccessClose(); return false;">принять</button>
    </div>
</div>