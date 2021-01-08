        <div class="userAllOrders">
            <div class="caption">
                <span>{{ $captionToTable }}</span>
            </div>
            <table id="falseHead" class='adminTable'>
                <tr>
                    <th class="tblOrderNumber">№ заказа</th>
                    <th class="tblOrderType">Тип заказа</th>
                    <th class="tblOrderDate">Дата</th>
                    <th class="tblOrderStatus">Статус</th>
                    <th class="tblOrderSum">Сумма</th>
                </tr>
            </table>
            <div id="scrollTable" class="scrollTable">
                <table id="OrdersTable" class='adminTable'>
                    <tr class="captionsTableOrder">
                        <th class="tblOrderNumber">№ заказа</th>
                        <th class="tblOrderType">Тип заказа</th>
                        <th class="tblOrderDate">Дата</th>
                        <th class="tblOrderStatus">Статус</th>
                        <th class="tblOrderSum">Сумма</th>
                    </tr>
                    @foreach($orders as $order)
                        <tr class="OrderRecord" onclick=" 
                            toOrderForMaster({{ $order->id }}, {{ $order->id_type_order }});
                        return false">
                            <td aria-label="№">
                                <div>@if($order->id_type_order === 2)i @endif{{ $order->id }}</div>
                            </td>
                            <td aria-label="Тип заказа">
                                <div>{{ $order->orderType }}</div>
                            </td>
                            <td aria-label="Дата">
                                <div class="tblDate"><span class="timeOrder">{{ $order->TimeCreate }}</span><span class="dateOrder">{{ $order->DateCreate }}</span></div>
                            </td>
                            <td aria-label="Статус">
                                <div>{{ $order->statusOrder }}</div>
                            </td>
                            <td aria-label="Сумма">
                                <div>{{ $order->sum_of_order }}</div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>