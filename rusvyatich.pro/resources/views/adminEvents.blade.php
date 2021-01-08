            <div class="events">
                @if (Cookie::get('dialogWindow') === null && Session::get('status') === CUSTOMER)
                    <div class="success successShowed">
                        <div class="canDoCustomer">
                            <p>В данном диалоге, вы можете отслеживать статус заказа и задавать любые вопросы оператору и вашему мастеру.</p> 
                                <label>
                                <dl class="dl-inline clearfix">
                                    <dt class="dt-dotted">
                                        <span style="margin-left: 0;">Больше не показывать это окно</span>
                                    </dt>
                                    <dd>
                                        <input id="showOrNot" type='checkbox' name="notShow">
                                    <label for="showOrNot" class="check"></label>
                                    </dd>
                                </dl>
                                </label>
                            
                            <button class="button" id="skipNote" onclick="closeAboutDialog();">Далее</button>
                        </div>
                    </div>
                @endif
                <div class="caption clearfix" id="eventsCaption">
                    <span id="spanEvents">События</span>
                    @if(Session::get('status') !== CUSTOMER) <span id="online"></span> @endif 
                    @if(Session::get('status') === CUSTOMER) <span id="weAreThere">@if($timeWork)Пишите, мы онлайн.@else Ответим с {{START_WORK_DAYSHIFT}} до {{END_WORK_DAYSHIFT}} по МСК @endif</span>@endif
                    @if($order->id_status !== REFUSED && Session::get('status') !== ADMIN && Session::get('status') !== MASTER && Session::get('status') !== MAIN_MASTER)
                        <button id="refuseButton" class="button button2" onclick="confirmationRefuse(); return false">отменить заказ</button>
                        @if(Session::get('status') == CUSTOMER && $order->id_payed == NOT_PAYED && !empty($order->sum_of_order))    
                            <!--<button onclick="window.location.href=window.location.toString()+ '/pay'" id="payButton" class="button button2" type="submit">Оплатить</button>-->
                        @endif
                    @endif
                </div>
                <div id="lettersScroll" class="lettersScroll">
                    <div id="letters" class="clearfix">
                        <div class="marginFix"></div>
                        <script id="messageLineTemplate" type="text/x-jquery-tmpl">
                            <div class="messageLine">

                                @{{if unreaded == 1}}
                                    <span class="newMessageEvent" id="newMessage">Новые сообщения</span>
                                @{{/if}}
                                @{{if DateCreate !== 0}}
                                    <span class="dateMessage">${DateCreate}</span>
                                @{{/if}}
                                @{{if id_message_type == 1 && id_sender !=0 }}
                                <span class="sender">${name} ${senderName}
                                    @{{if TimeCreate !== 0 }}
                                        <span class="timeMessage">
                                            ${TimeCreate}
                                        </span> 
                                    @{{/if}}
                                </span>
                                @{{/if}}
                                <span class="letterInLine 
                                @{{if id_message_type != 1}}statusMessage@{{/if}}">@{{if id_message_type != 1}}<span class="timeMessage">${TimeCreate}</span>@{{/if}} ${message}</span>
                                @{{if attach_image}}<img class="messageImage" style="display: block; border: 1px solid #555555;" src="{{ asset('messageImages') }}/${attach_image}" onclick='showMessageImg(this);' width="120px">@{{/if}}
                            </div>
                        </script>
                    </div>
                </div>
                <div class="messageBlock">
                    <form id="form_letter" method="POST" enctype="multipart/form-data" class="clearfix">
                        <label class="file_upload">
                            <span class="attachImage">NF</span>
                            <input id="file" type="file" name="file" accept="image/*">
                        </label>
                        <div class="row">
                            <div id="output">
                                <div class="close">
                                    <div id="image_close" class="window_close" onclick="hidePreview(); return false">Закрыть</div>
                                </div>
                            </div>
                        </div>
                        <div contenteditable="true" id="message" class="letter" placeholder="Cообщение"></div>
                        <button id="letterButton" class ="sendLetter button button2" name="send" type="button" onclick="sendLetter({{ $order->id}}, {{$typeOrder }}); return false;">отправить</button>
                    </form> 
                </div>
            </div>
            <div id='confirmationRefuse' class="wrapAlert">
                <div class="alert">  
                    <div class="boxAlert">
                        <span class="captionAlert">Отменить заказ?</span>
                        <button class="button leftButton" onclick="refuseOrder({{ $order->id}}, {{$typeOrder }}); return false"">Да</button>
                        <button class="button rightButton" onclick="rejectRefuse()">Нет</button>
                    </div>
                </div>
            </div>
            <div id="viewMessageImg">
                <img id="window" src="">
                <button id="close_message_img" class="window_close" type="button" title="Закрыть"onclick="closeMessageImg(); return false">
            </div>