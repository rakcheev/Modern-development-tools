            <div class="customerOrderBlock">
                <div class="aboutCustomer" onclick="window.location.href='/home/user{{ $order->id_user }}'">
    	    			<span>{{ $order->name }}</span>
    	    	  		<span>{{ $order->surname }}</span>	    		
    	    	  		<span>{{ $order->patronymic }}</span>
                        @if ($order->raznOnline) <div id="onlineDetector"></div> @endif
    	    	</div>
                <div id="scrollPieceCustomer">
    	    		<div class="aboutOrder">
                        <dl class="dl-inline clearfix timeInline">
                            <dt class="dt-dotted">
                                <span>Активность</span>
                            </dt>
                            <dd >@if($order->account_status_id == DELETED) нет @else да @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix phoneInline">
                            <dt class="dt-dotted">
                                <span>Телефон</span>
                            </dt>
                            <dd >{{ $order->phone }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix emailInline">
                            <dt class="dt-dotted">
                                <span>email</span>
                            </dt>
                            <dd >{{ $order->email }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix timeInline">
                            <dt class="dt-dotted">
                                <span>Местное время</span>
                            </dt>
                            <dd >от  @if( $order->fst_hours > 0) + @endif{{ $order->fst_hours}} до @if( $order->scnd_hours > 0) + @endif {{ $order->scnd_hours}}</dd>
                        </dl>
                        <dl class="dl-inline clearfix alertInline">
                            <dt class="dt-dotted">
                                <span>Уведомления</span>
                            </dt>
                            <dd > @if( $order->sms_alert_id === SEND_SMS) да @endif @if( $order->sms_alert_id === NO_SMS) нет @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix alertInline">
                            <dt class="dt-dotted">
                                <span>Тип оплаты</span>
                            </dt>
                            <dd> {{$order->namePayment}}</dd>
                        </dl>
                        <dl class="dl-inline clearfix alertInline">
                            <dt class="dt-dotted">
                                <span>Оплаченность</span>
                            </dt>
                            <dd > @if($order->id_payed == PAYED) да @else нет @endif</dd>
                        </dl>
                        <dl class="dl-inline clearfix alertInline">
                            <dt class="dt-dotted">
                                <span>Внесено</span>
                            </dt>
                            <dd><input class="inputInBlock" name="pay" value="{{ $order->money_payed }}" onblur="confirmationPay();" onkeydown="enterPay(event)" onfocus="setPayPrev();"></dd>
                        </dl>
                        <dl class="dl-inline clearfix alertInline">
                            <dt class="dt-dotted">
                                <span>Дней</span>
                            </dt>
                            <dd><input class="inputInBlock" name="daysOfOrder" value="{{ $order->days_for_order }}" onblur="confirmationDay();" onkeydown="enterDay(event)" onfocus="setDayPrev();"></dd>
                        </dl>
                        <dl class="dl-inline clearfix statusInline">
                            <dt class="dt-dotted">
                                <span>Статус</span>
                            </dt>
                            <dd >
                                <div class="new-select-style-wpandyou">
                                    <select name="statusChanger" size="bar" style="text-align: right;" class="statusSelect" onchange="confirmation();" onfocus="setStatusPrev();">
                                            @foreach ($statusesOrder as $status)
                                                <option value="{{ $status->id }}" 
                                                    @if ($order->id_status === $status->id)
                                                        selected 
                                                    @endif
                                                >    
                                                {{ $status->name }}
                                                </option>           
                                            @endforeach   
                                    </select>
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix sendInline">
                            <dt class="dt-dotted">
                                <span>Доставка</span>
                            </dt>
                            <dd >
                                <div class="new-select-style-wpandyou">
                                    <select name="typeOfSend" size="bar" style="text-align: right;" class="statusSelect" onchange="confirmationSend();" onfocus="setSendPrev();">
                                            @foreach ($typeOfSends as $typeOfSend)
                                                <option value="{{ $typeOfSend->id }}" 
                                                    @if ($order->id_type_send === $typeOfSend->id)
                                                        selected
                                                    @endif
                                                >
                                                {{ $typeOfSend->name }}
                                                </option>           
                                            @endforeach   
                                    </select>
                                </div>
                            </dd>
                        </dl>
                        <dl class="dl-inline clearfix sumInline">
                            <dt class="dt-dotted">
                                <span>Сумма заказа</span>
                            </dt>
                            <dd ><input class="inputInBlock" name="sumOrder" value="{{ $order->sum_of_order }}" onblur="confirmationSum();" onkeydown="enterSum(event)" onfocus="setSumPrev();"></dd>
                        </dl>
                    </div>
                    @if(!empty($masters))
                    <div class="sectionCaption">
                        <span>Выполняет</span>
                    </div>
                    <div class="madeBy">
                        <dl class="dl-inline clearfix masterInline">
                            <dt class="dt-dotted">
                                <span>Мастер</span>
                            </dt>
                            <dd >
                                <div class="new-select-style-wpandyou">
                                    <select name="masterChanger" size="bar" style="text-align: right;" class="statusSelect" onchange="confirmationMaster();" onfocus="setMasterPrev();">
                                        <option value="0">—</option>
                                        @foreach ($masters as $master)
                                            <option value="{{ $master->id }}" 
                                                @if ($master->id === $order->id_master)
                                                    selected 
                                                @endif
                                            >    
                                            {{ $master->name }} {{ $master->surname }}
                                            </option>           
                                        @endforeach      
                                    </select>
                                </div>
                            </dd>
                        </dl>
                    </div>
                    @endif
                    <div class="sectionCaption">
                        <span>Адрес</span>
                    </div>
    	    		<div class="adressCustomer">
                        <dl class="dl-inline clearfix regionInline">
                            <dt class="dt-dotted">
                                <span>Регион</span>
                            </dt>
                            <dd >{{ $order->region }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix localityInline">
                            <dt class="dt-dotted">
                                <span>Населенный пункт</span>
                            </dt>
                            <dd id="length__{$item['id']}">{{ $order->locality }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix streetInline">
                            <dt class="dt-dotted">
                                <span>Улица</span>
                            </dt>
                            <dd id="width__{$item['id']}">{{ $order->street }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix homeInline">
                            <dt class="dt-dotted">
                                <span>Дом</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">{{ $order->house }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix flatInline">
                            <dt class="dt-dotted">
                                <span>Квартира</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">{{ $order->flat }}</dd>
                        </dl>
                        <dl class="dl-inline clearfix indexInline">
                            <dt class="dt-dotted">
                                <span>Почтовый индекс</span>
                            </dt>
                            <dd id="thickness__{$item['id']}">{{ $order->mail_index }}</dd>
                        </dl>
    	    		</div>
                    <div class="sectionCaption">
                        <span>Заметка</span>
                    </div>
                    <textarea id="purposeTextarea">{{$order->purpose}}</textarea>
                    <span id="changePurposeButton" class="button" onclick="savePurpose({{ $order->id }}, {{ $typeOrder }})">сохранить</span>
                    <a href="#" onclick="confirmationDropUserByOperator(); return false">Удалить аккаунт</a>
                        
                </div>
            </div>
            <div id='confirmation' class="wrapAlert">
                <div class="alert statusAlert">
                    <div class="boxAlert">
                        <span class="captionAlert">Сменить статус с 
                            <span id="prevStatus">
                                @foreach ($statusesOrder as $status)
                                    @if ($order->id_status === $status->id)
                                        {{ $status->name }}
                                    @endif           
                                @endforeach
                            </span>  на 
                            <span id="newStatus"></span>    
                        </span>
                        <button class="button leftButton" onclick="confirm({{ $order->id }}, {{ $typeOrder }})">Да</button>
                        <button class="button rightButton" onclick="reject(
                            @foreach ($statusesOrder as $status)
                                @if ($order->id_status === $status->id)
                                    {{ $status->id }}
                                @endif
                            @endforeach      
                        )">Нет</button>
                    </div>
                </div>
            </div>
            <div id='confirmationSend' class="wrapAlert">
                <div class="alert statusAlert">
                    <div class="boxAlert">
                        <span class="captionAlert">Сменить 
                            <span id="prevSend">
                                @foreach ($typeOfSends as $typeOfSend)
                                    @if ($order->id_type_send === $typeOfSend->id)
                                        {{ $typeOfSend->id }}
                                    @endif
                                @endforeach  
                            </span>  на 
                            <span id="newSend"></span>    
                        </span>
                        <button class="button leftButton" onclick="confirmSend({{ $order->id }}, {{ $typeOrder }})">Да</button>
                        <button class="button rightButton" onclick="rejectSend(
                            @foreach ($typeOfSends as $typeOfSend)
                                @if ($order->id_type_send === $typeOfSend->id)
                                    {{ $typeOfSend->id }}
                                @endif
                            @endforeach      
                        )">Нет</button>
                    </div>
                </div>
            </div>
            @if(!empty($masters))
            <div id='confirmationMaster' class="wrapAlert">
                <div class="alert masterAlert">
                    <div class="boxAlert">
                        <span class="captionAlert">Cменить мастера c 
                            <span id="prevMaster">
                                @foreach ($masters as $master)
                                        @if ($master->id === $order->id_master)
                                            {{ $master->name }} {{ $master->surname }}
                                        @endif
                                @endforeach      
                            </span>
                                на 
                                <span id="newMaster"></span>
                            </span>
                        </span>
                        <button id="confirmMaster" class="button leftButton" onclick="confirmMaster({{ $order->id }}, {{ $typeOrder }})">Да</button>
                        <button class="button rightButton" onclick="rejectMaster()">Нет</button>
                    </div>
                </div>
            </div>
            @endif
            <div id='confirmationSum' class="wrapAlert">
                <div class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Cменить cумму заказа с 
                            <span id="prevSum"></span> 
                            на 
                            <span id="newSum"></span>
                        </span>
                        <button id="confirmSum" class="button leftButton" onclick="confirmSum({{ $order->id }}, {{ $typeOrder }})">Да</button>
                        <button class="button rightButton" onclick="rejectSum()">Нет</button>
                    </div>
                </div>
            </div>
            <div id='confirmationDay' class="wrapAlert">
                <div class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Дни на заказ с 
                            <span id="prevDay"></span> 
                            на 
                            <span id="newDay"></span>
                        </span>
                        <button id="confirmDay" class="button leftButton" onclick="confirmDay({{ $order->id }}, {{ $typeOrder }})">Да</button>
                        <button class="button rightButton" onclick="rejectDay()">Нет</button>
                    </div>
                </div>
            </div>
            <div id='confirmationPay' class="wrapAlert">
                <div class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Сменить внесено с 
                            <span id="prevPay"></span> 
                            на 
                            <span id="newPay"></span>
                        </span>
                        <button id="confirmPay" class="button leftButton" onclick="confirmPay({{ $order->id }}, {{ $typeOrder }})">Да</button>
                        <button class="button rightButton" onclick="rejectPay()">Нет</button>
                    </div>
                </div>
            </div>
            <div id='confirmationDropUserByOperator' class="wrapAlert">
                <div class="alert">
                    <div class="boxAlert">
                        <span class="captionAlert">Деактивировать аккаунт.
                        </span>
                        <button id="confirmDropByOperator" class="button leftButton" onclick="dropUserByOperator({{$order->id_user}}); return false">Да</button>
                        <button class="button rightButton" onclick="rejectDropUserByOperator(); return false">Нет</button>
                    </div>
                </div>
            </div>