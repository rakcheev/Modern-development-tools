@extends('layouts.site')

@section('content')
    <body class="album">
        @include('layouts.brandHead')
        <main class=""> 
	        <div id="wrap_for_product">
	            <img id="window" src="/./.:0" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
	            <button id="close_main_img" class="window_close" type="button" title="Закрыть"onclick="closeMainImg();"></button>
	        </div>
            <div class="container">
				<div class="prePage">
					<ul class="breadcrumb">
						<li><a href="/">Главная</a></li>
						<li><a href="#">Альбом работ</a></li>
					</ul>
				</div>
            
	            <section id="our_works"  class="content clearfix">
	                <h3>Примеры ножей скованых по конструктору</h3>
	                <section class="exampleForge clearfix">
						<span class="nameConstructKnife">Изогнутый</span>
	                    <div  class="clearfix">
		                    <img class="constructExample" src="{{ asset('img/construct7.jpg') }}?{{VERSION}}" width="550" height="330" alt="Эскиз ножа" title="Эскиз ножа">
		                    <section class="visualMakingBox clearfix">
		                        <svg class="firstArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                        <div class="forgingIcon">
		                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
		                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
		                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
		                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

		                                </g>
		                            </svg>
		                        </div>
		                        <svg class="secondArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                    </section>
		                    <img class="exampleImage" data-bigSrc="{{ asset('img/forged7Big.jpg') }}?{{VERSION}}" src="{{ asset('img/forged7.jpg') }}?{{VERSION}}" width="550" height="330" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
		                    <div class='enlarge'></div>
		                </div>
                        <div class="product_description construct_example_description">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Сталь</span>
                                </dt>
                                <dd>дамаск (штемпельный)</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина клинка</span>
                                </dt>
                                <dd>85 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Ширина клинка</span>
                                </dt>
                                <dd>27 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Толщина обуха</span>
                                </dt>
                                <dd>4.5 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Крепление</span>
                                </dt>
                                <dd>фултанг</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина рукояти</span>
                                </dt>
                                <dd>85 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Рукоять</span>
                                </dt>
                                <dd>мореный дуб</dd>
                            </dl>
                        </div>
	                </section>
	                <section class="exampleForge clearfix">
						<span class="nameConstructKnife">Акула</span>
	                    <div  class="clearfix">
		                    <img class="constructExample" src="{{ asset('img/construct1.jpg') }}?{{VERSION}}" width="550" height="330" alt="Эскиз ножа" title="Эскиз ножа">
		                    <section class="visualMakingBox clearfix">
		                        <svg class="firstArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                        <div class="forgingIcon">
		                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
		                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
		                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
		                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

		                                </g>
		                            </svg>
		                        </div>
		                        <svg class="secondArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                    </section>
		                    <img class="exampleImage" data-bigSrc="{{ asset('img/forged1Big.jpg') }}?{{VERSION}}" src="{{ asset('img/forged1.jpg') }}?{{VERSION}}" width="550" height="330" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
		                    <div class='enlarge'></div>
	                    </div>
                        <div class="product_description construct_example_description">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Сталь</span>
                                </dt>
                                <dd>х12мф</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина клинка</span>
                                </dt>
                                <dd>155 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Ширина клинка</span>
                                </dt>
                                <dd>33 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Толщина обуха</span>
                                </dt>
                                <dd>3.5 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Больстер</span>
                                </dt>
                                <dd>латунь</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина рукояти</span>
                                </dt>
                                <dd>120 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Рукоять</span>
                                </dt>
                                <dd>мореный дуб</dd>
                            </dl>
                        </div>
	                </section>
	                <section class="exampleForge clearfix">
						<span class="nameConstructKnife">Лесник</span>
	                    <div  class="clearfix">
		                    <img class="constructExample" src="{{ asset('img/construct5.jpg') }}?{{VERSION}}" width="550" height="330" alt="Эскиз ножа" title="Эскиз ножа">
		                    <section class="visualMakingBox clearfix">
		                        <svg class="firstArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                        <div class="forgingIcon">
		                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
		                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
		                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
		                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

		                                </g>
		                            </svg>
		                        </div>
		                        <svg class="secondArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                    </section>
		                    <img class="exampleImage" data-bigSrc="{{ asset('img/forged5Big.jpg') }}?{{VERSION}}" src="{{ asset('img/forged5.jpg') }}?{{VERSION}}" width="550" height="330" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
		                    <div class='enlarge'></div>
		                </div>
                        <div class="product_description construct_example_description">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Сталь</span>
                                </dt>
                                <dd>60с2а</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина клинка</span>
                                </dt>
                                <dd>100 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Ширина клинка</span>
                                </dt>
                                <dd>25 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Толщина обуха</span>
                                </dt>
                                <dd>2.7 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Больстер</span>
                                </dt>
                                <dd>латунь</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина рукояти</span>
                                </dt>
                                <dd>115 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Рукоять</span>
                                </dt>
                                <dd>сувель, граб</dd>
                            </dl>
                        </div>
	                </section>
	                <section class="exampleForge clearfix">
						<span class="nameConstructKnife">Вятич</span>
	                    <div  class="clearfix">
		                    <img class="constructExample" src="{{ asset('img/construct6.jpg') }}?{{VERSION}}" width="550" height="330" alt="Эскиз ножа" title="Эскиз ножа">
		                    <section class="visualMakingBox clearfix">
		                        <svg class="firstArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                        <div class="forgingIcon">
		                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
		                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
		                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
		                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

		                                </g>
		                            </svg>
		                        </div>
		                        <svg class="secondArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                    </section>
		                    <img class="exampleImage" data-bigSrc="{{ asset('img/forged6Big.jpg') }}?{{VERSION}}" src="{{ asset('img/forged6.jpg') }}?{{VERSION}}" width="550" height="330" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
		                    <div class='enlarge'></div>
		                </div>
                        <div class="product_description construct_example_description">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Сталь</span>
                                </dt>
                                <dd>дамаск (штемпельный)</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина клинка</span>
                                </dt>
                                <dd>110 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Ширина клинка</span>
                                </dt>
                                <dd>33 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Толщина обуха</span>
                                </dt>
                                <dd>2.4 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Больстер</span>
                                </dt>
                                <dd>латунь</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина рукояти</span>
                                </dt>
                                <dd>115 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Рукоять</span>
                                </dt>
                                <dd>береста, орех</dd>
                            </dl>
                        </div>
	                </section>
	                <section class="exampleForge clearfix">
						<span class="nameConstructKnife">Финский</span>
	                    <div  class="clearfix">
		                    <img class="constructExample" src="{{ asset('img/construct8.jpg') }}?{{VERSION}}" width="550" height="330" alt="Эскиз ножа" title="Эскиз ножа">
		                    <section class="visualMakingBox clearfix">
		                        <svg class="firstArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                        <div class="forgingIcon">
		                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
		                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
		                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
		                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

		                                </g>
		                            </svg>
		                        </div>
		                        <svg class="secondArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                    </section>
		                    <img class="exampleImage" data-bigSrc="{{ asset('img/forged8Big.jpg') }}?{{VERSION}}" src="{{ asset('img/forged8.jpg') }}?{{VERSION}}" width="550" height="330" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
		                    <div class='enlarge'></div>
		                </div>
                        <div class="product_description construct_example_description">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Сталь</span>
                                </dt>
                                <dd>95х18</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина клинка</span>
                                </dt>
                                <dd>100 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Ширина клинка</span>
                                </dt>
                                <dd>25 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Толщина обуха</span>
                                </dt>
                                <dd>4 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Крепление</span>
                                </dt>
                                <dd>фултанг</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина рукояти</span>
                                </dt>
                                <dd>120 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Рукоять</span>
                                </dt>
                                <dd>дуб</dd>
                            </dl>
                        </div>
	                </section>
	                <section class="exampleForge clearfix">
						<span class="nameConstructKnife">Клык</span>
	                    <div  class="clearfix">
		                    <img class="constructExample" src="{{ asset('img/construct4.jpg') }}?{{VERSION}}" width="550" height="330" alt="Эскиз ножа" title="Эскиз ножа">
		                    <section class="visualMakingBox clearfix">
		                        <svg class="firstArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                        <div class="forgingIcon">
		                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
		                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
		                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
		                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

		                                </g>
		                            </svg>
		                        </div>
		                        <svg class="secondArrow" enable-background="new 0 0 48 48" version="1.1" viewBox="0 0 48 48" height="26px" stroke="#555555" stroke-width="1.5" fill="#555555" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><polygon points="11.8,45.7 10.4,44.3 30.8,24 10.4,3.7 11.8,2.3 33.5,24  "/></g></svg>
		                    </section>
		                    <img class="exampleImage" data-bigSrc="{{ asset('img/forged4Big.jpg') }}?{{VERSION}}" src="{{ asset('img/forged4.jpg') }}?{{VERSION}}" width="550" height="330" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
		                    <div class='enlarge'></div>
		                </div>
                        <div class="product_description construct_example_description">
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Сталь</span>
                                </dt>
                                <dd>х12мф</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина клинка</span>
                                </dt>
                                <dd>135 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Ширина клинка</span>
                                </dt>
                                <dd>33 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Толщина обуха</span>
                                </dt>
                                <dd>4 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Больстер</span>
                                </dt>
                                <dd>латунь</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Длина рукояти</span>
                                </dt>
                                <dd>120 мм</dd>
                            </dl>
                            <dl class="dl-inline clearfix">
                                <dt class="dt-dotted">
                                    <span>Рукоять</span>
                                </dt>
                                <dd>граб</dd>
                            </dl>
                        </div>
	                </section>
	            </section>
            </div>
        </main>
        @include('handleOldToken') 
        @include('layouts.footerBig')   
    </body>
