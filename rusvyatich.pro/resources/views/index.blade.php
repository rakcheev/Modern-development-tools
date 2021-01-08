@extends('layouts.site')

@section('content')
    <body class="indexBody @if ($mobile == 1) mobileBody @endif">
    <script type="text/javascript">
        //alert('Сайт находится на стадии разработки и не функционирует в полной мере');
        if(!!window.performance && window.performance.navigation.type === 2)
        {
            window.location.reload();
        }
    </script>
    @include('layouts.metrix')<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?159",t.onload=function(){VK.Retargeting.Init("VK-RTRG-273901-7YqLn"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-273901-7YqLn" style="position:fixed; left:-999px;" alt=""/></noscript>
    <header class="mainHeader">
        <div class="container clearfix">
            <!--<span class="Runic" style="font-size: 40px; position: fixed; left: 30px; top:-6px;">В</span>-->
            <div class="menu_block">
                <button class="open_menu" onclick="showMenu();"></button>
                <nav class="menu">
                    <ul>
                        <!--   <li>
                              <a href="#how_this_work" class="go_to">Как это работает</a>
                          </li> -->
                        <li>
                            <a href="#advantag" class="go_to">У нас</a>
                        </li>
                        <li>
                            <a  @if($mobile == 1) href="#constructorPhone" @else href="#form_constructor" @endif class="go_to">Конструктор ножей</a>
                        </li>
                        <li>
                            <a href="#how_we_make_knife" class="go_to">Как мы делаем ваш нож</a>
                        </li>
                        <!--   <li>
                              <a href="#about_us" class="go_to">О нас</a>
                          </li> -->
                        <!-- <li>
                            <a href="#our_guarantees" class="go_to">Наши гарантии</a>
                        </li> -->
                        <li>
                            <a href="#individual" class="go_to">Индивидуальный нож</a>
                        </li>
                        <li>
                            <a href="#our_products" class="go_to">Наши изделия</a>
                        </li>
                        <li>
                            <a href="/shop">Магазин ножей</a>
                        </li>
                        <!--<li>
                            <a href="#faq">Частые вопросы</a>
                        </li>-->
                    </ul>
                </nav>
            </div>
            <a href="#" class="Runic brandInHeader">ВяТИч</a>
            <!--<span class="Runic" id="brandIcon">В</span>-->
            <div class="header_right">
                <div class="phone_for_call">
                    <span class="timeCall">с {{START_WORK_DAYSHIFT}}:00 до {{END_WORK_DAYSHIFT}}:00</span><span>+7 (925) 195-59-78</span>
                </div>
                <div class="userIndexBlock">
                    <span id="countBox">
                        <script id="countTemplate" type="text/x-jquery-tmpl">
                            @{{if newCount>0}}<span class="newMessageCount @{{if newCount>9}}wideCountTwo@{{/if}} @{{if newCount>99}}wideCountThree@{{/if}}">${newCount}</span>@{{/if}}
                        </script>
                    </span>
                    <div class="wrapLogin icon-profile">
                        <a id="toHome" href="@if (!empty(Session::get('userId')))
                                /home
@else
                                /auth
