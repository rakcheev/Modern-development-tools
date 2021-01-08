
    <nav class='admin_navigation'>
    	<ul class="navScrollPiece">
            <li>
                <a class="siteNav" href="/">ВяТИч</a>
            </li>
            <li>
                <a class="userNav" href="{{ $toUser }}">мой профиль</a>
            </li>
    		<li>
    			<a class="orderNav" href="{{ $ordersLink }}">заказы</a>
    		</li>
            @if(Session::get('status') !== CUSTOMER)
                @if(Session::get('status') !== MASTER)
            		<li>
            			<a class="productNav" href="{{ $knifesLink }}">продукты</a>
            		</li>
                    <li>
                        <a class="constructNav" href="{{ $changeConstructLink }}">конструктор</a>
                    </li>
                @endif
                @if(Session::get('status') === ADMIN)
            		<li>
            			<a class="statisticNav" href="{{ $statisticLink }}">статистика</a>
            		</li>
                @endif
                @if(Session::get('status') === ADMIN || Session::get('status') === MAIN_MASTER || Session::get('status') === MAIN_OPERATOR)
                    <li>
                        <a class="workerNav" href="{{ $workersLink }}">работники</a>
                    </li>
                @endif
            @endif
    	</ul>
    </nav>
