
    <div class="to_cart" id="to_cart" onclick="tmplKnife(); return false;">
        <span class="Runic RunicCart">В</span>
        <svg id="svg_cart" xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 75 125">
            <g id="svg_cart_knife_1" transform="translate(-5 110) rotate(-65)">
                <path  vector-effect="non-scaling-stroke" fill="#787878" d="M0,10 Q4.2,15 18,20 L64,20 L64,8 L38,8z"/>
                <path  vector-effect="non-scaling-stroke" fill="#40310b" d="M64,8 L113,8 Q123,12 117,24 Q113,18 107,18 L105,19 L64,19z"/>
                <path  vector-effect="non-scaling-stroke" d="M64,8 L65,8 L65,20 L64,20z"/>
            </g>
            <g id="svg_cart_knife_4" transform="translate(35 -58) rotate(-55)">
                <path  vector-effect="non-scaling-stroke" fill="#787878" d="M0,10 Q4.2,15 18,20 L64,20 L64,8 L38,8z"/>
                <path  vector-effect="non-scaling-stroke" fill="#1a1313" d="M64,8 L113,8 Q123,12 117,20 Q113,18 107,18 L105,19 L64,19z" transform="translate(1 0)"/>
                <path  vector-effect="non-scaling-stroke" d="M64,0 L66,8 L66,20 L64,28z" transform="translate(0 -1)"/>
            </g>
            <g id="svg_cart_knife_2" transform="translate(50 -58) rotate(-60)">
                <path  vector-effect="non-scaling-stroke" fill="#787878" d="M0,10 Q4.2,15 18,20 L64,20 L64,8 L38,8z"/>
                <path  vector-effect="non-scaling-stroke" fill="#211b03" d="M64,8 L113,8 Q123,12 117,20 Q113,18 107,18 L105,19 L64,19z" transform="translate(1 0)"/>
                <path  vector-effect="non-scaling-stroke" d="M64,0 L66,8 L66,20 L64,28z" transform="translate(0 -1)"/>
            </g>
            <g id="svg_cart_knife_3" transform="translate(60 -58) rotate(-55)">
                <path  vector-effect="non-scaling-stroke" fill="#787878" d="M0,10 Q4.2,15 18,20 L64,20 L64,8 L38,8z"/>
                <path  vector-effect="non-scaling-stroke" fill="#5C4141" d="M64,8 L113,8 Q123,12 117,20 Q113,18 107,18 L105,19 L64,19z" transform="translate(1 0)"/>
                <path  vector-effect="non-scaling-stroke" d="M64,0 L66,8 L66,20 L64,28z" transform="translate(0 -1)"/>
            </g>
            <g id="svg_cart_knife_5" transform="translate(40 -58) rotate(-55)">
                <path  vector-effect="non-scaling-stroke" fill="#787878" d="M0,10 Q4.2,15 18,20 L64,20 L64,8 L38,8z"/>
                <path  vector-effect="non-scaling-stroke" fill="#c7a3a3" d="M64,8 L113,8 Q123,12 117,20 Q113,18 107,18 L105,19 L64,19z" transform="translate(1 0)"/>
                <path  vector-effect="non-scaling-stroke" d="M64,0 L66,8 L66,20 L64,28z" transform="translate(0 -1)"/>
            </g>
            <polygon points="0,48 0,120 50,120 60,48" fill="#C1B3B3" id="svg_cart" transform="translate(-15 15)"/>
        </svg>
        <span id="basket-cost">{ $sum }<span class="rub">р.</span></span>
    </div>
    <div id="wrap_cart">
        <div id="cart">
            <div class="phoneLineClose">
                <span>Корзина</span>
            </div>
            <div class="close_cart">
                <button id="cart_close" class="window_close" onclick="closeCart(); return false;">Закрыть</button>
            </div>
            <h3>Ваша корзина</h3>
            <div class="wrap_slider">
                <ul id="cart_slider" class="cart_slider">
                <script id="cartTemplate" type="text/x-jquery-tmpl">
                    <li class="cartElement clearfix" @{{if type == 'serial'}} id="in_cart_serial_${id}" @{{else}} id="in_cart_${id}" @{{/if}}>
                        <a @{{if type == 'serial'}} href="/shop/serialKnife${id}" @{{else}} href="/shop/knife${id}" @{{/if}} target="_blank" style="display: block; width: calc(100% - 44px); height: 100%;">
                            <img src="{{ asset('img/imgStorage') }}/${image}?{{VERSION}}" width="143px" height="80px">
                            <span href="#" class="thumbdescription">${name} <span class="steelPartOfName">(${steel})</span></span>
                            <span class="productCartCount">@{{if type == 'serial'}}${count}@{{else}}1@{{/if}} шт.</span>
                            <span class="costCartElement">${price} р.</span>
                        </a>
                        <div class="remove_from_cart">
                            <button class="window_close removeFromCartButton" 
                            @{{if type == 'serial'}} 
                                onclick="removeFromCartSerial(${id}, event); return false;"  
                            @{{else}} 
                                onclick="removeFromCart(${id}, event); return false;" 
                            @{{/if}}>
                            удалить
                        </div>
                    </li>
                </script>
                </ul>
            </div>
            <div class="cart_res">
                <span id="pkSum">Общая стоимость заказа:</span><span id="phoneSum" style="display: none;">Cумма:</span> <span id="sum_inner_cart"></span>
            </div>
            <!--<button id="cleanCart" type="button" class="button" onclick="cleanCart(); return false;">Очистить корзину</button>-->
            <button name="send" type="button" class="button order_cart_button" onclick="
                @if (empty(Session::get('userId')))  
                    orderShow(2); return false;
                @else
                    orderShow(4); return false;
                @endif
            ">оформить</button>  
        </div>
    </div>