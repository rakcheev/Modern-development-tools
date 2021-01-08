Здравствуйте {{$name}},<br><br>

На сайте <a href="https://rusvyatich.pro/">rusvyatich.pro</a> ваш заказ №{{$orderId}} получил статус: {{$status}}<br><br>
{{$mess}}<br><br>
@if($typeOrder == CONSTRUCT_ORDER)
<a href="https://rusvyatich.pro/home/ordersConstruct/{{$orderId}}">К заказу</a>
@elseif($typeOrder == CART_ORDER)
<a href="https://rusvyatich.pro/home/ordersCart/{{$orderId}}">К заказу</a>
@elseif($typeOrder == IMAGE_ORDER)
<a href="https://rusvyatich.pro/home/ordersIndividual/{{$orderId}}">К заказу</a>
@endif
<br><br>

<hr>
Это письмо сформировано автоматически, отвечать на него не нужно