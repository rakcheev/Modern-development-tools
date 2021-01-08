<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Message;
use App\IndividualOrder;
use App\TypeOfSend;

class PaymentController extends Controller
{
	public function success(request $request)
    {
        $title = 'Успешная оплата';
        return view('successPay')->with(['title'=>$title]);
	}
	public function error(request $request)
    {
        $title = 'Ошибка оплаты';
        return view('errorPay')->with(['title'=>$title]);
	}
	public function pending(request $request)
    {
        $title = 'Ожидание оплаты';
        return view('pendingPay')->with(['title'=>$title]);
	}
	public function result(request $request)
    {	
    	$dataSet = $request->all();
    	if(!$dataSet) {
			exit('Ошибка обработки платежа');
    	}
    	unset($dataSet['ik_sign']);// удаляем из данных строку подписи
		ksort($dataSet, SORT_STRING); // сортируем по ключам в алфавитном порядке элементы массива
		array_push($dataSet, '9NVzfZTwdxZP93b6'); // добавляем в конец массива "секретный ключ"
		$signString = implode(':', $dataSet); // конкатенируем значения через символ ":"
		$sign = base64_encode(md5($signString, true)); // берем MD5 хэш в бинарном виде по
		//сформированной строке и кодируем в BASE64
		if ($sign != $request->ik_sign){
			exit('Ошибка платежа');
		} // возвращаем результат
		switch ($request->ik_x_typeorder) {
			case CONSTRUCT_ORDER:
			case CART_ORDER:
				$order = Order::find($request->ik_x_idorder);
				$order->money_payed = $order->money_payed + $request->ik_am;
				$deliveryCost = TypeOfSend::where('id', $order->id_type_send)->value('price');
				if ($order->money_payed >= ($order->sum_of_order + $deliveryCost)) $order->id_payed = PAYED;
				$order->save();
                $message = new Message();
                $message->id_order = $request->ik_x_idorder; 
                $message->message = 'Внесено: ' . $request->ik_am . ' р.';
                $message->id_message_type = PAYED_MESSAGE;
                $message->save();
                $messageId = $message->id;

				break;
			case IMAGE_ORDER:
				$order = IndividualOrder::find($request->ik_x_idorder);
				$order->money_payed = $order->money_payed + $request->ik_am;
				$deliveryCost = TypeOfSend::where('id', $order->id_type_send)->value('price');
				if ($order->money_payed >= ($order->sum_of_order + $deliveryCost)) $order->id_payed = PAYED;
				$order->save();
                $message = new Message();
                $message->id_individual = $request->ik_x_idorder; 
                $message->message = 'Внесено: ' . $request->ik_am . ' р.';
                $message->id_message_type = PAYED_MESSAGE;
                $message->save();
                $messageId = $message->id;
				break;
			default:
				break;
		}
	}
}
