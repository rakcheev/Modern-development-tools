<?php

/*
*
*Контроллер сохранения заказа 
*из корзины
*
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Knife;
use App\KnifeSerial;
use App\Order;
use App\ProductsInOrder;
use App\ProductsSerialInOrder;
use App\typeOfPayment;
use App\UserOwn;
use App\SelectOperator;
use App\OutFromOrder;
use App\Message;
use App\StatusOrder;
use App\PayNow;
use App\Sms;
use App\TypeOfSend;
use App\PasswordAdd;
use Mail;
use Session;


ignore_user_abort(true);
set_time_limit(0);

class OrderCartController extends Controller
{
	/*
	*
	*Сохранение
	*заказа из корзины
	*
	*/
	public function sendCart(request $request)
	{
		$resData['wrongCaptcha'] = 0;
		$validator = \Validator::make($request->all(), [
	        'captcha' => 'required|captcha'
	    ]);
		if ($validator->fails()) {
		    return response()->json(['success'=>1, 'wrongCaptcha'=>1], 200);
		}
		$phone = $request->phone;
		$email = $request->email;
		$id_area = $request->zone;
		$name = $request->name;
		$surname = $request->surname;
		$patronymic = $request->patronymic;
		$locality = $request->locality;
		$street = $request->street;
		$house = $request->house;
		$flat = $request->flat;
		$region = $request->region;
		$mailIndex = $request->mailIndex;
		$typeSend = $request->typeSend;
		$typeOfPayment = PAY_LATER; //$request->type_of_payment;
		$ids = $request->session()->get('cart')->items;
		$cartArray = null;
		$sumOfProducts = $request->session()->get('cart')->totalPrice;
		$badProduct = false; // проверка не куплен ли уже продукт 
		if($ids) {
			$cartArray = Knife::whereIn('id', $ids)->get();
			foreach ($cartArray as $item) {
				if ($item->id_status !== AVAILABLE) {
					$badProduct = true;
				}
			}
		}
		$preArraySerial = $request->session()->get('cart')->itemsSerial;
		$cartArraySerial = null;
		$idsSerial = null;
		if($preArraySerial) {
			foreach ($preArraySerial as $value) {
	    		$idsSerial[] = $value['id'];
		    }
			$cartArraySerial = KnifeSerial::whereIn('id', $idsSerial)->get();

		}
	    
		if ((count($ids) !== count($cartArray)) || $badProduct || (count($idsSerial) !== count($cartArraySerial))) {
			Session::forget('cart');
			$resData['success'] = 0;
			$resData['res'] = ALREADY_BUYED;
			echo json_encode($resData);
			return;
		}
		/*if ($typeOfPayment !== PAY_CARD) {
			$resData['success'] = 0;
			$resData['note'] = "Ошибка. Не допустимый тип оплаты";
			echo json_encode($resData);
			return;
		}*/
		if((empty($cartArray) && empty($cartArraySerial)) || $sumOfProducts == '') {
			$resData['success'] = 0;
			$resData['note'] = "Ошибка. Корзина пуста";
			$resData['emptyCart'] = 1;
			echo json_encode($resData);
			return;
		}
		if ($phone == '' || $name == '' || $surname == '' || $patronymic == '' || $locality == '' || $street == '' || $house == '' || $region == '' || $mailIndex == '' || $typeOfPayment == '' || $id_area == '')
		{
			$resData['success'] = 0;
			$resData['note'] = "Ошибка. Не всё указано";
			echo json_encode($resData);
			return;
		} else {
			try {
				if (UserOwn::where([
			        	['phone', $phone],
			        	['account_status_id', ACTIVE]
			        ])->exists()) {
					$resData['success'] = 3;
					$resData['note'] = 'Пользователь уже зарегистрирован';
					echo json_encode($resData);
					return;
				}
				$user = new UserOwn();
				$password = md5(uniqid($email, true));
				$user->password = Hash::make($password);
				$user->phone = $phone;
				$user->name = $name;
				$user->email = $email;
				$user->id_area = $id_area;
				$user->account_status_id = ACTIVE;
				$user->surname = $surname; 
				$user->patronymic = $patronymic; 
				$user->status = CUSTOMER;
				$user->locality = $locality; 
				$user->street = $street; 
				$user->house = $house; 
				$user->region = $region; 
				$user->flat = $flat;
				$user->mail_index = $mailIndex;
				$operator = new SelectOperator(); //поиск менее занятого оператора
				$operator = $operator->getOperator();
				$user->id_operator = $operator;
				$user->save();
				$userId = $user->id;

				$hash = md5(uniqid($phone, true));
				$passwordAdd = new PasswordAdd();
				$passwordAdd->id_user = $userId;
				$passwordAdd->access_hash = Hash::make($hash);
				$passwordAdd->save();
				$passwordAddId = $passwordAdd->id;
				$request->session()->put('passwordHash', $hash);
				$request->session()->put('passwordHashId', $passwordAddId);
			} catch (\Exception $e){
				$resData['success'] = 0;
				$resData['note'] = 'Неизвестная ошибка';
				echo json_encode($resData);
				return;
			}
			try{
				$order = new Order();
				$order->id_user = $userId;
				$order->sum_of_order = $sumOfProducts; 
				$order->id_payment = $typeOfPayment; 
				$order->id_status = ENTERED; 
				$order->id_type_order = CART_ORDER; 
				$order->id_type_send = $typeSend; 
				$order->customer_note = NULL; 
				$order->purpose = 'Позвонить клиенту'; 
				$order->days_for_order = NULL; 
				$order->save();
				$orderId = $order->id;
				
				$deliveryCost = TypeOfSend::where('id', $typeSend)->value('price');

                $message = new Message();
                $message->id_order = $orderId;
                $message->message = 'Сумма заказа: ' . $sumOfProducts . " + " . $deliveryCost . ' (доставка) = ' . ($sumOfProducts + $deliveryCost) . ' р.'; 
                $message->id_message_type = SUM_CHANGED_MESSAGE;
                $message->save();
                $messageIdSum = $message->id;

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Статус заказа: ' . StatusOrder::where('id', ENTERED)->value('name');
                $message->id_message_type = STATUS_CHANGED_MESSAGE;
                $message->save();
				$messageId = $message->id;

				$out = new OutFromOrder(); //установка времени выхода из просмотра сообщений для пользователя
				$out->id_user = $userId;
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out1 = $out->id;

				$out = new OutFromOrder(); //установка времени выхода из просмотра сообщений для оператора
				$out->id_user = $operator;
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out2 = $out->id;

				$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для  админа 
				$out->id_user = UserOwn::where('status', ADMIN)->value('id');
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out3 = $out->id;

				$mainOperators = UserOwn::where('status', MAIN_OPERATOR)->get();
				foreach ($mainOperators as $mainOperator) {
					$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для  главного оператора 
					$out->id_user = $mainOperator->id;
					$out->id_order = $orderId;
					$out->id_individual = NULL;
					$out->save();
					$outersOperators[] = $out->id;
				}

				$mainMasters = UserOwn::where('status', MAIN_MASTER)->get();
				foreach ($mainMasters as $mainMaster) {
					$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для главных мастеров
					$out->id_user = $mainMaster->id;
					$out->id_order = $orderId;
					$out->id_individual = NULL;
					$out->save();
					$outersMasters[] = $out->id;
				}
			} catch (\Exception $e){
				if (isset($outersMasters)){
					foreach ($outersMasters as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($outersOperators)){
					foreach ($outersOperators as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка заказа';
				echo json_encode($resData);
				return;
			}
			try {
				if($cartArray) {
					$data = array();
					foreach ($cartArray as $item) {
						$data[] = array('id_order' => $orderId, 'id_product' => $item['id']); // Формирование массива для insert
					}
					ProductsInOrder::insert($data);
	                Knife::whereIn('id', $ids)->update(['id_status'=>IN_ORDER]); 
				}
				if ($preArraySerial) {
					$dataSerial = array();
					foreach ($preArraySerial as $item) {
						$dataSerial[] = array('id_order' => $orderId, 'id_product' => $item['id'], 'count'=>$item['count']); // Формирование массива для insert
					}
	                ProductsSerialInOrder::insert($dataSerial);
	            }
			} catch (\Exception $e) {
				if (isset($orderId) && $cartArray) ProductsInOrder::where('id_order', $orderId)->delete();
				if (isset($orderId) && $cartArraySerial) ProductsSerialInOrder::where('id_order', $orderId)->delete();
                Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]); 
				if (isset($outersMasters)){
					foreach ($outersMasters as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($outersOperators)){
					foreach ($outersOperators as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка установки товаров для заказа';
				Order::destroy($orderId);
				echo json_encode($resData);
				return;
			}
			try {
				if ($typeOfPayment == PAY_CARD) {
			       $token = str_random(8);
			       $pay = new PayNow();
			       $pay->id_order = $orderId;
			       $pay->token = Hash::make($token);
			       $pay->save();
			       $payTokId = $pay->id;
			       Session::put('accessToken', $token);
			       Session::put('orderUnpayed', $orderId);
			       Session::save();
				}
			} catch (\Exception $e) {
				if (isset($payTokId)) PayNow::destroy($payTokId);
				if (isset($orderId) && $cartArray) ProductsInOrder::where('id_order', $orderId)->delete();
				if (isset($orderId) && $cartArraySerial) ProductsSerialInOrder::where('id_order', $orderId)->delete();
                Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]); 
				if (isset($outersMasters)){
					foreach ($outersMasters as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($outersOperators)){
					foreach ($outersOperators as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка заказа';
				echo json_encode($resData);
				return;
			}

        	//Отправляем смс
			// try {
				
			// 	$message = "Ваш логин: " . $phone . " Ваш пароль: " . $password;
			// 	$sms = new Sms();
			// 	$resSms = $sms->sendSms($phone, $message);
			// 	if (!$resSms ) {
			// 		if (isset($payTokId)) PayNow::destroy($payTokId);
			// 		if (isset($orderId) && $cartArray) ProductsInOrder::where('id_order', $orderId)->delete();
			// 		if (isset($orderId) && $cartArraySerial) ProductsSerialInOrder::where('id_order', $orderId)->delete();
	  //               Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]); 
			// 		if (isset($outersMasters)){
			// 			foreach ($outersMasters as $outer) {
			// 				OutFromOrder::destroy($outer);
			// 			}
			// 		}
			// 		if (isset($outersOperators)){
			// 			foreach ($outersOperators as $outer) {
			// 				OutFromOrder::destroy($outer);
			// 			}
			// 		}
			// 		if (isset($out1)) OutFromOrder::destroy($out1);
			// 		if (isset($out2)) OutFromOrder::destroy($out2);
			// 		if (isset($out3)) OutFromOrder::destroy($out3);
			// 		if (isset($messageIdSum)) Message::destroy($messageIdSum);
			// 		if (isset($messageId)) Message::destroy($messageId);
			// 		if (isset($orderId)) Order::destroy($orderId);
			// 		if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
			// 		if (isset($userId)) UserOwn::destroy($userId);
			//         Session::forget('accessToken');
			//         Session::forget('orderUnpayed');
			//         Session::save();
			// 		$resData['success'] = 0;
			// 		$resData['note'] = 'Ошибка отправки пароля';
			// 		echo json_encode($resData);
			// 		return;
			// 	}
			// } catch (\Exception $e) {
			// 	$resData['success'] = 0;
			// 	$resData['note'] = 'Неизвестная ошибка';
			// 	echo json_encode($resData);
			// 	return;
				
			// }
			Session::forget('cart');
			Session::save();
			$resData['success'] = 1;
			$resData['phone'] = $phone;
			$resData['note'] = 'Сообщение отправленно';
			$resData['payId'] = $typeOfPayment;
			$resData['orderId'] = $orderId;
			$resData = json_encode($resData);
			ignore_user_abort(true);
		    header("Connection: close");
		    header("Content-Length: " . mb_strlen($resData));
		    echo $resData;
		    flush();
			$this->sendMessageToEmailFromCart($phone, $name, $surname, $patronymic, $locality, $street, $house, $region, $mailIndex, $typeOfPayment, $cartArray, $sumOfProducts);
		}
	}

	public function sendCartAuth(request $request)
	{
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка аутентификации';
			$resData['res'] = UNAUTH_ERROR;
			echo json_encode($resData);
			return;
        }
        try {
        
			$typeOfPayment = PAY_LATER; //$request->type_of_payment;
			$sumOfProducts = $request->session()->get('cart')->totalPrice;
			$badProduct = false; // проверка не куплен ли уже продукт 
			$ids = $request->session()->get('cart')->items;
			$cartArray = null;
			if($ids) {
				$cartArray = Knife::whereIn('id', $ids)->get();
				foreach ($cartArray as $item) {
					if ($item->id_status !== AVAILABLE) {
						$badProduct = true;
					}
				}
			}
			$preArraySerial = $request->session()->get('cart')->itemsSerial;
			$cartArraySerial = null;
			$idsSerial = null;
			if($preArraySerial) {
				foreach ($preArraySerial as $value) {
		    		$idsSerial[] = $value['id'];
			    }
				$cartArraySerial = KnifeSerial::whereIn('id', $idsSerial)->get();

			}
		    
			if ((count($ids) !== count($cartArray)) || $badProduct || (count($idsSerial) !== count($cartArraySerial))) {
				Session::forget('cart');
				Session::save();
				$resData['success'] = 0;
				$resData['res'] = ALREADY_BUYED;
				echo json_encode($resData);
				return;
			} 
		} catch (\Exception $e) {
			$resData['success'] = 0;
        		$resData['note'] = 'Неизвестная ошибка';
				echo json_encode($resData);
				return;
        }
		$resData = array();
		if ((empty($cartArray) && empty($cartArraySerial)) || $sumOfProducts == '')
		{
			$resData['success'] = 0;
			$resData['emptyCart'] = 1;
			$resData['note'] = 'Ошибка. Корзина пуста.';
			echo json_encode($resData);
			return;
		} else {
			try {
				$user = UserOwn::where('id', $userId)->first();
				$phone = $user->phone;
				$name = $user->name;
				$surname = $user->surname;
				$patronymic = $user->patronymic;
				$locality = $user->locality;
				$street = $user->street;
				$house = $user->house;
				$flat = $user->flat;
				$region = $user->region;
				$mailIndex = $user->mail_index;
			} catch (\Exception $e) {
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка авторизации';
				echo json_encode($resData);
				return;
			}
			try{
				$order = new Order();
				$order->id_user = $userId;
				$order->sum_of_order = $sumOfProducts; 
				$order->id_payment = $typeOfPayment; 
				$order->id_status = ENTERED; 
				$order->id_type_order = CART_ORDER;
				$order->id_type_send = $request->typeSend;
				$order->customer_note = NULL;
				$order->purpose = 'Позвонить клиенту'; 
				$order->days_for_order = NULL; 
				$order->save();
				$orderId = $order->id;
				
				$deliveryCost = TypeOfSend::where('id', $request->typeSend)->value('price');

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Сумма заказа: ' . $sumOfProducts . " + " . $deliveryCost . ' (доставка) = ' . ($sumOfProducts + $deliveryCost) . ' р.'; 
                $message->id_message_type = SUM_CHANGED_MESSAGE;
                $message->save();
                $messageIdSum = $message->id;

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Статус заказа: ' . StatusOrder::where('id', ENTERED)->value('name');
                $message->id_message_type = STATUS_CHANGED_MESSAGE;
                $message->save();
				$messageId = $message->id;

				$out = new OutFromOrder(); //установка времени выхода из просмотра сообщений для пользователя
				$out->id_user = $userId;
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out1 = $out->id;

				$operator = UserOwn::where('id', $userId)->value('id_operator'); //установка времени выхода из просмотра сообщений для оператора
				$out = new OutFromOrder(); 
				$out->id_user = $operator;
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out2 = $out->id;

				$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для  админа 
				$out->id_user = UserOwn::where('status', ADMIN)->value('id');
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out3 = $out->id;

				$mainOperators = UserOwn::where('status', MAIN_OPERATOR)->get();
				foreach ($mainOperators as $mainOperator) {
					$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для  главного оператора 
					$out->id_user = $mainOperator->id;
					$out->id_order = $orderId;
					$out->id_individual = NULL;
					$out->save();
					$outersOperators[] = $out->id;
				}

				$mainMasters = UserOwn::where('status', MAIN_MASTER)->get();
				foreach ($mainMasters as $mainMaster) {
					$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для главных мастеров
					$out->id_user = $mainMaster->id;
					$out->id_order = $orderId;
					$out->id_individual = NULL;
					$out->save();
					$outersMasters[] = $out->id;
				}
			} catch (\Exception $e) {
				if (isset($outersMasters)){
					foreach ($outersMasters as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($outersOperators)){
					foreach ($outersOperators as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3))OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка заказа';
				echo json_encode($resData);
				return;
			}
			try {

				if($cartArray) {
					$data = array();
					foreach ($cartArray as $item) {
						$data[] = array('id_order' => $orderId, 'id_product' => $item['id']); // Формирование массива для insert
					}
					ProductsInOrder::insert($data);
	                Knife::whereIn('id', $ids)->update(['id_status'=>IN_ORDER]); 
				}
        	
				if ($preArraySerial) {
					$dataSerial = array();
					foreach ($preArraySerial as $item) {
						$dataSerial[] = array('id_order' => $orderId, 'id_product' => $item['id'], 'count'=>$item['count']); // Формирование массива для insert
					}
	                ProductsSerialInOrder::insert($dataSerial);
	            }
			} catch (\Exception $e) {
				if (isset($orderId) && $cartArray) ProductsInOrder::where('id_order', $orderId)->delete();
				if (isset($orderId) && $cartArraySerial) ProductsSerialInOrder::where('id_order', $orderId)->delete();
                Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]);
				if (isset($outersMasters)){
					foreach ($outersMasters as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($outersOperators)){
					foreach ($outersOperators as $outer) {
						OutFromOrder::destroy($outer);
					}
				}
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3))OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка установки товаров для заказа';
				echo json_encode($resData);
				return;
			}
			Session::forget('cart');
			Session::save();
			$resData['success'] = 1;
			$resData['note'] = 'Сообщение отправленно';
			$resData['payId'] = $typeOfPayment;
			$resData['orderId'] = $orderId;
			$resData = json_encode($resData);
			ignore_user_abort(true);
		    header("Connection: close");
		    header("Content-Length: " . mb_strlen($resData));
		    echo $resData;
		    flush();
			$this->sendMessageToEmailFromCart($phone, $name, $surname, $patronymic, $locality, $street, $house, $region, $mailIndex, $typeOfPayment, $cartArray, $sumOfProducts);
		}	
       
	}


	/*
	*
	*Отправка сообщения о заказе из корзины
	*
	*/
	private function sendMessageToEmailFromCart($phone, $name, $surname, $patronymic, $locality, $street, $house, $region, $mailIndex, $type_of_payment, $cartArray, $sumOfProducts)
	{
		$type_of_payment = typeOfPayment::where('id', $type_of_payment)->value('name');
		try {
		    Mail::send('emails.cartMail', ['phone' => $phone, 'name' => $name, 'surname' => $surname, 'patronymic' => $patronymic, 'locality' => $locality, 'street' => $street, 'house' => $house, 'region' => $region, 'mailIndex' => $mailIndex, 'type_of_payment' => $type_of_payment, 'cartArray' => $cartArray, 'sumOfProducts' => $sumOfProducts], function ($message) use ($name)
		    {
		    	$message->to('rezovgg@gmail.com', $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject('Заказ из корзины!');
		    });
		    return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}

	