<script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}"></script>
<script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
<script src="{{ asset('js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
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
        $('.exampleImage').mouseover(function(){
            $(this).next('.enlarge').css('display', 'block');
        });
        $('.exampleImage').mouseout(function(){
            $(this).next('.enlarge').css('display', 'none');
        });

        /*Вывод картинки на весь экран*/
       $('.exampleImage').click(function(){
            var src = $(this).attr('data-bigSrc');
            $('#window').attr('src',src);
            $('#window').load(function(){
                $('#wrap_for_product').css('display','block');
                $('.enlarge').css('display', 'none');
                hideMainScroll();
            })
        });
       /*Аналогичные действия esc-ейпу от клика вне*/
        $(document).mouseup(function (e) {
            if ($('body').hasClass('unclicked') || $('#error_message').is(':visible') || $('#note_message').is(':visible')) return false;
            if (e.which != 1) return false;
            if ($('#wrap_for_product').is(':visible')){
                div=$('#window');
                if (!div.is(e.target) && div.has(e.target).length === 0) {
                    closeMainImg();
                }
            }
        });
        /*Отработка действия клавиши esc*/
        window.addEventListener("keydown", function(event){
            if(event.keyCode===27) {
               closeMainImg();
            }
        });
        $(document).mousemove(function(e){
            if ($('#wrap_for_product').is(':visible')) {
                place_image=$('#window').offset();
                x = e.pageX - place_image.left;
                y = e.pageY - place_image.top;
                div1=$('.prevMainImage');
                div2=$('.nextMainImage');
                if ((
                    x > $('#window').outerWidth() ||
                    y > $('#window').outerHeight() ||
                    x < 0 ||
                    y < 0 ) && !div1.is(e.target) && div1.has(e.target).length === 0 && !div2.is(e.target) && div2.has(e.target).length === 0
                ) {
                        $('#wrap_for_product').addClass('hoveredClose');
                } else {
                    $('#wrap_for_product').removeClass('hoveredClose');
                }
            }
    	});
    });
</script>
@endsection