@endif"
                           @if (!empty(Session::get('userId')))
                           class="accounted"
                                @endif>{{ $username }}</a></div>
                    @if (!empty(Session::get('userId')))
                        <a id="outUser" class="icon-exit" href="#" onclick="confirmationOut(); return false">Выйти</a>
                    @endif
                </div>
                <div class="bond_block">
                    <a href="https://vk.com/rusvyatich" class="vkontakte" target="_blank">Вконтакте</a>
                    <a href="https://www.instagram.com/rusvyatich/" class="instagram" target="_blank">Инстаграм</a>
                </div>
            </div>
        </div>
    </header>
    <main role="main">
        <div id="wrap_for_product">
            <img id="window" src="/./.:0" alt="Нож кузницы Вятич" title="Нож кузницы Вятич">
            <button id="close_main_img" class="window_close" type="button" title="Закрыть"onclick="closeMainImg();"></button>
        </div>
        @if($mobile == 1)
            <div class="main_image" style="height: 570px;">
                <img  src="{{ asset('img/knifes/forest333.jpg') }}?{{VERSION}}" height="100%" alt="Кованый нож" title="Кованый нож" >
                @else
                    <div class="main_image">
                        <img  src="{{ asset('img/knifes/forest338.jpg') }}?{{VERSION}}" width="100%"  alt="Кованый нож" title="Кованый нож" >
                    </div>
                @endif
            </div>=
            <div class="first_impression container" style="height: @if($mobile) 600px @else 366px; @endif">
                <div class="first_impression_right">
                    <div class="main_titles @if($mobile == 1) phn @endif">
                        <span class="brand">ВяТИч</span>
                        <h1>Сконструируй нож —<br>
                            мы скуем</h1>
                        <a @if($mobile == 1) href="#constructorPhone" @else href="#form_constructor" @endif class="order_button button go_to">Собрать нож</a>
                    </div>
                </div>
            </div>
            <div class="page container">
                <!--<iframe width="672" height="378" src="https://www.youtube.com/embed/AKLmuwfW-tM?autoplay=1&controls=0&rel=0&showinfo=0&loop=1&mute=1" frameborder="0" allowfullscreen ></iframe>-->
                <!-- <section id="how_this_work" class="content clearfix">
                    <h3>Как это работает</h3>
                    <div class="innerContainer">
                    <section class="howItWorks">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                <g transform="scale(1.8, 1.8) translate(-700 -100)">
                                    <g class="bagStroke" transform="translate(580 30)" stroke="#555555" stroke-width="2.5">

                                        <g class="edgeFix" transform="translate(-360 -20)">
                                            <path transform="scale(1.5625 1.5) translate(0.3 0)" vector-effect="non-scaling-stroke" d="M317,49 Q336,130 610,120 L600,120 Q628,105 660,102 M660,40 L510,40 L313,56 " fill="none"/>
                                        </g>
                                        <g class="edgeFix" transform="translate(-365.71428571428567 -22.857142857142854)">
                                            <path transform="scale(1.5714285714285714 1.5714285714285714) translate(19.7 0)" vector-effect="non-scaling-stroke" d="M640,40 L880,40 Q920,63 900,100  L840,100 L710,100 Q670,80 640,96z" fill="none"/>
                                        </g>
                                    </g>
                                    <g transform="translate(705 132) scale(0.95 0.95) "  stroke="#555555" stroke-width="18">
                                        <line x1="0" y1="325" x2="1040" y2="325" />
                                        <line x1="0" y1="335" x2="0" y2="241" />
                                        <line x1="0" y1="250" x2="1040" y2="250"/>
                                        <line x1="1040" y1="335" x2="1040" y2="241"/>
                                        <line x1="80" y1="325" x2="80" y2="280"/>
                                        <line x1="160" y1="325" x2="160" y2="280"/>
                                        <line x1="240" y1="325" x2="240" y2="280"/>
                                        <line x1="320" y1="325" x2="320" y2="280"/>
                                        <line x1="400" y1="325" x2="400" y2="280"/>
                                        <line x1="480" y1="325" x2="480" y2="280"/>
                                        <line x1="560" y1="325" x2="560" y2="280"/>
                                        <line x1="640" y1="325" x2="640" y2="280"/>
                                        <line x1="720" y1="325" x2="720" y2="280"/>
                                        <line x1="800" y1="325" x2="800" y2="280"/>
                                        <line x1="880" y1="325" x2="880" y2="280"/>
                                        <line x1="960" y1="325" x2="960" y2="280"/>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <button class="descStep">Вы подбираете нужную<br>конфигурацию ножа</button>
                    </section>
                    <section class="howItWorks">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                <g transform="scale(1.9, 1.9) translate(415 70)" stroke="#555555" stroke-width="18">
                                    <circle cx="60" cy="60" r="400" fill="none"/>
                                    <circle cx="60" cy="60" r="30" fill="none"/>
                                    <g transform="rotate(-45 60 60)">
                                    <line x1="60" y1="-160" x2="60" y2="30"/>
                                    <line x1="80" y1="60" x2="360" y2="60"/>
                                    </g>
                                    <line x1="-340" y1="60" x2="-280" y2="60"/>
                                    <line x1="460" y1="60" x2="400" y2="60"/>
                                    <line x1="60" y1="-340" x2="60" y2="-280"/>
                                    <line x1="60" y1="460" x2="60" y2="400"/>

                                </g>
                            </svg>
                        </div>
                        <button class="descStep">Мы сообщаем вам сроки<br>выполнения заказа, и вы вносите предоплату</button>
                    </section>
                    <section class="howItWorks">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
                                    <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/> 
                                    <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>

                                </g>
                            </svg>
                        </div>
                        <button class="descStep">Мастер изготавливает ваш нож<br> и отправляет фото по готовности</button>
                    </section>
                    <section class="howItWorks">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                <path transform="scale(11.5, 11.5) translate(-17 -120)" d="m 101.46999,201.29427 c -1.801558,-0.39156 -4.307918,-1.73528 -9.230548,-4.94872 -1.24985,-0.8159 -2.33811,-1.48345 -2.41835,-1.48345 -0.0802,0 -1.02328,0.72082 -2.09565,1.60182 -4.00879,3.29345 -5.52411,4.03795 -7.98567,3.92351 -2.0181,-0.0938 -3.47099,-0.77737 -5.06805,-2.38442 -1.23024,-1.23794 -1.82795,-2.26435 -1.94625,-3.34216 -0.16263,-1.48171 -0.0294,-1.41735 -1.6004,-0.77328 -1.30651,0.53564 -1.4998,0.57087 -3.13947,0.57222 -1.55253,0.001 -1.8623,-0.0473 -2.77971,-0.43588 -1.32972,-0.56323 -2.77541,-1.65138 -3.35383,-2.52437 -0.61252,-0.92447 -1.23102,-2.50483 -1.37567,-3.51498 l -0.1183,-0.82621 -0.95145,-0.10189 c -0.5233,-0.0561 -1.56622,-0.10965 -2.31761,-0.11913 -1.64254,-0.0207 -2.12345,-0.16855 -3.50925,-1.0787 -2.08644,-1.37031 -2.8659,-2.96111 -3.23512,-6.60258 l -0.0926,-0.91334 h -0.76109 c -2.62408,0 -4.94045,-1.27447 -6.44331,-3.54513 -1.24271,-1.8776 -1.37854,-4.69337 -0.34179,-7.08542 0.31904,-0.73612 0.58008,-1.35585 0.58008,-1.37719 0,-0.17416 -3.38746,-2.77077 -3.47606,-2.66451 -0.0634,0.0761 -0.31275,0.40789 -0.55399,0.7373 -0.71041,0.97002 -1.07624,1.1406 -1.96681,0.91709 -0.927466,-0.23278 -4.253309,-1.89121 -10.290742,-5.13149 -5.595798,-3.00325 -8.742018,-4.75439 -9.113471,-5.07244 -0.255324,-0.21862 -0.28731,-0.86563 -0.384017,-7.76772 -0.141806,-10.12083 -0.116708,-47.751075 0.0349,-52.322179 0.07382,-2.225677 0.192821,-3.786457 0.304531,-3.994008 0.100851,-0.187376 0.395568,-0.414658 0.654926,-0.50507 0.454938,-0.158593 0.66642,-0.01752 5.999571,4.002027 15.010972,11.31364 37.233142,28.61322 37.992232,29.5763 0.46434,0.58912 0.38596,1.40502 -0.2234,2.32583 l -0.42802,0.64678 4.50457,-0.0926 c 4.71575,-0.0969 7.30713,-0.28702 14.67033,-1.07634 5.87074,-0.62933 8.14944,-0.71418 11.06692,-0.41208 1.2819,0.13273 2.49702,0.20238 2.70029,0.15478 0.20327,-0.0476 0.98396,-0.32745 1.73486,-0.62187 3.627348,-1.42219 6.698998,-1.05394 14.439308,1.73104 4.79905,1.7267 7.66086,2.65408 8.19029,2.65408 0.76026,0 2.33063,-0.48769 5.17798,-1.60807 3.24619,-1.27731 3.68725,-1.5501 3.4624,-2.14151 -0.26438,-0.69537 -0.16972,-1.04124 0.4432,-1.61936 2.66525,-2.51388 32.50419,-25.882044 40.77025,-31.928928 2.34319,-1.714121 2.82692,-1.90043 3.41289,-1.314465 0.20173,0.201733 0.34822,0.667833 0.4368,1.389852 0.17386,1.416926 0.1742,60.318151 3.2e-4,61.734391 l -0.13292,1.08296 -0.82012,0.52072 c -0.94073,0.59731 -5.94043,3.33509 -11.93892,6.53764 -6.13492,3.27538 -7.31928,3.68468 -8.28721,2.86395 -0.24695,-0.2094 -0.98509,-1.31626 -1.6403,-2.45971 -0.65522,-1.14344 -1.22825,-2.08004 -1.27341,-2.08131 -0.0452,-10e-4 -2.19846,1.88639 -4.78511,4.1948 l -4.70301,4.19712 -0.10477,1.08432 c -0.41609,4.30614 -2.36067,6.58983 -6.41682,7.53579 l -1.42473,0.33227 -0.10997,1.53217 c -0.245,3.41365 -1.05692,4.89993 -3.4395,6.29633 -1.76009,1.03158 -2.33652,1.19236 -4.27484,1.19236 h -1.74137 l -0.002,0.59565 c -0.004,0.95914 -0.44353,2.55861 -0.98035,3.56579 -0.91574,1.71811 -2.93934,3.21728 -4.91448,3.64088 -1.20024,0.2574 -3.52274,0.0953 -4.55244,-0.31767 -0.46189,-0.18527 -0.89093,-0.33684 -0.95342,-0.33684 -0.0625,0 -0.23456,0.53208 -0.38236,1.1824 -0.37421,1.64652 -0.96208,2.80126 -1.99296,3.91473 -1.7721,1.91409 -4.23663,2.78101 -6.50465,2.28808 z m 2.89334,-3.31217 c 1.03231,-0.48072 1.78014,-1.24001 2.26554,-2.30028 0.81333,-1.77653 0.37013,-3.36287 -1.39542,-4.99465 -0.58757,-0.54306 -3.27602,-2.57582 -5.974348,-4.51725 -7.98769,-5.74711 -8.30304,-6.04134 -7.82397,-7.30006 0.34678,-0.91114 0.90063,-1.04349 1.94245,-0.46418 1.35287,0.75227 5.2649,3.42359 11.220678,7.662 3.10136,2.20707 6.20645,4.35606 6.90019,4.77553 1.24594,0.75334 1.27799,0.76266 2.62086,0.76266 2.08686,0 3.31594,-0.70737 4.19509,-2.41442 0.55703,-1.08159 0.23396,-3.58514 -0.57748,-4.47504 -0.50308,-0.55172 -4.74279,-3.68359 -12.39469,-9.15595 -3.65315,-2.61261 -6.923268,-4.99087 -7.266938,-5.28503 -0.58044,-0.49684 -0.62484,-0.59429 -0.62484,-1.37147 0,-0.96566 0.27888,-1.32562 1.1319,-1.46103 0.63594,-0.10095 0.544,-0.16195 11.972418,7.94278 12.62143,8.95077 11.56238,8.26142 12.79934,8.33132 2.74632,0.15517 4.66508,-1.42883 4.87087,-4.02107 0.10901,-1.37317 -0.17577,-2.25877 -0.97147,-3.02106 -0.99833,-0.95642 -3.8074,-3.0545 -10.74223,-8.02334 -6.9675,-4.99225 -12.80619,-9.31553 -13.20868,-9.78043 -0.54122,-0.62514 -0.4339,-1.57276 0.22349,-1.97359 1.11848,-0.68195 0.24317,-1.20529 12.30722,7.35841 11.60596,8.23852 13.3107,9.43373 13.86252,9.71908 0.78076,0.40375 2.47287,0.4851 3.55382,0.17086 2.1574,-0.62717 3.30449,-3.06175 2.62528,-5.57185 -0.30807,-1.13852 -1.09744,-1.83115 -16.74279,-14.69068 -4.71755,-3.87754 -10.0985,-8.30552 -11.95766,-9.83996 -1.85916,-1.53443 -3.51554,-2.812 -3.68084,-2.83904 -0.1653,-0.027 -1.05298,0.24503 -1.97262,0.6046 -3.704628,1.44843 -6.135698,1.91235 -10.600958,2.02293 -4.29935,0.10648 -7.31721,-0.23195 -9.56383,-1.07253 -1.09926,-0.41129 -2.68643,-1.57936 -3.3035,-2.4312 -0.39506,-0.54537 -0.4573,-0.77551 -0.4573,-1.69103 0,-1.58976 0.36178,-2.45686 1.4965,-3.58678 1.18866,-1.18363 2.3394,-1.88745 5.19588,-3.17793 3.17575,-1.43471 6.33251,-3.187 6.33251,-3.51511 0,-0.21255 -5.26765,0.11702 -9.05388,0.56645 -6.11988,0.72644 -12.28765,1.11296 -18.18721,1.13974 -1.22307,0.005 -2.50967,0.0607 -2.85912,0.12264 l -0.63537,0.11256 -2.99235,4.92147 c -10.26205,16.87782 -14.45788,23.98176 -14.88846,25.20755 -0.25642,0.73001 -0.24434,0.74509 2.01167,2.51169 1.05385,0.82524 1.71909,1.24546 1.84826,1.16752 0.11098,-0.067 1.09526,-0.84262 2.18729,-1.72366 2.6157,-2.11034 3.88932,-3.00701 5.02302,-3.53638 0.85447,-0.39898 1.09512,-0.436 2.84291,-0.43725 1.85084,-0.001 1.943,0.0151 3.00366,0.53732 2.14648,1.05671 4.0012,3.4687 4.17952,5.43532 0.0471,0.51951 0.13558,0.97542 0.1966,1.01313 0.061,0.0377 0.64061,-0.26229 1.28796,-0.66668 1.68411,-1.05204 2.77329,-1.3673 4.35382,-1.26019 2.18379,0.14798 3.90343,0.91149 5.21781,2.31666 1.14025,1.21903 1.67441,2.50074 1.90107,4.56166 l 0.18835,1.71254 1.25786,0.20981 c 0.69181,0.1154 1.76385,0.42119 2.38231,0.67954 2.78486,1.16333 4.13161,3.0332 4.55338,6.32207 l 0.18169,1.41668 1.26359,0.11023 c 3.20345,0.27944 5.53183,1.71527 6.64742,4.09923 0.91352,1.95215 0.97105,3.53253 0.21955,6.03121 -0.2172,0.72218 -0.35881,1.46021 -0.31469,1.64005 0.14001,0.5707 6.82502,4.75693 8.905058,5.57645 0.81447,0.32089 2.13574,0.2498 3.02129,-0.16257 z m -22.876318,-0.87818 c 1.23083,-0.5669 5.30801,-3.68221 7.28782,-5.56851 1.29853,-1.23719 1.52895,-1.71199 1.52433,-3.14095 -0.008,-2.3435 -1.48637,-4.1273 -3.72337,-4.49138 -1.23637,-0.20122 -2.17023,0.009 -3.20694,0.72346 -1.27912,0.88097 -4.88042,3.89888 -6.20539,5.20016 -1.02062,1.00237 -1.1666,1.22187 -1.32723,1.99564 -0.28386,1.3673 -0.13554,2.72182 0.38398,3.50688 0.46986,0.71 1.55452,1.55992 2.37845,1.86369 0.77826,0.28693 2.16494,0.24421 2.88835,-0.089 z m -11.95264,-5.8268 c 0.74693,-0.2545 3.89171,-2.67748 7.89542,-6.08323 3.27001,-2.78163 3.74838,-3.30191 3.97758,-4.32607 0.46605,-2.08258 -0.75054,-4.34425 -2.83215,-5.26505 -0.53862,-0.23826 -0.95762,-0.29717 -1.78218,-0.25058 -1.30153,0.0736 -1.4308,0.15496 -5.80825,3.65786 -4.75832,3.80769 -6.67823,5.49253 -6.99381,6.13748 -0.17925,0.36635 -0.30685,1.06427 -0.34509,1.88764 -0.051,1.09885 -0.005,1.42027 0.28645,2.02342 0.42445,0.87678 1.54456,1.89155 2.43693,2.20776 0.88327,0.31297 2.2644,0.31767 3.1651,0.0107 z m -10.03475,-7.43313 c 0.91654,-0.32768 5.75325,-4.18557 10.7968,-8.61185 2.51352,-2.20588 2.74825,-2.52888 2.82501,-3.8873 0.0868,-1.5368 -0.28276,-2.54481 -1.30957,-3.57162 -1.22923,-1.22923 -2.91002,-1.65683 -4.40862,-1.12158 -0.95358,0.3406 -3.88246,2.59253 -8.7868,6.75594 -3.41655,2.90038 -4.69386,4.12128 -4.95009,4.73149 -0.35335,0.84148 -0.27247,2.53714 0.16503,3.45987 0.46206,0.97452 1.43553,1.93447 2.25955,2.22817 0.77384,0.2758 2.65832,0.28514 3.40869,0.0169 z m -8.19225,-9.00533 c 1.57724,-0.74599 7.21163,-5.41388 8.26966,-6.85112 0.84562,-1.14869 1.004,-1.94681 0.63692,-3.20952 -0.56338,-1.93797 -2.52566,-3.51173 -4.36322,-3.49933 -1.08108,0.007 -1.76437,0.33701 -3.74915,1.80908 -3.03143,2.24835 -6.27065,5.11141 -6.68294,5.90687 -0.40645,0.78417 -0.55035,1.80732 -0.3803,2.70397 0.3016,1.59031 1.75142,3.1982 3.13836,3.48049 0.85546,0.17411 2.39722,0.006 3.13067,-0.34044 z m 91.203678,-13.06972 c 3.40321,-3.03352 4.33871,-3.97117 4.33871,-4.34872 0,-0.3011 -4.27982,-7.43206 -10.84682,-18.07278 -5.23863,-8.48833 -6.29025,-10.08635 -6.63759,-10.08635 -0.16738,0 -2.07372,0.68495 -4.23631,1.52211 -2.16258,0.83717 -4.2292,1.63642 -4.59248,1.77611 -0.86388,0.33219 -1.3104,0.22525 -6.64614,-1.59184 -10.88568,-3.70711 -10.88518,-3.70698 -13.58998,-3.57755 -1.876488,0.0898 -2.115948,0.19409 -6.662148,2.90165 -3.28964,1.95921 -6.06773,3.40501 -9.76867,5.08393 -2.22623,1.00992 -3.25622,1.9193 -3.25622,2.87494 0,0.80879 0.2942,1.21458 1.18376,1.63272 1.41626,0.66572 3.31325,0.87635 7.87012,0.87384 5.79718,-0.003 8.03385,-0.41548 11.198238,-2.06419 0.78626,-0.40966 1.69279,-0.88178 2.01452,-1.04916 0.69396,-0.36104 0.80858,-0.318 2.35359,0.88376 5.18758,4.03512 31.85938,26.16667 32.5471,27.00673 0.20852,0.25471 0.25011,0.24784 0.63536,-0.10499 0.22673,-0.20765 2.06946,-1.85474 4.09496,-3.66021 z M 39.395672,158.24943 c 1.20839,-2.00933 5.01919,-8.2994 8.46843,-13.97794 9.5291,-15.68788 11.30587,-18.63992 11.30587,-18.78429 0,-0.24691 -1.18028,-1.25763 -4.52695,-3.87661 C 41.936402,111.66684 27.083503,100.16531 20.770345,95.380855 20.61168,95.26061 20.571794,101.06572 20.571794,124.27884 v 29.04845 l 8.219982,4.38849 c 4.520989,2.41366 8.26202,4.34314 8.313398,4.28772 0.05138,-0.0554 1.082108,-1.74474 2.290498,-3.75407 z m 118.871008,1.25179 c 2.58795,-1.36452 6.26001,-3.31189 8.16014,-4.32748 l 3.45477,-1.84652 v -28.94955 c 0,-17.32115 -0.0582,-28.949548 -0.14479,-28.949548 -0.22928,0 -10.92926,8.240758 -27.95272,21.528228 -9.9876,7.79571 -10.26772,8.02997 -10.22993,8.55523 0.044,0.61163 0.61963,1.60047 8.70151,14.94774 3.86158,6.37744 8.37243,13.8276 10.0241,16.55591 1.65167,2.72832 3.0657,4.96201 3.14229,4.96376 0.0766,0.002 2.25668,-1.11325 4.84463,-2.47777 z"
                                style="fill: #555555;stroke-width:0.19884021" />
                            </svg>
                        </div>
                        <button class="descStep">После полной оплаты ножа<br>мы высылаем его вам</button>
                    </section>
                    </div>
                </section> -->

                <section id="advantag" class="action content clearfix">

                    <h3>У нас</h3>
                    <div class="innerContainer clearfix">
                        <section class="howItWorks">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                    <g transform="scale(1.8, 1.8) translate(-700 -100)">
                                        <g class="bagStroke" transform="translate(580 30)" stroke="#555555" stroke-width="2.5">

                                            <g class="edgeFix" transform="translate(-360 -20)">
                                                <path transform="scale(1.5625 1.5) translate(0.3 0)" vector-effect="non-scaling-stroke" d="M317,49 Q336,130 610,120 L600,120 Q628,105 660,102 M660,40 L510,40 L313,56 " fill="none"/>
                                            </g>
                                            <g class="edgeFix" transform="translate(-365.71428571428567 -22.857142857142854)">
                                                <path transform="scale(1.5714285714285714 1.5714285714285714) translate(19.7 0)" vector-effect="non-scaling-stroke" d="M640,40 L880,40 Q920,63 900,100  L840,100 L710,100 Q670,80 640,96z" fill="none"/>
                                            </g>
                                        </g>
                                        <g transform="translate(705 132) scale(0.95 0.95) "  stroke="#555555" stroke-width="18">
                                            <line x1="0" y1="325" x2="1040" y2="325" />
                                            <line x1="0" y1="335" x2="0" y2="241" />
                                            <line x1="0" y1="250" x2="1040" y2="250"/>
                                            <line x1="1040" y1="335" x2="1040" y2="241"/>
                                            <line x1="80" y1="325" x2="80" y2="280"/>
                                            <line x1="160" y1="325" x2="160" y2="280"/>
                                            <line x1="240" y1="325" x2="240" y2="280"/>
                                            <line x1="320" y1="325" x2="320" y2="280"/>
                                            <line x1="400" y1="325" x2="400" y2="280"/>
                                            <line x1="480" y1="325" x2="480" y2="280"/>
                                            <line x1="560" y1="325" x2="560" y2="280"/>
                                            <line x1="640" y1="325" x2="640" y2="280"/>
                                            <line x1="720" y1="325" x2="720" y2="280"/>
                                            <line x1="800" y1="325" x2="800" y2="280"/>
                                            <line x1="880" y1="325" x2="880" y2="280"/>
                                            <line x1="960" y1="325" x2="960" y2="280"/>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <button class="descStep">Уникальный конструктор</button>
                            <span>Только у нас конструктор ножей в реальный размер.</span>
                        </section>
                        <section class="howItWorks">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                    <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
                                        <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/>
                                        <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M640,100 L650,100  L660,95 L720,95 L730,100 L740,100 L740,90 L720,80 L720,60 L760,40 L760,35 L650,35 L640,40 L600,40 L640,65 L665,65 L665,80 L640,90z"/>
                                    </g>
                                </svg>
                            </div>
                            <button class="descStep">Только ручная работа<br></button>
                            <span>Из высококачественных материалов мастер вручную куёт клинок и изготавливает рукоять.</span>
                        </section>
                        <section class="howItWorks">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                    <g class="bagStroke " transform="scale(10, 10) translate(-600 5)" stroke="#555555">
                                        <!-- <path class="fixKuznEdge" transform="rotate(215 710 -10)" vector-effect="non-scaling-stroke" fill="none"   d="M640,0 L640,8 L710,8 L710,28 L730,28 L730,-20 L710,-20 L710,0 L710,8 L710,0z "/>  -->
                                        <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M642,100 L730,100 L730,45 L692,35 L692,60 L714,66 L714,100 M642,100 L642,45 L683.5,35 L683.5,60 L662,65 L662,100 M790,46 L720, -10 L712,-5 L686,-25 L703,-56 L727,-38 L726,-52 L710,-60 L 730,-60 L736,-54 L737,-30 L790,10 M688,80 Q690,20 703,-10 M688,80 Q684,0 695-17 M710,-50 L715,-60 M723, -43 L727,-52"/>
                                        <!--  <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M688,75 Q690,0 710,-25" stroke-width="1"></path> -->

                                    </g>
                                </svg>
                            </div>
                            <button class="descStep">Тесты на прочность<br></button>
                            <span>Каждый клинок проходит тест на излом и крошение режущей кромки.</span>
                        </section>
                        <section class="howItWorks">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                    <g transform="scale(1.9, 1.9) translate(415 70)" stroke="#555555" stroke-width="18">
                                        <circle cx="60" cy="60" r="400" fill="none"/>
                                        <circle cx="60" cy="60" r="30" fill="none"/>
                                        <g transform="rotate(-45 60 60)">
                                            <line x1="60" y1="-160" x2="60" y2="30"/>
                                            <line x1="80" y1="60" x2="360" y2="60"/>
                                        </g>
                                        <line x1="-340" y1="60" x2="-280" y2="60"/>
                                        <line x1="460" y1="60" x2="400" y2="60"/>
                                        <line x1="60" y1="-340" x2="60" y2="-280"/>
                                        <line x1="60" y1="460" x2="60" y2="400"/>

                                    </g>
                                </svg>
                            </div>
                            <button class="descStep">Фиксированные сроки<br></button>
                            <span>Устанавливаем точные сроки, за не выполнение которых с каждым просроченным днём скидка в 3%.</span>
                        </section>
                        <section class="howItWorks nextHowItWorks">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                    <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
                                        <path class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M610,25 Q615,95 690,25 Q765,-45 770,25 M610,25 Q615,-45 690,25 Q765,95 770,25"/>

                                    </g>
                                </svg>
                            </div>
                            <button class="descStep">Гарантия качества<br></button>
                            <span>Ножи имеют пожизненную гарантию от производственных дефектов. Сломался по нашей вине - вернём деньги.</span>
                        </section>
                        <section class="howItWorks">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 1800 487" >
                                    <g class="bagStroke " transform="scale(10, 10) translate(-600 0)" stroke="#555555">
                                        <path id="zzz" class="fixKuznEdge" vector-effect="non-scaling-stroke" fill="none" d="M610,25 Q690,105  770,25 Q690,-45 610,26 M670,25a20,20 0 1,0 40,0a20,20 0 1,0 -40,0"/>

                                    </g>
                                </svg>
                            </div>
                            <button class="descStep">Отслеживание процесса<br></button>
                            <span>Кузнец поэтапно отправляет фотоотчёты о проделанной работе.</span>
                        </section>
                    </div>
                </section>
                <section id="action" class="action content clearfix">
                    <!--<p class="getCustomer">Прямо сейчас Вы можете узнать стоимость изготовления ножа рассчитав её по нашему конструктору. Так же мы можем сковать индивидуальный нож по вашему описанию, фотографии, чертежу(с описанием размеров и материала изготовления).</p>-->
                    <h3>Рассчитайте цену своего ножа!</h3>
                    <div class="innerContainer clearfix">
                        <p class="aboutForm">
                            Будьте внимательны при конфигурировании изделия. Чтобы ваше изделие не попало под категорию <a href="/coldArms" style="color:black;">холодного оружия</a>, диапазон размерностей ножа может автоматически меняться в зависимости от выбранных характеристик (например, при выборе гарды или рукояти с подпальцевыми выемками меняется допустимая толщина обуха на 2.4 мм в соответствии с законодательством РФ). Для всех ножей с клинком более 140 мм или с наборной рукоятью (состоит более чем из одного материала) выполняется сквозной монтаж. Больстер не учитывается в длине рукояти.
                        </p>

                        <ul class="list listCircle leftAboutOrder">
                            <li>Сроки изготовления — от 7-и до 14-и дней.</li>
                            <li>Предоплата {{PERSENT}}%.</li>
                            <li>Ножны из натуральной кожи под каждый нож — в подарок.
                            </li><img class="nozhn" src="{{ asset('img/nozhn.jpg') }}?{{VERSION}}" alt="Кожаные ножны" title="Кожаные ножны" >
                            <li style="color:red; font-weight: bold;">Скидка в 10% действует до 10-го декабря</li>
                        </ul>
                        <!--<button class="blockConsult">
                            <a href="#" class="button" id="buttonOpros" onclick="showOpros(); return false;">Пройди опрос и узнай какой нож подходит именно тебe + 5% скидка</a>
                        </button>-->
                        <div class="sortConstructBlock">
                            <b>Сортировать конструктор:</b>
                            <div>
                                <label>
                                    <input id="popularityConstruct" type="radio" name="sortConstruct" value="3" checked>
                                    <label class="Radio" for="popularityConstruct"></label>
                                    <span>По популярности</span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input id="downCost" type="radio" name="sortConstruct" value="1">
                                    <label class="Radio" for="downCost"></label>
                                    <span>По возрастанию цены</span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input id="upCost" type="radio" name="sortConstruct" value="2">
                                    <label class="Radio" for="upCost"></label>
                                    <span>По убыванию цены</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="otherContainer">
                        <form  id="form_constructor" method="POST" class="order clearfix">
                            <section class="stage_of_construct stage_bladeS">
                                <span class="captionConstruct">Форма клинка</span>
                                <div class="selector_of_construct">
                                    <div class="types_slider up_slid_blade">
                                        <ul id="down_slid_blade" class='line_of_types down_slid_blade'>
                                            @foreach ($typeOfBlades as $blade)
                                                <li class="bladeTemplateRow">
                                                    <label id="blade_construct_{{ $blade->id }}">
                                                        <input type="radio" name="blade_type_select" value="{{ $blade->id }}" onchange="getPath({{ $blade->id }},1)">
                                                        <span>{{ $blade->name }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 153" >
                                                            <path fill="none"  stroke-width="0.6" stroke="#000000" d="{{ $blade->path }}" vector-effect="non-scaling-stroke" transform="translate(-310 0)">
                                                        </svg>
                                                    </label>
                                                <!--<a id="info_blade_{{ $blade->id }}" href="#" class="info_link" onclick="showDescription({{ $blade->id }},2); return false;">!</a>-->
                                                </li>
                                            @endforeach
                                            <script id="bladesTypeTemplate" type="text/x-jquery-tmpl">
                                    <li class="bladeTemplateRow">
                                        <label id="blade_construct_${id}">
                                            <input type="radio" name="blade_type_select" value="${id}" onchange="getPath(${id},1)">
                                            <span>${name}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 153" >
                                                    <path fill="none"  stroke-width="0.6" stroke="#000000" d="${path}" vector-effect="non-scaling-stroke" transform="translate(-310 0)">
                                                </svg>
                                        </label>
                                        <!--<a id="info_blade_{{ $blade->id }}" href="#" class="info_link" onclick="showDescription({{ $blade->id }},2); return false;">!</a>-->
                                    </li>
                                </script>
                                        </ul>
                                    </div>
                                </div>
                                <div id="bladeDesc" class="descriptionStage">
                                    <p></p>
                                </div>
                            </section>
                            <section class="stage_of_construct stage_blade">
                                <span class="captionConstruct">Тип стали</span>
                                <div class="selector_of_construct for_blade">
                                    <div class="types_slider up_slid_steel">
                                        <ul id="down_slid_steel" class='line_of_types down_slid_steel'>
                                            @foreach ($typeOfSteels as $steel)
                                                <li class="steelTemplateRow">
                                                    <label id="steel_construct_{{ $steel->id }}">
                                                        <input type="radio" name="steel_type_select" value="{{ $steel->id }}" onchange="setTexture({{ $steel->id }} ,1)">
                                                        <span>{{ $steel->name }}</span>
                                                    </label>
                                                    <a id="info_steel_{{ $steel->id }}" href="#" class="info_link" onclick="showDescription({{ $steel->id }} ,1); return false;">!</a>
                                                </li>
                                            @endforeach
                                            <script id="steelsTypeTemplate" type="text/x-jquery-tmpl">
                                        <li class="steelTemplateRow">
                                            <label id="steel_construct_${id}">
                                                <input type="radio" name="steel_type_select" value="${id}" onchange="setTexture(${id} ,1)">
                                                <span>${name}</span>
                                            </label>
                                            <a id="info_steel_${id}" href="#" class="info_link" onclick="showDescription(${id} ,1); return false;">!</a>
                                        </li>
                                    </script>
                                        </ul>
                                    </div>
                                </div>
                                <div id="steelDesc" class="descriptionStage">
                                    <p></p>
                                </div>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Длина:</span>
                                    <span id="contentSliderBladeLength" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderBladeLength"></div>
                                <input type="text" id="length_blade_construct" value="130" name="blade_length_select"/>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Ширина:</span>
                                    <span id="contentSliderBladeHeight" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderBladeHeight"></div>
                                <input type="text" id="height_blade_construct" value="29" name="blade_height_select"/>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Обух:</span>
                                    <span id="contentSliderButtWidth" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderButtWidth"></div>
                                <input type="text" id="butt_width_construct" value="3.5" name="butt_width_select"/>
                            </section>
                            <section class="stage_of_construct stage_addition_blade">
                                <span class="captionConstruct">Доп. на клинок</span>
                                <div class="checks_slider">
                                    @foreach ($additionOfBlade as $addition)
                                        <div class="additionBlade @if($addition->damask == 2) notForDamask @endif @if ($addition->id == 3) skvoznoi @endif" >
                                            <label class="preventLabel">
                                                <input class="additionInput" data-price="{{$addition->price}}" id="additionBlade_{{$addition->id}}" type="checkbox" name="addition_{{$addition->id}}"  onchange="@if($addition->image) setAdditionTexture( '{{$addition->image}}', 'addition_{{$addition->id}}');@endif showNewSum();">
                                                <label class="checkConditions helpClass" for="additionBlade_{{$addition->id}}">
                                                </label>
                                                <span class="nameOfAddition">{{$addition->name}}</span>
                                            </label>
                                            <a href="#" class="info_link" onclick="showDescription({{$addition->id}}, 6); return false;">!</a>
                                        </div>
                                    @endforeach
                                    <span style="display: block; margin-top: 20px;"></span>
                                    <span class="captionConstruct" style="margin-top: 30px;">Спуски</span>
                                    @foreach ($spuski as $spusk)
                                        <div class="additionBlade spuskAddition">
                                            <label class="preventLabel">
                                                <input id="spusk_{{$spusk->id}}" type="radio" name="spusk" @if($spusk->id == 1) checked @endif value="{{$spusk->id}}">
                                                <label class="Radio helpClass" for="spusk_{{$spusk->id}}"></label>
                                                <span class="nameOfAddition">{{$spusk->name}}</span>
                                                <a href="#" class="info_link" onclick="showDescription({{$spusk->id}}, 7); return false;">!</a>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                            <section class="stage_of_construct stage_bolster">
                                <span class="captionConstruct">Больстер</span>
                                <div class="selector_of_construct ">
                                    <div class="types_slider up_slid_bolster">
                                        <ul id="down_slid_bolster" class='line_of_types down_slid_bolster'>
                                            @foreach ($typeOfBolsters as $bolster)
                                                <li class="bolsterTemplateRow">
                                                    <label id="bolster_construct_{{ $bolster->id }}">
                                                        <input type="radio" name="bolster_type_select" value="{{ $bolster->id }}" onchange="getPath({{ $bolster->id }},2)">
                                                        <span>{{ $bolster->name }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 153" >
                                                            <path fill="none"  stroke-width="0.6" stroke="#000000" d="{{ $bolster->path }}" vector-effect="non-scaling-stroke" transform="translate(-530 0)"/>
                                                        </svg>
                                                    </label>
                                                    @if ($bolster->id == 5)
                                                        <a id="info_bolster_{{ $bolster->id }}" href="#" class="info_link" onclick="showDescription({{ $bolster->id }},3); return false;">!</a>
                                                    @endif
                                                </li>
                                                <script id="bolstersTypeTemplate" type="text/x-jquery-tmpl">
                                        <li class="bolsterTemplateRow">
                                            <label id="bolster_construct_${id}">
                                                <input type="radio" name="bolster_type_select" value="${id}" onchange="getPath(${id},2)">
                                                <span>${name}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 153" >
                                                    <path fill="none"  stroke-width="0.6" stroke="#000000" d="${path}" vector-effect="non-scaling-stroke" transform="translate(-530 0)"/>
                                                </svg>
                                            </label>
                                            <!--<a id="info_bolster_{{ $bolster->id }}" href="#" class="info_link" onclick="showDescription({{ $bolster->id }},3); return false;">!</a>-->
                                        </li>
                                    </script>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div id="bolsterDesc" class="descriptionStage">
                                    <p></p>
                                </div>
                            </section>
                            <section class="stage_of_construct stage_handle_shape">
                                <span class="captionConstruct">Форма ручки</span>
                                <div class="selector_of_construct for_handle">
                                    <div class="types_slider up_slid_handle">
                                        <ul id="down_slid_handle" class='line_of_types down_slid_handle'>
                                            @foreach ($typeOfHandles as $handle)
                                                <li class="handleTemplateRow">
                                                    <label id="handle_construct_{{ $handle->id }}">
                                                        <input type="radio" name="handle_type_select" value="{{ $handle->id }}" data-path="{{ $handle->path }}" onchange="getPath({{ $handle->id }},3)">
                                                        <span>{{ $handle->name }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 153" >
                                                            <path fill="none"  stroke-width="0.6" stroke="#000000" d="{{ $handle->path }}" vector-effect="non-scaling-stroke" transform="translate(-610 0)"/>
                                                        </svg>
                                                    </label>
                                                <!--<a id="info_handle_{{ $handle->id }}" href="#" class="info_link" onclick="showDescription({{ $handle->id }},4); return false;">!</a>-->
                                                </li>
                                            @endforeach
                                            <script id="handlesTypeTemplate" type="text/x-jquery-tmpl">
                                        <li class="handleTemplateRow">
                                            <label id="handle_construct_${id}">
                                                <input type="radio" name="handle_type_select" value="${id}" data-path="${path}" onchange="getPath(${id},3)">
                                                <span>${name}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 300 153" >
                                                    <path fill="none"  stroke-width="0.6" stroke="#000000" d="${path}" vector-effect="non-scaling-stroke" transform="translate(-610 0)"/>
                                                </svg>
                                            </label>
                                            <!--<a id="info_handle_{{ $handle->id }}" href="#" class="info_link" onclick="showDescription({{ $handle->id }},4); return false;">!</a>-->
                                        </li>
                                    </script>
                                        </ul>
                                    </div>
                                </div>
                                <div id="shapeHandleDesc" class="descriptionStage">
                                    <p></p>
                                </div>
                            </section>
                            <section class="stage_of_construct stage_handle_material">
                                <span class="captionConstruct">Материал ручки</span>
                                <div class="selector_of_construct for_handle_material">
                                    <div class="types_slider up_slid_handle_material">
                                        <ul id="down_slid_handle_material" class='line_of_types down_slid_handle_material'>
                                            @foreach ($typeOfHandleMaterials as $handleMaterial)
                                                <li class="handleMaterialTemplateRow @if($handleMaterial->nabor == 1) nabor @else notNabor @endif">
                                                    <label id="handle_material_construct_{{ $handleMaterial->id }}">
                                                        <input type="radio" name="handle_material_type_select" value="{{ $handleMaterial->id }}" data-path="{{ $handleMaterial->path }}" onchange="setTexture({{ $handleMaterial->id }},2)" onclick="setted();"   />
                                                        <span>{{ $handleMaterial->name }}</span>
                                                    </label>
                                                <!--  <a id="info_handle_{{ $handleMaterial->id }}" href="#" class="info_link" onclick="showDescription({{ $handleMaterial->id }},5);">!</a> -->
                                                </li>
                                            @endforeach
                                            <script id="handlesMaterialTypeTemplate" type="text/x-jquery-tmpl">
                                        <li class="scripted handleMaterialTemplateRow @{{if nabor == 1}} nabor  @{{else}} notNabor  @{{/if}}">
                                        <label id="handle_material_construct_${id}">
                                            <input type="radio" name="handle_material_type_select" value="${id}" data-path="${path}" onchange="setTexture(${id},2)" onclick="setted();"   />
                                            <span>${name}</span>
                                        </label>
                                            <!--<a id="info_handle_{{ $handle->id }}" href="#" class="info_link" onclick="showDescription({{ $handle->id }},4); return false;">!</a>-->
                                        </li>
                                    </script>
                                        </ul>
                                    </div>
                                </div>
                                <div id="materialHandleDesc" class="descriptionStage">
                                    <p></p>
                                </div>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Длина:</span>
                                    <span id="contentSliderHandle" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="sliderHandle"></div>
                                <input type="text" id="length_handle_construct" value="120" name="handle_length_select"/>
                            </section>
                            <section class="stage_of_construct">
                                <input type="hidden" name="oprosSale" value="0">
                                <input type="hidden" name="mobile" value="0">
                                <span class="captionConstruct">Дополнительно</span>
                                <div class="added">
                                    <textarea name="additionallyConstruct" placeholder="Ваши пожелания. Например, финишная обработка клинка."></textarea>
                                </div>
                                <span id="sum"></span>
                                <span id="sumNewOld"></span>
                                <span id="sumNew">цена:</span>
                                <button id="constructButton" name="send" type="button" class="button" onclick="
                                @if (empty(Session::get('userId')))
                                        orderShow(1); return false;
                                @else
                                        orderShow(3); return false;
                                @endif
                                        ">оформить</button>
                            </section>
                        </form>
                    </div>
                    @if ($mobile == 1)
                        <div id="constructorPhone" class="constructorPhone">
                            <div class="stageConstructPhone" id="buttonBlade" onclick="showBladePhone();">клинок</div>
                            <div class="stageConstructPhone" id="buttonSteel" onclick="showSteelPhone();">сталь</div>
                            <div class="stageConstructPhone" id="buttonBolster" onclick="showBolsterPhone();">больстер</div>
                            <div class="stageConstructPhone" id="buttonHandle" onclick="showHandlePhone();">ручка</div>
                            <div class="stageConstructPhone" id="buttonMaterial" onclick="showMaterialPhone();">материал</div>
                        </div>
                        <form id="mobileConstructForm">
                            <input type="hidden" name="oprosSale" value="0">
                            <input type="hidden" name="mobile" value="1">
                            <div class="boxes">
                                <div class="up_line_phone" id="bladePhone">
                                    <ul class='line_phone'>
                                        @foreach ($typeOfBlades as $blade)
                                            <li class="bladeTemplateRow">
                                                <label id="phone-blade_construct_{{ $blade->id }}">
                                                    <input type="radio" name="blade_type_select" value="{{ $blade->id }}" onchange="getPath({{ $blade->id }},1)">
                                                    <span>{{ $blade->name }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="80%" viewbox="0 0 380 130" >
                                                        <path fill="none"  stroke-width="0.6" stroke="#000000" d="{{ $blade->path }}" vector-effect="non-scaling-stroke" transform="translate(-310 0)">
                                                    </svg>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="up_line_phone" id="steelPhone">
                                    <ul class='line_phone'>
                                        @foreach ($typeOfSteels as $steel)
                                            <li class="steelTemplateRow">
                                                <label id="phone-steel_construct_{{ $steel->id }}">
                                                    <input type="radio" name="steel_type_select" value="{{ $steel->id }}" onchange="setTexture({{ $steel->id }} ,1)">
                                                    <span>{{ $steel->name }}</span>
                                                </label>
                                                <a id="info_steel_{{ $steel->id }}" href="#" class="info_link" onclick="showDescription({{ $steel->id }} ,1); return false;">!</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="up_line_phone" id="bolsterPhone">
                                    <ul class='line_phone'>
                                        @foreach ($typeOfBolsters as $bolster)
                                            <li class="bolsterTemplateRow">
                                                <label id="phone-bolster_construct_{{ $bolster->id }}">
                                                    <input type="radio" name="bolster_type_select" value="{{ $bolster->id }}" onchange="getPath({{ $bolster->id }},2)">
                                                    <span>{{ $bolster->name }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 380 145" >
                                                        <path fill="none"  stroke-width="0.6" stroke="#000000" d="{{ $bolster->path }}" vector-effect="non-scaling-stroke" transform="translate(-530 0)"/>
                                                    </svg>
                                                </label>
                                                @if ($bolster->id == 5)
                                                    <a id="info_bolster_{{ $bolster->id }}" href="#" class="info_link" onclick="showDescription({{ $bolster->id }},3); return false;">!</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="up_line_phone" id="handlePhone">
                                    <ul class='line_phone'>
                                        @foreach ($typeOfHandles as $handle)
                                            <li class="handleTemplateRow">
                                                <label id="phone-handle_construct_{{ $handle->id }}">
                                                    <input type="radio" name="handle_type_select" value="{{ $handle->id }}" data-path="{{ $handle->path }}" onchange="getPath({{ $handle->id }},3)">
                                                    <span>{{ $handle->name }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 380 160" >
                                                        <path fill="none"  stroke-width="0.6" stroke="#000000" d="{{ $handle->path }}" vector-effect="non-scaling-stroke" transform="translate(-610 0)"/>
                                                    </svg>
                                                </label>
                                            <!--<a id="info_handle_{{ $handle->id }}" href="#" class="info_link" onclick="showDescription({{ $handle->id }},4); return false;">!</a>-->
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="up_line_phone" id="materialPhone">
                                    <ul class='line_phone'>
                                        @foreach ($typeOfHandleMaterials as $handleMaterial)
                                            <li class="phone-handleMaterialTemplateRow @if($handleMaterial->nabor == 1) nabor @else notNabor @endif">
                                                <label id="phone-handle_material_construct_{{ $handleMaterial->id }}">
                                                    <input type="radio" name="handle_material_type_select" value="{{ $handleMaterial->id }}" data-path="{{ $handleMaterial->path }}" onchange="setTexture({{ $handleMaterial->id }},2)" onclick="setted();"   />
                                                    <span>{{ $handleMaterial->name }}</span>
                                                </label>
                                            <!--  <a id="info_handle_{{ $handleMaterial->id }}" href="#" class="info_link" onclick="showDescription({{ $handleMaterial->id }},5);">!</a> -->
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="additionsPhone clearfix">
                                <section class="leftPhoneAddition">
                                    <span>Доп. на клинок</span>
                                    @foreach ($additionOfBlade as $addition)
                                        <div class="additionBlade @if($addition->damask == 2) notForDamask @endif @if ($addition->id == 3) skvoznoi @endif" >
                                            <label class="preventLabel">
                                                <input class="additionInput" data-price="{{$addition->price}}" id="additionBladePhone_{{$addition->id}}" type="checkbox" name="additionPhone_{{$addition->id}}"  onchange="@if($addition->image) setAdditionTexture( '{{$addition->image}}', 'additionPhone_{{$addition->id}}');@endif showNewSum();">
                                                <label class="checkConditions helpClass" for="additionBladePhone_{{$addition->id}}">
                                                </label>
                                                <span class="nameOfAddition">{{$addition->name}}</span>
                                            </label>
                                            <a href="#" class="info_link" onclick="showDescription({{$addition->id}}, 6); return false;">!</a>
                                        </div>
                                    @endforeach
                                </section>
                                <section class="rightPhoneAddition">
                                    <span>Спуски</span>
                                    @foreach ($spuski as $spusk)
                                        <div class="additionBlade spuskAddition @if($spusk->id == 1) firstSpusk @endif">
                                            <label class="preventLabel">
                                                <input id="spuskPhone_{{$spusk->id}}" type="radio" name="spuskPhone" @if($spusk->id == 1) checked @endif value="{{$spusk->id}}">
                                                <label class="Radio helpClass" for="spuskPhone_{{$spusk->id}}"></label>
                                                <span class="nameOfAddition">{{$spusk->name}}</span>
                                                <a href="#" class="info_link" onclick="showDescription({{$spusk->id}}, 7); return false;">!</a>
                                            </label>
                                        </div>
                                    @endforeach
                                </section>
                            </div>
                        </form>
                    @endif
                    <span class="knife_preview_span">Схематичный вид вашего ножа</span>
                    <div class="test_svg clearfix svg1740 containerForSvgMain" id="main_test_svg">
                        <svg id="svg" xmlns="http://www.w3.org/2000/svg" width="100%" viewbox="0 0 1800 502" >
                            <g id="bladePaths" transform="translate(600 70)">
                                <g id="blade_wrap_svg">
                                    <path id="blade_svg" d="" vector-effect="non-scaling-stroke" stroke ="#000000" stroke-width="0.75" fill="#000000"></path>
                                </g>
                                <path id="fixBlade" vector-effect="non-scaling-stroke"  fill="#f2efef" d="M645,119 L630,119 Q597,119 591,139 L591, 300 L645,300z"></path>
                                <g id="bolster_wrap_svg">
                                    <path id="bolster_svg" d="" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke" data-width="5"  fill="#000000"></path>
                                </g>
                                <g id="handle_wrap_svg">
                                    <path class="" id="handle_svg" d="" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke"  fill="#000000"></path>
                                    <path class="klepka" id="klepka" d="M640,40" stroke ="#000000" stroke-width="0.75" vector-effect="non-scaling-stroke"  fill="url(#patternKlepka)"></path>
                                </g>
                            </g>
                            <g id="lineikaLines" transform="translate(0 62)">
                                {{ $c = 0 }}
                                @for ($i = 0; $i < 2400; $i+=40)
                                    <line x1="{{ $i }}" y1="325" x2="{{ $i }}" y2="305" stroke="#000000" stroke-width="2"/>
                                    @for ($j = 0; $j < 10; $j++)
                                        <line x1="{{ $i + $j * 4 }}" y1="325" x2="{{ $i + $j * 4 }}" y2="315" stroke="#000000" stroke-width="1"/>
                                    @endfor
                                    <text x="{{ $i }}" y="300">{{ $c }}</text>
                                    {{ $c++ }}
                                @endfor
                            </g>
                            <defs>
                                <pattern id="patternHandle" viewbox="0 0 1800 487"  width="867.5" height="408"  patternUnits="userSpaceOnUse" x="-285" y="-55">
                                    <image id="handleImg" href="" xlink:href="" width="830" height="250" />
                                </pattern>
                                <pattern id="patternBolster" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse"  x="-63" y="-58" width="680" height="300">
                                    <image id="bolsterImg" width="930" height="400" href="" xlink:href=""/>
                                </pattern>
                                <pattern id="patternSteel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse" x="-495" y="-27" width="815" height="285">
                                    <image id="steelImg" width="720" height="310" href="" xlink:href=""/>
                                </pattern>
                                <pattern id="patternKlepka" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 487" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"  width="867.5" height="408"  patternUnits="userSpaceOnUse" x="-285" y="-55">>
                                    <image id="klepkaImg" width="830" height="250" href="{{ asset('img/patternsConstruct') }}/klepka.jpg?{{VERSION}}" xlink:href="{{ asset('img/patternsConstruct') }}/klepka.jpg?{{VERSION}}"/>
                                </pattern>
                            </defs>
                        </svg>
                        <div id="note">
                            Обратите внимание, что размер см на линейке может не совпадать с фактическим размером см на вашем экране
                        </div>
                        @if($mobile == 1)
                            <div class="phoneLengths">
                                <div class="slide_block clearfix">
                                    <span class="slideName">Длина:</span>
                                    <span id="phone-contentSliderBladeLength" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="phone-sliderBladeLength"></div>
                                <input type="text" id="phone-length_blade_construct" value="130" name="blade_length_select"/>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Ширина:</span>
                                    <span id="phone-contentSliderBladeHeight" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="phone-sliderBladeHeight"></div>
                                <input type="text" id="phone-height_blade_construct" value="29" name="blade_height_select"/>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Обух:</span>
                                    <span id="phone-contentSliderButtWidth" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="phone-sliderButtWidth"></div>
                                <input type="text" id="phone-butt_width_construct" value="3.5" name="butt_width_select"/>
                                <div class="slide_block clearfix">
                                    <span class="slideName">Ручка:</span>
                                    <span id="phone-contentSliderHandle" class="slideRes"></span>
                                </div>
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="phone-sliderHandle"></div>
                                <input type="text" id="phone-length_handle_construct" value="120" name="handle_length_select"/>
                            </div>
                            <span id="sumPhone" style="font-weight:bold;color:grey;"></span>
                            <span id="sumNewOldPhone"></span>
                            <span id="sumNewPhone" style="font-weight:bold;font-size: 22px;">цена:</span>
                            <textarea id="phoneAdditionText" name="additionallyConstruct" placeholder="Ваши пожелания. Например, финишная обработка клинка."></textarea>
                            <button id="constructButtonPhone" name="send" type="button" class="button" onclick="
                            @if (empty(Session::get('userId')))
                                    orderShow(1); return false;
                            @else
                                    orderShow(3); return false;
                            @endif
                                    ">оформить</button>
                        @endif
                    </div>
                </section>
                <section id="our_works"  class="content clearfix">
                    <h3>Примеры работ</h3>
                    <section class="exampleForge clearfix">
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
                    </section><!--
                <section class="exampleForge clearfix">
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
                </section> -->
                    <section class="exampleForge clearfix">
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
                    </section>
                    <section class="exampleForge clearfix">
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
                    </section>
                    <a id="toAlbum" href="/album">Посмотреть все ножи</a>
                </section>
                <section id="how_we_make_knife"  class="content">
                    <h3>Как мы делаем ваш нож</h3>
                    <section class="switchDexcriptionHow clearfix">
                        <div id="toHowVideo" class="leftSection">
                        <span class="makingBtn pushedMaking">
                            <span class="makingCaption">Посмотреть</span>
                        </span>
                        </div>
                        <div id="toHowText" class="rightSection">
                        <span class="makingBtn">
                            <span class="makingCaption">Прочитать</span>
                        </span>
                        </div>
                    </section>
                    <section id="videoHow" style="display: block;">
                        <iframe src="https://player.vimeo.com/video/285821002?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </section>
                    <section id="textHow" style="display: none;">
                        <section class="stage_of_create">
                            <img src="{{ asset('img/chertezh6.jpg') }}?{{VERSION}}" width="550" height="280" alt="Эскиз ножа" title="Эскиз ножа">
                            <section>
                                <h4>1. Принимаем заказ</h4>
                                <p>Вы подбираете нож по нашему конструктору либо же отправляете нам эскиз, фото, словесное описание ножа, который бы хотели заказать. </p>
                            </section>
                        </section>
                        <section class="stage_of_create clearfix">
                            <img src="{{ asset('img/kovka3.jpg') }}?{{VERSION}}"  width="550" height="280" alt="Ковка ножа" title="Ковка ножа">
                            <section>
                                <h4>2. Разогреваем и куем</h4>
                                <p>Из выбранной вами стали мастер отковывает заготовку будущего клинка.</p>
                            </section>
                        </section>
                        <section class="stage_of_create clearfix">
                            <img src="{{ asset('img/zakalMy.jpg') }}?{{VERSION}}"  width="550" height="280" alt="Закалка ножаc" title="Закалка ножа">
                            <section>
                                <h4>3. Слесарим и закаливаем нож</h4>
                                <p>После ковки следует слесарная обработка с использованием шлифовальных лент и наждаков. Далее отправляем нож в печь для закалки.</p>
                            </section>
                        </section>
                        <section class="stage_of_create clearfix">
                            <img src="{{ asset('img/polir.jpg') }}?{{VERSION}}"  width="550" height="280" alt="Полировка ножа" title="Полировка ножа">
                            <section>
                                <h4>4. Полируем и точим</h4>
                                <p>После закаливания финальным штрихом в обработке клинка будет полировка и заточка.</p>
                            </section>
                        </section>
                        <section class="stage_of_create clearfix">
                            <img src="{{ asset('img/posilka.jpg') }}?{{VERSION}}"  width="550" height="280" alt="готовый нож" title="готовый нож">
                            <section>
                                <h4>5. Делаем рукоять и упаковываем</h4>
                                <p>Изготавливаем рукоять из выбранного вами материала с дальнейшей подгонкой к клинку. Упаковываем изделие. Ваш нож готов!</p>
                            </section>
                        </section>
                    </section>
                </section>
                <style type="text/css">
                    #individual #form_order,
                    #individual .innerContainer,
                    #individual h3{
                        display: none;
                    }
                </style>
                <section  id="individual" class="action clearfix">
                    <h3>Заказать индивидуальный нож</h3>
                    <div class="innerContainer">
                        <p class="aboutForm">
                            Прикрепите чертёж, фотографию, укажите словесное описание желаемого ножа. Мы перезвоним вам и сообщим цену и сроки выполнения заказа.
                        </p>
                    </div>
                    <form id="form_order" method="POST" enctype="multipart/form-data" class="order clearfix @if (!empty(Session::get('userId'))) autorized
                @endif" >
                        <div class="form_left">
                            @if (empty(Session::get('userId')))
                                <label class="clearfix">
                                    <input name="name" type="text" value="" spellcheck="false" autocomplete="off" placeholder="Ваше имя">
                                    <div class="necessary">Это обязательное поле</div>
                                </label>
                                <label class="clearfix">
                                    <input class="phone" name="phone" type="text" value="" autocomplete="off" placeholder="Ваш телефон" onclick="focusPhone('#form_order'); return false;">
                                    <div class="necessary">Это обязательное поле</div>
                                </label>
                                <label class="clearfix">
                                    <input class="email" name="email" type="text" value="" autocomplete="off" placeholder="Ваш email">
                                    <div class="necessary" id="noteIndividualEmail">Это обязательное поле</div>
                                </label>
                                <div class="clearfix">
                                    <div class="zonesBlock">
                                        <img src="{{ asset('img') }}/ZoneAll.png?{{VERSION}}" width="285" height="160" alt="Карта России" title="Карта России">
                                        <input id="indZone1" class="" name="zoneInd" type="radio" value="1">
                                        <label for="indZone1" class="Radio fstZone"></label>
                                        <input id="indZone2" class="" name="zoneInd" type="radio" value="2">
                                        <label for="indZone2" class="Radio secondZone"></label>
                                        <input id="indZone3" class=""  name="zoneInd" type="radio" value="3">
                                        <label for="indZone3" class="Radio thirdZone"></label>
                                        <div class="noteZone necessary">Укажите зону проживания</div>
                                    </div>
                                </div>
                            @endif
                            <label class="clearfix">
                                <textarea name="description" value="" spellcheck="false" placeholder="Укажите материалы рукояти, клинка, больстера, а также их размеры (если они не указаны на приложенном фото)" autocomplete="off"></textarea>
                                <div class="necessary">Добавьте описание или фото</div>
                            </label>
                        </div>
                        <div class="form_right">
                            <div class="file">
                                <label class="file_upload">
                                    <span class="button">Выбрать картинку</span>
                                    <input id="file" type="file" name="file" accept="image/*">
                                </label>
                                <div class="row">
                                    <div id="output">
                                        <div class="close">
                                            <div id="image_close" class="window_close">Закрыть</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (empty(Session::get('userId')))
                                <div class="captchaBlock">
                                    <div class="forCaptchaBlock">
                                        @captcha
                                        <svg enable-background="new 0 0 32 32" height="32px" id="Refresh" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path class="refreshPath" d="M25.032,26.16c2.884-2.883,4.184-6.74,3.928-10.51c-1.511,0.013-3.021,0.021-4.531,0.034  c0.254,2.599-0.603,5.287-2.594,7.277c-3.535,3.533-9.263,3.533-12.796,0c-3.534-3.533-3.534-9.26,0-12.794  c3.015-3.016,7.625-3.446,11.109-1.314c-1.181,1.167-2.57,2.549-2.57,2.549c-1,1.062,0.016,1.766,0.69,1.77h8.828  c0.338,0,0.611-0.274,0.612-0.612V3.804c0.041-0.825-0.865-1.591-1.756-0.7c0,0-1.495,1.48-2.533,2.509  C18.112,1.736,10.634,2.175,5.841,6.967c-5.3,5.3-5.3,13.892,0,19.193C11.141,31.459,19.733,31.459,25.032,26.16z" fill="#555555" /></svg>
                                    </div>
                                    <div class="wrapNecessaryCaptcha">
                                        <input type="text" placeholder="Символы с картинки" name="captcha" autocomplete="off">
                                        <div class="necessary">Это обязательное поле</div>
                                    </div>
                                    </label>
                                </div>
                                <div class="conditionCheck">
                                    <input id="acception" type="checkbox" name="conditions" checked="checked">
                                    <label class="checkConditions" for="acception"></label>
                                    <span class="textForCheckbox">
                                    <span>Я прочитал(а) и принимаю</span> <a href="/conditions" target="_blank">Условия использования</a>
                                </span>
                                </div>
                            @endif
                            <button name="send" type="button" class="button" onclick="sendImage(); return false;">отправить</button>
                        </div>
                    </form>
                    <div class="link_block clearfix">
                        <a href="https://vk.com/rusvyatich" class="button vk_link" target="_blank">подписаться на группу вк</a>
                        <a href="https://www.instagram.com/rusvyatich/" class="button instagram_link" target="_blank">подписаться на инстаграм</a>
                    </div>
                </section>
                <section id="our_products" class="content clearfix">
                    <h3>Наши изделия</h3>
                    <div class="product_block clearfix">
                        @if ($products->isEmpty())
                            <span class="emptyProducts">К сожалению, сейчас товар отсутствует</span>
                        @endif
                        @foreach ($products as $product)
                            <div id="_{{ $product->id }}" class="product">
                                <img src="{{ asset('img/imgStorageMin') }}/{{ $product->image }}?{{VERSION}}" width="340" height="190" alt="Нож {{ $product->name }} (кузница Вятич)" title="Нож {{ $product->name }} (кузница Вятич)">
                                <div class="product_description">
                                    <span class="nameKnife" id="nameKnife_{{$product->id}}">{{ $product->name }}  ({{ $product->steel }})</span>
                                    <dl class="dl-inline clearfix">
                                        <dt class="dt-dotted">
                                            <span>Сталь</span>
                                        </dt>
                                        <dd id="steel__{{ $product->id }}">{{ $product->steel }}</dd>
                                    </dl>
                                    <dl class="dl-inline clearfix">
                                        <dt class="dt-dotted">
                                            <span>Длина клинка</span>
                                        </dt>
                                        <dd id="length__{{ $product->id }}">{{ $product->blade_length }} мм</dd>
                                    </dl>
                                    <dl class="dl-inline clearfix">
                                        <dt class="dt-dotted">
                                            <span>Ширина клинка</span>
                                        </dt>
                                        <dd id="width__{{ $product->id }}">{{ $product->blade_width }} мм</dd>
                                    </dl>
                                    <dl class="dl-inline clearfix">
                                        <dt class="dt-dotted">
                                            <span>Толщина обуха</span>
                                        </dt>
                                        <dd id="thickness__{{ $product->id }}">{{ $product->blade_thickness }} мм</dd>
                                    </dl>
                                    <span id="cost__{{ $product->id }}" class="cost">Цена: {{ $product->price }} р.</span>
                                </div>
                                @if($product->source == "individual")
                                    <a class="abutton button read_more" href="/shop/knife{{ $product->id }}">Подробнее</a>
                                @else
                                    <a class="abutton button read_more" href="/shop/serialKnife{{ $product->id }}">Подробнее</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <a id="toShop" href="/shop">Посмотреть все</a>
                </section>
                <!--  <section id="our_advantages"  class="content">
                     <h3>Наши преимущества</h3>
                     <ul class="list">
                         <li>вы получаете уникальный нож, сделанный вручную</li>
                         <li>возможность контактировать напрямую с мастером</li>
                         <li>сроки изготовления — от 3 до 7 дней</li>
                     </ul>
                 </section> -->
            <!--<section id="about_us" class="content">
                <h3>О нас</h3>
                <p style="text-align: justify;">Мы команда кузнецов из древнерусского города Суздаль</p>
                <img id="weAre" src="{{ asset('img/we.jpg') }}" alt="Суздальские кузнецы" title="Суздальские кузнецы">
            </section>-->
                <!--  <section id="our_guarantees"  class="content">
                     <h3>Наши гарантии</h3>
                     <ul class="list">
                         <li>при просрочке, что бывает крайне редко, за каждый день задержки — скидка в размере 5%</li>
                         <li>все ножи имеют пожизненную гарантию от производственных дефектов</li>
                     </ul>
                 </section> -->
                <section id="delivery"  class="content">
                    <h3>Доставка</h3>
                    <ul class="list">
                        @foreach ($typeOfSends as $typeOfSend)
                            <li>{{$typeOfSend->description}} ({{$typeOfSend->price}} р.)</li>
                        @endforeach
                    </ul>
                </section>
            <!-- <section id="faq" class="content">
                <h3>Частые вопросы</h3>
                <section class="faqSections">
                    <div>Изготовление</div>
                    <div>Оплата и доставка</div>
                    <div>Возврат</div>
                </section>
                <ul id="basics" class="list">
                    <li>
                        <span class="question">Куда обратиться если хочу вернуть заказ?</span>
                        <div class="faq_answer">
                            Напишите своему оператору в <a href="/home">личном кабинете</a>, либо позвоните по номеру {{COMPANY_PHONE}}
                    </div>
                </li>
                <li>
                    <span class="question">Могу ли я вернуть нож сделанный на заказ?</span>
                    <div class="faq_answer">
                        Да, вы можете отказаться от ножа и вернуть за него деньги (если уже были заплачены) для ножей стоимостью менее {{WITHOUT_PAY}} руб. в течение недели после получения.
                        </div>
                    </li>
                    <li>
                        <span class="question">Можно ли вернуть деньги за нож, сделанный на заказ, стоимостью более {{WITHOUT_PAY}} руб?</span>
                        <div class="faq_answer">
                            Возврат можно оформить в течение  2-х недель, но в данном случае залог за нож (50% от стоимости) будет удержан в нашу пользу.
                        </div>
                    </li>
                    <li>
                        <span class="question">Как возвращаются ножи из вашего <a href="/shop" target="_blank">магазина</a>?</span>
                        <div class="faq_answer">
                            Эти ножи можно вернуть в течение недели после получения.
                        </div>
                    </li>
                    <li>
                        <span class="question">Будет ли произведен возврат средств за обратную доставку?</span>
                        <div class="faq_answer">
                            Нет, средства за обратную доставку платите вы. Вы можете отказаться от ножа после отправки фото в данном случае платить ничего не прийдется.
                        </div>
                    </li>
                    <li>
                        <span class="question">Как мне можно оплатить свой заказ?</span>
                        <div class="faq_answer">
                            После согласования заказа, вам будет предоставлен номер карты для оплаты вашего заказа
                        </div>
                    </li>
                    <li>
                        <span class="question">Как осуществляется доставка?</span>
                        <div class="faq_answer">
                            Доставка осуществляется выбранным вами способом из предложенных при оформлении заказа.
                        </div>
                    </li>
                </ul>
            </section> -->
            </div>
    </main>
    @include('handleOldToken')
    @include('layouts.footerBig')
    @include('layouts.orderPopup')
    @include('layouts.opros')
    @if (Session::has('orderUnpayed'))
        <div class="toPay" onclick="payOrder({{Session::get('orderUnpayed')}}); return false;">
            <span>У вас есть неоплаченный заказ</span>
        </div>
    @endif
    @include('layouts.cart')
    <div id="choose_construct_way" class="success">
        <div id="alert_way" class="alert">
            <span class="captionAlert">Чтобы данная конфигурация не была Холодным Оружием выберите один из путей</span>
            <button class="button buttonWay" onclick="lengthLessThan180();">Длина клинка меньше 180 мм</button>
            <button class="button buttonWay" onclick="heightMoreThan40();">Высота клинка больше 40 мм</button>
        </div>
    </div>
    <div id="alertAboutRange">
        <span style="font-weight: bold; margin-bottom: 7px; display: block;">По <a href="/coldArms" target="_blank" style="color: black;">законодательству РФ</a> </span>
        <dl id="bladeLengthAlert" class="dl-inline clearfix">
            <dt class="dt-dotted">
                <span>Длина клинка</span>
            </dt>
            <dd id="rangeBladeLength">80 — 230мм</dd>
        </dl>
        <dl id="bladeHeightAlert" class="dl-inline clearfix">
            <dt class="dt-dotted">
                <span>Высота клинка</span>
            </dt>
            <dd id="rangeBladeHeight">20 — 50мм</dd>
        </dl>
        <dl id="buttWidthAlert" class="dl-inline clearfix">
            <dt class="dt-dotted">
                <span>Толщина обуха</span>
            </dt>
            <dd id="rangeButtWidth">2 — 6мм</dd>
        </dl>

    </div>
    </body>
    <script>
        // "global" vars
        var patternPath = "{{ asset('img/patternsConstruct') }}/";
        var pathToImageDescription = "{{ asset('img/additions') }}/";
        var MIN_BLADE_LENGTH = 100;
        var MIN_BLADE_HEIGHT = 22;
        var MIN_BUTT_WIDTH = 2;
        var MIN_HANDLE_LENGTH = 110;
        var MAX_BLADE_LENGTH = 230;
        var MAX_BLADE_HEIGHT = 50;
        var MAX_BUTT_WIDTH = 5;
        var MAX_HANDLE_LENGTH = 130;
        var old_butt_width = 3.5;
        var bolster_restrict = 0;
        var handle_restrict = 0;
        var PAY_WITHOUT = {{WITHOUT_PAY}};
        var PERSENT = {{PERSENT}};
        var VERSION = {{VERSION}};
        var idKnife = false;
        var skvozByUser = false;
        var mobile = {{$mobile}};
    </script>
    <script src="{{ asset('js/jquery.min.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.nicescroll.min.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/device.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/main.js') }}?{{VERSION}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.tmpl.js') }}?{{VERSION}}" type="text/javascript"></script>
    <!-- <script src="{{ asset('js/lazyload.js') }}?{{VERSION}}" type="text/javascript"></script> -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @if($mobile != 1)
        <script type="text/javascript" src="https://vk.com/js/api/openapi.js?159"></script>

        <!-- VK Widget -->
        <div id="vk_community_messages"></div>
        <script type="text/javascript">
            VK.Widgets.CommunityMessages("vk_community_messages", 74873609, {disableExpandChatSound: "1",disableNewMessagesSound: "1",disableButtonTooltip: "1"});
        </script>
    @endif
    <script type="text/javascript">
        function firstImpressionChanger() {
            if ($(window).width() <= '1000'){
                $('.main_image img').height($(window).height() - $('.header').outerHeight());
                $('.main_image img').width((1920*$(window).height() - $('.header').outerHeight())/1080);
                $('.first_impression').height($(window).height()+40);
                $('.main_image img').attr('src',"{{ asset('img') }}//knifes/forest333.jpg?{{VERSION}}");
                $('.main_image').height($(window).height() - $('.header').outerHeight() - 48);
                $('.main_titles').addClass('phn');
            } else {
                $('.first_impression').height(($(window).width()/4)+30);
                $('.main_image img').height('auto');
                $('.main_image img').width('100%');
                $('.main_image img').attr('src',"{{ asset('img') }}//knifes/forest338.jpg?{{VERSION}}");
                $('.main_image').height(($(window).width()/4)+30);
                $('.main_titles').removeClass('phn');
            }

        }
        // $('.main_image').height($(window).height() - $('.header').outerHeight() - 48);
        if( $(window).width()/$(window).height() <1.77){
            //     $('.main_image img').height($(window).height() - $('.header').outerHeight());
            //     $('.main_image img').width((1920*$(window).height() - $('.header').outerHeight())/1080);
            // } else {
            //     $('.main_image img').height('auto');
            //     $('.main_image img').width('100%');
            // }
            if ($(window).width() <= '550'){
                $('.timeCall').text('c '+{{START_WORK_DAYSHIFT}}+' до '+{{END_WORK_DAYSHIFT}});
                //$('.main_image img').attr('src',"{{ asset('img') }}/iron.jpg");
            } else {
                $('.timeCall').text('c '+{{START_WORK_DAYSHIFT}}+':00 до '+{{END_WORK_DAYSHIFT}}+':00');
            }
        }
        firstImpressionChanger();
        $().ready(function(){
            $('#zz').on('blur keyup paste input',function(){
                $('#zzz').attr('d', $(this).val());
            });
            // lazyload();
            $('#form_constructor').trigger('reset');
            chooseFirstsConstruct(); //выбор ножа по дефолту

            /*Замена главной картинки при ширине меньше 500*/
            if ($(window).width() <= '1000'){
                $('.main_image img').height($(window).height() - $('.header').outerHeight());
                $('.main_image img').width((1920*$(window).height() - $('.header').outerHeight())/1080);
                $('.first_impression').height($(window).height()+40);
                $('.main_image img').attr('src',"{{ asset('img') }}//knifes/forest333.jpg?{{VERSION}}");
                $('.main_image').height($(window).height() - $('.header').outerHeight() - 48);
                $('.main_titles').addClass('phn');
            } else {
                $('.first_impression').height(($(window).width()/4)+30);
                $('.main_image img').attr('src',"{{ asset('img') }}//knifes/forest338.jpg?{{VERSION}}");
                $('.main_titles').removeClass('phn');
                $('.main_image').height(($(window).width()/4)+30);
            }
            if(isIE || isEdge) {
                $('.edgeFix').css('stroke-width', '12');
                $('.fixKuznEdge').css('stroke-width', '3.5');
            }
            if (isIE) $('body').addClass('ie_body');
            /*Скрытие меню после клика на заголовки меню*/
            $('.menu a').click(function(){
                showMenu();
            });
            /*Скрытие меню по клику вне*/
            $(document).mouseup(function (e){
                var div = $(".menu");
                if(div.is(':visible')){
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('.open_menu').is(e.target)) {
                        showMenu();
                    }
                }
                var div = $('#bladePhone');
                if(div.is(':visible')){
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#buttonBlade').is(e.target) && $('#aboutPartWrap').css('display')!='block') {
                        $('#bladePhone').css('display', 'none');
                    }
                }
                var div = $('#steelPhone');
                if(div.is(':visible')){
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#buttonSteel').is(e.target) && $('#aboutPartWrap').css('display')!='block') {
                        $('#steelPhone').css('display', 'none');
                    }
                }
                var div = $('#bolsterPhone');
                if(div.is(':visible')){
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#buttonBolster').is(e.target) && $('#aboutPartWrap').css('display')!='block') {
                        $('#bolsterPhone').css('display', 'none');
                    }
                }
                var div = $('#handlePhone');
                if(div.is(':visible')){
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#buttonHandle').is(e.target) && $('#aboutPartWrap').css('display')!='block') {
                        $('#handlePhone').css('display', 'none');
                    }
                }
                var div = $('#materialPhone');
                if(div.is(':visible')){
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#buttonMaterial').is(e.target) && $('#aboutPartWrap').css('display')!='block') {
                        $('#materialPhone').css('display', 'none');
                    }
                }
            });
            /*Аналогичные действия esc-ейпу от клика вне*/
            $(document).mouseup(function (e) {
                if ($('body').hasClass('unclicked') || $('#error_message').is(':visible') || $('#note_message').is(':visible')) return false;
                if (e.which != 1) return false;
                var div = $('#alertAboutRange');
                if (div.is(':visible') && !div.is(e.target) && div.has(e.target).length === 0) {
                    div.css('display', 'none');
                }
                if ($('#wrap_opros').is(':visible')){
                    div = $('#way_opros');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        $('#form_consult_close').click();
                    }
                }
                if ($('#success_message').is(':visible')){
                    div = $('#alert_message');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        $('#close_alert').click();
                    }
                }
                if ($('#wrap_for_product').is(':visible')){
                    div=$('#window');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        closeMainImg();
                    }
                }
                if ($('#aboutPartWrap').is(':visible')){
                    div = $('#aboutPart');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        closeAboutPart();
                        if ($('#wrap_construct_order').is(':visible')) {
                            hideMainScroll();
                        }
                    }
                    return;
                }
                if ($('#success_after_add_to_cart').is(':visible')){
                    div=$('#alert_after_add_to_cart');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        $('#return_to_buy').click();
                    }
                } else if ($('#wrap_cart').is(':visible') && !$('#wrap_construct_order').is(':visible')){
                    div=$('#cart');
                    if (!div.is(e.target) && div.has(e.target).length === 0) {
                        closeCart();
                    }
                } else if ($('#wrap_construct_order').is(':visible') && $('#stage5').is(':hidden')){
                    div=$('#way_to_buy');
                    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('#stage4 .next').hasClass('button_pushed')) {
                        @if(!Session::has('userId'))closeConstructOrderConfirmation(); @else closeConstructOrder(); @endif
                    }
                }
            });

            /*Скрол для конструктора*/
            if(device.desktop()) {
                $('.up_slid_steel').niceScroll('.down_slid_steel',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:10, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:15, bouncescroll: false});
                $('.up_slid_handle').niceScroll('.down_slid_handle',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:10, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
                $('.up_slid_handle_material').niceScroll('.down_slid_handle_material',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:10, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
                $('.up_slid_blade').niceScroll('.down_slid_blade',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:10, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
                $('.up_slid_bolster').niceScroll('.down_slid_bolster',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:10, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
                $('.wrap_slider').niceScroll('.cart_slider',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
                $('.menu').niceScroll('.menu ul',{cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:10, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false, horizrailenabled: false});
                $('#aboutPartScrollable').niceScroll({cursorcolor:'#8a8484',cursoropacitymin:'1', cursorwidth:6, cursorborder:'none', cursorborderradius:0,background: "#e8e8e8",mousescrollstep:25, bouncescroll: false});
            } else {
                $('body').addClass('mobileBody');
            }

            /*Во всю ширину если нету скрола*/
            function niceScrollWidthBind(){
                $('.types_slider').each(function(i,elem) {
                    if(!hasVerticalScroll(this)){
                        $(this).children('.line_of_types').css('width','100%');
                    }else{
                        $(this).children('.line_of_types').css('width','calc(100% - 8px');
                    };
                    if(!device.desktop()) {
                        $(this).children('.line_of_types').css('width','100%');
                    }
                });
            };
            niceScrollWidthBind();
            /*При фокусе на ипуте изменение положения для места под вирт. клавиатуру*/
            $('input').on('focus',function(){
                if(!device.desktop()) {
                    document.getElementById('wrapForScrollOrder').scrollTop = 0;
                    var topPos=$(this).offset().top;
                    var topFormPos=$('#form_construct_order').offset().top;
                    document.getElementById('wrapForScrollOrder').scrollTop = topPos-topFormPos+60;
                }
            });
            var $htmlOrBody = $('html, body'); // scrollTop works on <body> for some browsers, <html> for others
            var scrollTopPadding =80;

            $('input').on("change keyup input click", function(){
                $(this).removeClass('red');
                $(this).removeClass('notCheckedConditions');
                $(this).removeClass('unchooz');
            });
            $('input[type=password]').keydown(function(event){
                if(event.keyCode===13) {
                    event.preventDefault();
                    if ($(this).val() != '') {
                        $('#stage5 .onlyNext').click();
                    }
                    return;
                }
            });
            $('#form_order input').keydown(function(event){
                if(event.keyCode===13) {
                    event.preventDefault();
                    sendImage();
                    return;
                }
            });
            $('#formConsult input').keydown(function(event){
                if(event.keyCode===13) {
                    event.preventDefault();
                    sendConsultConstruct();
                    return;
                }
            });


            $('#form_order .forCaptchaBlock svg').click(function(){
                $('#form_order .forCaptchaBlock img').click();
            })
            $('#wrap_construct_order .forCaptchaBlock svg').click(function(){
                $('#wrap_construct_order .forCaptchaBlock img').click();
            });
            $('#stage1 input ,#stage2 input ,#stage3 input, #stage4 input[name=captcha]').keydown(function(event){
                if(event.keyCode===13) {
                    var val = $(this).val();
                    if($(this).attr('name') == 'phone') {
                        if (val.match(/[0-9]/g).length <11) {
                            $(this).val('');
                        }
                    }
                    event.preventDefault();
                    $(this).parents('.stageOfOrder').children('.next').click();
                    return;
                }
            });

            //Сортировка конструктора
            $('input[name=sortConstruct]').on("change keyup input click", function(){
                sortConstruct($('input[name=sortConstruct]:checked').val());
            });
            $('input[type=radio]').on("change keyup input click", function(){
                $('.typeSend').removeClass('red');
            });
            $('input[name=conditions]').on("change keyup input click", function(){
                $('.checkConditions').removeClass('notCheckedConditions');
            });
            $('input[name=zone]').on("change keyup input click", function(){
                $('.zonesBlock').removeClass('unchoosenZonePopup');
            });
            $('input[name=zoneInd]').on("change keyup input click", function(){
                $('.zonesBlock').removeClass('unchoosenZone');
            });


            /*Сортировка по умолчанию*/
            /*Скрыть блок информации в кострукторе*/
            $('.info_link').mouseleave(function(){
                $('.descriptionStage').css('display','none');
            });

            /*Отмена клика по ссылке информации*/
            $('.info_link').click(function(){
                return false;
            });
            $('.wrapLogin').click(function(){
                window.location.href = $('#toHome').attr('href');
            });


            /* Изменение фона продукта при нведении на описание в корзине*/
            $('.thumbdescription').mouseenter(function(e){
                this.style.background='#cccccc';
            });



            sizeCartPopup(); //установка высоты блока продуктов в попапе
            checkCart(); //проверка заполненности корзины
            var height=$('input#height_blade_construct').val()*4; //начальная высота клинка
            var length=$('input#length_blade_construct').val()*4; //начальная длинна клинка
            var lengthHandle=$('input#length_handle_construct').val()*4; // начальная длина ручки ножа

            var flagChange=0; //флаг для изменения viewBox
            /*Первоначальная установка svg view ножа*/
            if($(window).width()<=1740){
                shape = document.getElementById("svg");
                shape.setAttribute("viewBox", "0 0 1200 335");
                $('#bladePaths').attr('transform','translate(0 48)');
                $('#lineikaLines').attr('transform','translate(0 10)');
                $('#main_test_svg').removeClass('svg1740');
                //if (isIE) $('.test_svg').css('height', '335px');
            } else {
                //if (isIE) $('.test_svg').css('height', '335px');
            }
            // $('.construct_selected input').click(function(){
            //     return false;
            // });
            /*Изменение svg длины клинка*/
            $('input#length_blade_construct, input#phone-length_blade_construct').change(function(){
                w=$(window).width();
                length=$(this).val()*4;
                if(length>640 && flagChange===0 && w<=1740){
                    shape = document.getElementById("svg");
                    shape.setAttribute("viewBox", "0 0 1800 502");
                    $('#bladePaths').attr('transform','translate(600 70)');
                    $('#lineikaLines').attr('transform','translate(0 175)');
                    $('#main_test_svg').removeClass('svg1740');
                    flagChange=1;
                } else if(flagChange===1 && length<=640 && w<=1740){
                    $('#main_test_svg').removeClass('svg1740');
                    shape = document.getElementById("svg");
                    shape.setAttribute("viewBox", "0 0 1200 335");
                    $('#bladePaths').attr('transform','translate(0 48)');
                    $('#lineikaLines').attr('transform','translate(0 10)');
                    flagChange=0;
                }
                if (w>1740) {
                    shape = document.getElementById("svg");
                    shape.setAttribute("viewBox", "0 0 1800 502");
                    $('#bladePaths').attr('transform','translate(600 70)');
                    $('#lineikaLines').attr('transform','translate(0 62)');
                    $('#main_test_svg').addClass('svg1740');
                    if(length>640 && flagChange===0){
                        flagChange=1;
                    } else if (flagChange===1 && length<=640){
                        flagChange=0;
                    }
                }
                driveKnife(length,height,320,80);
            });

            /*Изменение svg высоты клинка*/
            $('input#height_blade_construct, input#phone-height_blade_construct').change(function(){
                height=$(this).val()*4;
                driveKnife(length,height,320,80);
            });
            /*изменение svg длины рукояти*/
            $('input#length_handle_construct, input#phone-length_handle_construct').change(function(){
                lengthHandle=$(this).val()*4;
                driveKnifeHandle(lengthHandle,280,0.22);
            });
            $("input#butt_width_construct, input#phone-butt_width_construct").change(function(){
                showNewSum();
            });
            $('#additionBlade_3').click(function(){
                if($('#additionBlade_3').is(':checked')) {
                    skvozByUser = true;
                } else {
                    skvozByUser = false;
                }
            });
            document.getElementById('file').addEventListener('change', handleFileSelect, false);
            /*Ползунок для длины клинка*/
            $( "#sliderBladeLength" ).slider({
                value : 130,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_BLADE_LENGTH,//Минимально возможное значение на ползунке
                max : MAX_BLADE_LENGTH,//Максимально возможное значение на ползунке
                step : 5,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#sliderBladeLength" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#contentSliderBladeLength" ).html( val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#contentSliderBladeLength" ).html((ui.value).toFixed(1)+' мм');
                    $("input#length_blade_construct").val(ui.value);
                    $("input#length_blade_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                },
                slide: function(event, ui){
                    $( "#contentSliderBladeLength" ).html((ui.value).toFixed(1)+' мм');
                    $("input#length_blade_construct").val(ui.value);
                    $("input#length_blade_construct").trigger('change');
                    /* Fix handler to be inside of slider borders */
                }
            });

            /*Ползунок для высоты клинка*/
            $( "#sliderBladeHeight" ).slider({
                value : 29,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_BLADE_HEIGHT,//Минимально возможное значение на ползунке
                max : MAX_BLADE_HEIGHT,//Максимально возможное значение на ползунке
                step : 1,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#sliderBladeHeight" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#contentSliderBladeHeight" ).html(val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#contentSliderBladeHeight" ).html((ui.value).toFixed(1)+' мм');
                    $("input#height_blade_construct").val(ui.value);
                    $("input#height_blade_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                },
                slide: function(event, ui){
                    $( "#contentSliderBladeHeight" ).html((ui.value).toFixed(1)+' мм');
                    $("input#height_blade_construct").val(ui.value);
                    $("input#height_blade_construct").trigger('change');
                }
            });

            /*Ползунок для толщины обуха*/
            $( "#sliderButtWidth" ).slider({
                value : 3.5,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_BUTT_WIDTH,//Минимально возможное значение на ползунке
                max : MAX_BUTT_WIDTH,//Максимально возможное значение на ползунке
                step : 0.1,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#sliderButtWidth" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#contentSliderButtWidth" ).html( val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#contentSliderButtWidth" ).html((ui.value).toFixed(1)+' мм');
                    $("input#butt_width_construct").val(ui.value);
                    $("input#butt_width_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                    old_butt_width = ui.value;
                },
                slide: function(event, ui){
                    $( "#contentSliderButtWidth" ).html((ui.value).toFixed(1)+' мм');
                    $("input#butt_width_construct").val(ui.value);
                    $("input#butt_width_construct").trigger('change');
                }
            });
            $('#sliderButtWidth .ui-slider-handle').click(function(){
            });


            /*Ползунок для ручки*/
            $( "#sliderHandle" ).slider({
                value : 120,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_HANDLE_LENGTH,//Минимально возможное значение на ползунке
                max : MAX_HANDLE_LENGTH,//Максимально возможное значение на ползунке
                step : 5,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#sliderHandle" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#contentSliderHandle" ).html(val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#contentSliderHandle" ).html((ui.value).toFixed(1)+' мм');
                    $("input#length_handle_construct").val(ui.value);
                    $("input#length_handle_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                },
                slide: function(event, ui){
                    $( "#contentSliderHandle" ).html((ui.value).toFixed(1)+' мм');
                    $("input#length_handle_construct").val(ui.value);
                    $("input#length_handle_construct").trigger('change');
                }
            });


            /*newPhone*/
            $('#additionBladePhone_3').click(function(){
                if($('#additionBladePhone_3').is(':checked')) {
                    skvozByUser = true;
                } else {
                    skvozByUser = false;
                }
            });

            /*Ползунок для длины клинка*/
            $( "#phone-sliderBladeLength" ).slider({
                value : 130,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_BLADE_LENGTH,//Минимально возможное значение на ползунке
                max : MAX_BLADE_LENGTH,//Максимально возможное значение на ползунке
                step : 5,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#phone-sliderBladeLength" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#phone-contentSliderBladeLength" ).html( val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#phone-contentSliderBladeLength" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-length_blade_construct").val(ui.value);
                    $("input#phone-length_blade_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                },
                slide: function(event, ui){
                    $( "#phone-contentSliderBladeLength" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-length_blade_construct").val(ui.value);
                    $("input#phone-length_blade_construct").trigger('change');
                    /* Fix handler to be inside of slider borders */
                }
            });

            /*Ползунок для высоты клинка*/
            $( "#phone-sliderBladeHeight" ).slider({
                value : 29,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_BLADE_HEIGHT,//Минимально возможное значение на ползунке
                max : MAX_BLADE_HEIGHT,//Максимально возможное значение на ползунке
                step : 1,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#phone-sliderBladeHeight" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#phone-contentSliderBladeHeight" ).html(val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#phone-contentSliderBladeHeight" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-height_blade_construct").val(ui.value);
                    $("input#phone-height_blade_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                },
                slide: function(event, ui){
                    $( "#phone-contentSliderBladeHeight" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-height_blade_construct").val(ui.value);
                    $("input#phone-height_blade_construct").trigger('change');
                }
            });

            /*Ползунок для толщины обуха*/
            $( "#phone-sliderButtWidth" ).slider({
                value : 3.5,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_BUTT_WIDTH,//Минимально возможное значение на ползунке
                max : MAX_BUTT_WIDTH,//Максимально возможное значение на ползунке
                step : 0.1,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#phone-sliderButtWidth" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#phone-contentSliderButtWidth" ).html( val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#phone-contentSliderButtWidth" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-butt_width_construct").val(ui.value);
                    $("input#phone-butt_width_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                    old_butt_width = ui.value;
                },
                slide: function(event, ui){
                    $( "#phone-contentSliderButtWidth" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-butt_width_construct").val(ui.value);
                    $("input#phone-butt_width_construct").trigger('change');
                }
            });
            $('#phone-sliderButtWidth .ui-slider-handle').click(function(){
            });

            /*Ползунок для ручки*/
            $( "#phone-sliderHandle" ).slider({
                value : 120,//Значение, которое будет выставлено слайдеру при загрузке
                min : MIN_HANDLE_LENGTH,//Минимально возможное значение на ползунке
                max : MAX_HANDLE_LENGTH,//Максимально возможное значение на ползунке
                step : 5,//Шаг, с которым будет двигаться ползунок
                create: function( event, ui ) {
                    val = $( "#phone-sliderHandle" ).slider("value");//При создании слайдера, получаем его значение в перемен. val
                    $( "#phone-contentSliderHandle" ).html(val.toFixed(1)+' мм');//Заполняем этим значением элемент с id contentSlider
                },
                stop: function(event, ui) {
                    $( "#phone-contentSliderHandle" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-length_handle_construct").val(ui.value);
                    $("input#phone-length_handle_construct").trigger('change');
                    fixMarginSlide($(this), $(this).slider('option', 'max'), $(this).slider('option', 'min'));
                },
                slide: function(event, ui){
                    $( "#phone-contentSliderHandle" ).html((ui.value).toFixed(1)+' мм');
                    $("input#phone-length_handle_construct").val(ui.value);
                    $("input#phone-length_handle_construct").trigger('change');
                }
            });



            /*newPhone*/
            /*Отработка действия при удалении загруженной картинки*/
            $('#image_close').click(function(event){
                toggleFlag();
                $('.row').css('z-index','-5');
                $("#file")[0].value = "";
                $('.file_upload .button').text('выбрать картинку');
            });

            /*Отработка действия клавиши esc*/
            window.addEventListener("keydown", function(event){
                if(event.keyCode===27) {
                    doEsc();
                }
            });

            /*Выделение кнопки выбора типа оплаты*/
            $('#stage4 input[name=type_of_payment]').change(function(){
                if ( !$("#stage4 label").hasClass("unhovered") ) {
                    $('#stage4 label').removeClass('button_pushed');
                    $(this).parent('label').addClass('button_pushed');
                }
            });

            /*скрытие формы заказа по клику*/
            $('#form_close').click(function(event){
                $('#wrap_order').css('display','none');
                $('#form_order_product').trigger('reset');
            });

            /*скрытие блока успешной отправки данных*/
            $('#close_alert').click(function(event){
                $('.success').css('display','none');
            });

            @include('scripts.closeMessages')

            $(window).scroll(function() {
                if(checkVisibility('#main_test_svg') @if($mobile != 1)|| checkVisibility('#form_constructor') @endif){
                    $('header').addClass('mainHeaderHided');
                } else {
                    $('header').removeClass('mainHeaderHided');
                    if ($('.open_menu').hasClass('opened')) {
                        showMenu();
                    };
                }
            });
            /*Плавный скрол на якоря*/
            $('.go_to').click( function(){ // ловим клик по ссылке с классом go_to
                var scroll_el = $(this).attr('href'); // возьмем содержимое атрибута href, должен быть селектором, т.е. например начинаться с # или .
                if ($(scroll_el).length != 0) { // проверим существование элемента чтобы избежать ошибки
                    $('html, body').animate({ scrollTop: $(scroll_el).offset().top }, 370); // анимируем скроолинг к элементу scroll_el
                }
                return false; // выключаем стандартное действие
            });
            $('#toHowText').click(function(){
                $('#videoHow').css('display', 'none');
                $('#textHow').css('display', 'block');
                $('#toHowVideo .makingBtn').removeClass('pushedMaking');
                $('#toHowText .makingBtn').addClass('pushedMaking');
            });
            $('#toHowVideo').click(function(){
                $('#videoHow').css('display', 'block');
                $('#textHow').css('display', 'none');
                $('#toHowText .makingBtn').removeClass('pushedMaking');
                $('#toHowVideo .makingBtn').addClass('pushedMaking');
            });
            /*маска для поля телефон и почтового индекса*/
            $(".phone").mask("+7(999) 999-99-99",{placeholder:"_"});
            $(" input[name='mailIndex']").mask("999999",{placeholder:"_"});
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
            /*Лупа при наведении и подсветка кнопок закрытия*/
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
                if ($('#wrap_opros').is(':visible')) {
                    place_image=$('#way_opros').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#way_opros').outerWidth() ||
                        y > $('#way_opros').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('#way_opros').addClass('hoveredClose');
                    } else {
                        $('#way_opros').removeClass('hoveredClose');
                    }
                }
                if ($('#aboutPartWrap').is(':visible')){

                    place_image=$('#aboutPart').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#aboutPart').outerWidth() ||
                        y > $('#aboutPart').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('#aboutPart').addClass('hoveredClose');
                    } else {
                        $('#aboutPart').removeClass('hoveredClose');
                    }
                }
                if ($('#wrap_cart').is(':visible') && $('#wrap_construct_order').is(':hidden')) {
                    place_image=$('#cart').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#cart').outerWidth() ||
                        y > $('#cart').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('.close_cart').addClass('hoveredClose');
                    } else {
                        $('.close_cart').removeClass('hoveredClose');
                    }
                } else if ($('#wrap_construct_order').is(':visible') && $('#aboutPartWrap').is(':hidden')) {
                    place_image=$('#way_to_buy').offset();
                    x = e.pageX - place_image.left;
                    y = e.pageY - place_image.top;
                    if (
                        x > $('#way_to_buy').outerWidth() ||
                        y > $('#way_to_buy').outerHeight() ||
                        x < 0 ||
                        y < 0
                    ) {
                        $('.close_order_construct').addClass('hoveredClose');
                    } else {
                        $('.close_order_construct').removeClass('hoveredClose');
                    }
                }
            });
            // $('.additionBlade').click(function(e){
            //     if(!$(e.target).hasClass('helpClass') && !isIE && !isEdge) {
            //         $(this).children('label').trigger('click');
            //     }
            //     console.log(e);
            // });
            // $('.typeSend').click(function(e){
            //     if(!$(e.target).hasClass('helpClass') && !isIE && !isEdge) {
            //         $(this).children('label').trigger('click');
            //     }
            // });
            function resizeImageAbout(){
                if ((($(window).width()) / $(window).height())< 1) {
                    $('#imageAbout').css('width', '100%');
                    $('#imageAbout').css('height', 'auto');
                } else {
                    $('#imageAbout').css('height', '62%');
                    $('#imageAbout').css('width', 'auto');
                }
            }
            resizeImageAbout();
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
            function changeMenu() {
                if (parseInt($('.menu').css('left'))<0){
                    $('.menu').css('height', $(window).height()- $('.header').outerHeight()-48);
                    $('.menu').addClass('overflowed');
                    $('.menu').getNiceScroll().resize();
                    if($('.open_menu').hasClass('opened')) {
                        hideMainScroll();
                    }
                } else {
                    $('.menu').css('height', '');
                    $('.menu').removeClass('overflowed');
                    $('.menu').getNiceScroll().resize();
                    if($('.open_menu').hasClass('opened')) {
                        showMainScroll();
                    }
                }
            }
            changeMenu();
            /*Реакция на ресайзы*/
            $(window).resize(function() {
                var w = $(window).width();
                var h = $(window).height();
                if (w <= '1000'){
                    $('.main_image img').attr('src',"{{ asset('img') }}//knifes/forest333.jpg?{{VERSION}}");
                }else {
                    $('.main_image img').attr('src',"{{ asset('img') }}/knifes/forest338.jpg?{{VERSION}}");
                }
                shape = document.getElementById("svg");
                if(w<=1740 && flagChange===0){
                    shape.setAttribute("viewBox", "0 0 1200 335");
                    $('#bladePaths').attr('transform','translate(0 48)');
                    $('#lineikaLines').attr('transform','translate(0 10)');
                    $('#main_test_svg').removeClass('svg1740');
                }else if(w<=1740 && flagChange===1){
                    shape.setAttribute("viewBox", "0 0 1800 502");
                    $('#bladePaths').attr('transform','translate(600 70)');
                    $('#lineikaLines').attr('transform','translate(0 175)');
                    $('#main_test_svg').removeClass('svg1740');
                }else if(w>1740 ){
                    shape.setAttribute("viewBox", "0 0 1800 502");
                    $('#bladePaths').attr('transform','translate(600 70)');
                    $('#lineikaLines').attr('transform','translate(0 62)');
                    $('#main_test_svg').addClass('svg1740');
                }
                $('#svg').height($('#svg').width()/3.58);
                sizeCartPopup();
                niceScrollWidthBind();
                resizePhoneFooter();
                resizeCart();
                firstImpressionChanger();
                changeMenu();
                resizeImageAbout();
            });
            $('#svg').height($('#svg').width()/3.58);
            tmplNewChanges();
            setTimeout(function(){$('#form_order .forCaptchaBlock img').click()}, 100);
            setTimeout(function(){$('#phone-steel_construct_19').click()},1500);
        });
    </script>
    </html>

@endsection