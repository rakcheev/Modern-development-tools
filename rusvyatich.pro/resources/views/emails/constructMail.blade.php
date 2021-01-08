<span style="font-size:16px; font-weight:bold; line-height:2.5;">Информация о клиенте</span><br>
<table rules="all" style="border-color: #666;" cellpadding="10">
	<tr><td style='background: #eee;'><strong>Имя</strong> </td><td>{{ $name }}</td></tr>
	<tr><td style='background: #eee;'><strong>Фамилия</strong> </td><td>{{ $surname }}</td></tr>
	<tr><td style='background: #eee;'><strong>Отчество</strong> </td><td>{{ $patronymic }}</td></tr>
	<tr><td style='background: #eee;'><strong>Телефон</strong> </td><td>{{ $phone }}</td></tr>
	<tr><td style='background: #eee;'><strong>Населённый пункт</strong> </td><td>{{ $locality }}</td></tr>
	<tr><td style='background: #eee;'><strong>Улица</strong> </td><td>{{ $street }}</td></tr>
	<tr><td style='background: #eee;'><strong>Дом</strong> </td><td>{{ $house }}</td></tr>
	<tr><td style='background: #eee;'><strong>Область</strong> </td><td>{{ $region }}</td></tr>
	<tr><td style='background: #eee;'><strong>Почтовый индекс</strong> </td><td>{{ $mailIndex }}</td></tr>
	<tr><td style='background: #eee;'><strong>Оплата</strong> </td><td>{{ $type_of_payment }}</td></tr>
</table>
<br> 
<span style="font-size:16px; font-weight:bold; line-height:2.5;">Параметры ножа</span>
<table rules="all" style="border-color: #666;" cellpadding="10">
	<tr><td style='background: #eee;'><strong>Сталь</strong></td><td>{{ $steel  }}</td></tr>
	<tr><td style='background: #eee;'><strong>Клинок</strong></td><td>{{ $blade }}</td></tr>
	<tr><td style='background: #eee;'><strong>Длина клинка</strong></td><td>{{ $blade_length }}</td></tr>
	<tr><td style='background: #eee;'><strong>Высота клинка</strong> </td><td>{{ $blade_height }}</td></tr>
	<tr><td style='background: #eee;'><strong>Толщина обуха</strong> </td><td>{{ $butt_width }}</td></tr>
	<tr><td style='background: #eee;'><strong>Больстер</strong></td><td>{{ $bolster }}</td></tr>
	<tr><td style='background: #eee;'><strong>Рукоять</strong></td><td>{{ $handle }}</td></tr>
	<tr><td style='background: #eee;'><strong>Материал рукояти</strong></td><td>{{ $handleMaterial }}</td></tr>
	<tr><td style='background: #eee;'><strong>Длина рукояти</strong></td><td>{{ $handle_length }}</td></tr>
</table>
<br>
<span style="font-size:16px; font-weight:bold; line-height:2.5;">Комментарий:</span><br>
<span>{{ $textFromCustomer }}</span><br>
<span style="font-size:16px; font-weight:bold; line-height:2.5;">Сумма:</span><br>
<span>{{ $sumOfOrder }}</span>