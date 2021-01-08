<span style="font-size:16px; font-weight:bold; line-height:2.5;">Информация о клиенте</span>
<table rules="all" style="border-color: #666;" cellpadding="10">
	<tr><td><strong>Имя</strong> </td><td>{{ $name }}</td></tr>
	<tr><td><strong>Фамилия</strong> </td><td>{{ $surname }}</td></tr>
	<tr><td><strong>Отчество</strong> </td><td>{{ $patronymic }}</td></tr>
	<tr><td><strong>Телефон</strong> </td><td>{{ $phone }}</td></tr>
	<tr><td><strong>Населённый пункт</strong> </td><td>{{ $locality }}</td></tr>
	<tr><td><strong>Улица</strong> </td><td>{{ $street }}</td></tr>
	<tr><td><strong>Дом</strong> </td><td>{{ $house }}</td></tr>
	<tr><td><strong>Область</strong> </td><td>{{ $region }}</td></tr>
	<tr><td><strong>Почтовый индекс</strong> </td><td>{{ $mailIndex }}</td></tr>
	<tr><td><strong>Оплата</strong> </td><td>{{ $type_of_payment }}</td></tr>
</table>
<br>
<span style="font-size:16px; font-weight:bold; line-height:2.5;">Корзина</span>
<table rules="all" style="border-color: #666;" cellpadding="10">
	<tr style='background: #eee;'><td><strong>id</strong> </td><td>Сталь</td><td>Цена</td></tr>
	@foreach ($cartArray as $item)
		<tr><td><strong>{{ $item->id }}</strong></td><td>{{ $item->steel }}</td><td>{{ $item->price }}</td></tr>
	@endforeach		
</table>
<br> 
<span style="font-size:16px; font-weight:bold; line-height:2.5;">Общая сумма:</span>
<span>{{ $sumOfProducts }}</span>