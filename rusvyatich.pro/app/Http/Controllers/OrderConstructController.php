<?php

/*
*
*Контроллер
*сохранения конструктора 
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Classes\Sms\SmsRu;
use App\Order;
use App\typeOfPayment;
use App\Blade;
use App\Steel;
use App\Bolster;
use App\Handle;
use App\HandleMaterial;
use App\KnifePropertiesInOrder;
use App\SpuskInOrder;
use App\Spusk;
use App\AdditionOfBlade;
use App\AdditionInOrder;
use App\UserOwn;
use App\OutFromOrder;
use App\SelectOperator;
use App\Message;
use App\StatusOrder;
use App\Sms;
use App\PayNow;
use App\TypeOfSend;
use App\PasswordAdd;
use Mail;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class OrderConstructController extends Controller
{
	/*
	*
	*Сохранение
	*конструктора 
	*для не авторизованного пользователя
	*
	*/
    public function sendConstruct(request $request)
	{	
		$resData['wrongCaptcha'] = 0;
		$validator = \Validator::make($request->all(), [
	        'captcha' => 'required|captcha'
	    ]);
		if ($validator->fails()) {
		    return response()->json(['success'=>1, 'wrongCaptcha'=>1], 200);
		}
		$id_steel = $request->steel_type_select;
		$id_blade = $request->blade_type_select;
		$blade_length = $request->blade_length_select;
		$blade_height = $request->blade_height_select;
		$butt_width = $request->butt_width_select;
		$id_bolster = $request->bolster_type_select;
		$id_handle = $request->handle_type_select;
		$id_handleMaterial = $request->handle_material_type_select;
		$handle_length = $request->handle_length_select;
		$textFromCustomer = $request->additionallyConstruct;
		$typeSend = $request->typeSend;
		$phone = $request->phone;
		$name = $request->name;
		$email = $request->email;
		$surname = $request->surname;
		$patronymic = $request->patronymic;
		$locality = $request->locality;
		$street = $request->street;
		$house = $request->house;
		$flat = $request->flat;
		$id_area = $request->zone;
		$region = $request->region;
		$mailIndex = $request->mailIndex;
		$typeOfPayment = PAY_LATER;//$request->type_of_payment;
		$sumOfOrder = 0;
		$oprosSale = $request->oprosSale;
		if ($id_steel == '' || $id_blade == '' || $blade_length == '' || $blade_height == '' || $butt_width == '' || $id_bolster == '' || $id_handle == '' || $id_handleMaterial == '' || $handle_length == '' || $phone == '' || $name == '' || $surname == '' || $patronymic == '' || $locality == '' || $street == '' || $house == '' || $region=='' || $mailIndex == '' || $typeOfPayment == '' || $id_area == '' || $request->oprosSale == '')
		{
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка. Не все указано'. $id_steel. '->' .$id_blade. '->' .$blade_length. '->' .$blade_height. '->' .$butt_width. '->' .$id_bolster. '->' .$id_handle. '->' .$id_handleMaterial. '->' .$handle_length. '->' .$phone. '->' .$name. '->' .$surname. '->' .$patronymic. '->' .$locality. '->' .$street. '->' .$house. '->' .$region. '->' . $mailIndex. '->' .$typeOfPayment. '->' .$id_area. '->' .$request->oprosSale;
			echo json_encode($resData);
			return;

		} else {
			try {
				$hardness_blade = Blade::select(['typeOfBlade.id', 'hardness.k as hardness'])
	            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
	            {
	                $join->on('typeOfBlade.hardness', '=', 'hardness.id');
	            })
	            ->where('typeOfBlade.id', $id_blade)->get()->first();
				$hardness_handle = Handle::select(['typeOfHandle.id', 'hardness.k as hardness'])
	            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
	            {
	                $join->on('typeOfHandle.hardness', '=', 'hardness.id');
	            })
	            ->where('typeOfHandle.id', $id_handle)->get()->first();
	            $hardness_blade = $hardness_blade->hardness;
	            $hardness_handle = $hardness_handle->hardness;
				$price_steel=intval(Steel::where('id', $id_steel)->value('price'));
				$price_bolster=intval(Bolster::where('id', $id_bolster)->value('price'));
				$price_handle_material=intval(HandleMaterial::where('id', $id_handleMaterial)->value('price'));
			    $sBlade =$blade_length*$blade_height;
			    $buttWidthBlade = $butt_width;
			    $buttKoef = 1; 
			    if ($buttWidthBlade > 3.5) {
			        $buttKoef = 1.1;
			    }
			    if ($buttWidthBlade > 4.5) {
			        $buttKoef = 1.2;
			    }
			    $sumOfOrder = 200+$hardness_blade*$price_steel*3.5*$buttKoef*self::getBladeKoef($sBlade)*$sBlade/3510+$price_bolster+$price_handle_material*$hardness_handle*self::getHandleKoef($handle_length)*3+500;


				$additionOfBladeF = AdditionOfBlade::all();
				foreach ($additionOfBladeF as $additionInDBF) {
					$nameAdditionF = (($request->mobile == 1) ? 'additionPhone_' . $additionInDBF->id : 'addition_' . $additionInDBF->id);
					if ($request->$nameAdditionF) {
							$sumOfOrder+=$additionInDBF->price;
					}
				}
			    if ($sumOfOrder > 4500) {
			        $sumOfOrder = $sumOfOrder*1.20;
			    } else {
			        $sumOfOrder = $sumOfOrder*1.25;
			    }
			    $sumOfOrder = round(round($sumOfOrder)/10)*10;
			    if($oprosSale) {
			    	$sumOfOrder = round($sumOfOrder*0.85);
			    } else {
			    	$sumOfOrder = $sumOfOrder*0.9;
			    }
			} catch(\Exception $e){
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка установки суммы';
				echo json_encode($resData);
				return;
			}
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
				$user->email = $email;
				$user->id_area = $id_area;
				$user->account_status_id = ACTIVE;
				$user->name = $name;
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
				$resData['note'] = 'Ошибка создания аккаунта' . $e->getMessage();
				echo json_encode($resData);
				return;
			}
			try{
				$order = new Order();     
				$order->id_user = $userId;
				$order->sum_of_order = $sumOfOrder; 
				$order->id_payment = $typeOfPayment; 
				$order->id_status = ENTERED; 
				$order->id_type_order = CONSTRUCT_ORDER; 
				$order->id_type_send = $typeSend; 
				$order->customer_note = $textFromCustomer; 
				$order->purpose = 'Позвонить клиенту'; 
				$order->days_for_order = NULL; 
				$order->save();
				$orderId = $order->id;
				
				$additionOfBlade = AdditionOfBlade::all();
				foreach ($additionOfBlade as $additionInDB) {
					$nameAddition = (($request->mobile == 1) ? 'additionPhone_' . $additionInDB->id : 'addition_' . $additionInDB->id);
					if ($request->$nameAddition) {
						$addition = new AdditionInOrder;
						$addition->id_order = $orderId;
						$addition->id_addition = $additionInDB->id;
						$addition->save();
						$idsAdditions[] = $addition->id;
					}
				}

			 //    $resData['sss'] = 1;
			 //    echo json_encode($resData);
				// return;
				$spuskInOrder = new SpuskInOrder;
				$spuskInOrder->id_order = $orderId;
				$spuskInOrder->id_spusk = (($request->mobile == 1) ? $request->spuskPhone : $request->spusk);
				$spuskInOrder->save();
				$idSpuskInOrder = $spuskInOrder->id;

				$deliveryCost = TypeOfSend::where('id', $typeSend)->value('price');

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Сумма заказа: ' . $sumOfOrder . " + " . $deliveryCost . ' (доставка) = ' . ( $deliveryCost + $sumOfOrder) . ' р.'; 
                $message->id_message_type = SUM_CHANGED_MESSAGE;
                $message->save();
                $messageIdSum = $message->id;

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Статус заказа: ' . StatusOrder::where('id', ENTERED)->value('name');
                $message->id_message_type = STATUS_CHANGED_MESSAGE;
                $message->save();
				$messageId = $message->id;
				
				$out = new OutFromOrder(); //установка выхода из просмотра сообщений для пользователя
				$out->id_user = $userId;
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out1 = $out->id;

				$out = new OutFromOrder(); //установка выхода из просмотра сообщений для оператора
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка создания заказа';
				echo json_encode($resData);
				return;
			}
			try {
				$properties = new KnifePropertiesInOrder();
				$properties->id_order = $orderId;
				$properties->id_typeOfSteel = $id_steel;
				$properties->id_typeOfBlade = $id_blade;
				$properties->blade_length = $blade_length;
				$properties->blade_height = $blade_height;
				$properties->butt_width = $butt_width;
				$properties->id_typeOfBolster = $id_bolster;
				$properties->id_typeOfHandle = $id_handle;
				$properties->id_typeOfHandleMaterial = $id_handleMaterial;
				$properties->handle_length = $handle_length;
				$properties->save();
				$propertiesId = $properties->id;
			} catch (\Exception $e) {
				if (isset($propertiesId)) KnifePropertiesInOrder::destroy($propertiesId);
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Свойства ножа не сохранены';
				echo json_encode($resData);
				return;
			}
			$steel = Steel::where('id', $id_steel)->value('name');
			$blade = Blade::where('id', $id_blade)->value('name'); 
			$bolster = Bolster::where('id', $id_bolster)->value('name');
			$handle = Handle::where('id', $id_handle)->value('name');
			$handleMaterial = HandleMaterial::where('id', $id_handleMaterial)->value('name');
			ignore_user_abort(true);
			try {
				if (($typeOfPayment == PAY_CARD) || ($typeOfPayment == PAY_PERSENT)) {
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
				if (isset($propertiesId)) KnifePropertiesInOrder::destroy($propertiesId);
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
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
			// 		if (isset($propertiesId)) KnifePropertiesInOrder::destroy($propertiesId);
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
			// 		if (isset($idsAdditions)){
			// 			foreach ($idsAdditions as $idAddition) {
			// 				AdditionOfBlade::destroy($idAddition);
			// 			}
			// 		}
			// 		SpuskInOrder::destroy($idSpuskInOrder);
			// 		if (isset($out1)) OutFromOrder::destroy($out1);
			// 		if (isset($out2)) OutFromOrder::destroy($out2);
			// 		if (isset($out3)) OutFromOrder::destroy($out3);
			// 		if (isset($messageIdSum)) Message::destroy($messageIdSum);
			// 		if (isset($messageId)) Message::destroy($messageId);
			// 		if (isset($orderId)) Order::destroy($orderId);
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
			$resData['success'] = 1;
			$resData['phone'] = $phone;
			$resData['note'] = 'Сообщение отправлено';
			$resData['payId'] = $typeOfPayment;
			$resData['orderId'] = $orderId;
			$resData = json_encode($resData);
	        header("Connection: close");
	        header("Content-Length: " . mb_strlen($resData));
	        echo $resData;
	        flush();
			$this->sendMessageToEmailFromConstruct($steel, $blade, $blade_length, $blade_height, $butt_width, $bolster, $handle, $handleMaterial, $handle_length, $textFromCustomer, $phone, $name, $surname, $patronymic, $locality, $street, $house, $region, $mailIndex,  $sumOfOrder, $typeOfPayment);

	    }
	}


	/*
	*
	* На консультацию
	* из конструктора 
	*
	*
	*/
    public function sendConsultConstruct(request $request)
	{	
		// $resData['wrongCaptcha'] = 0;
		// $validator = \Validator::make($request->all(), [
	 //        'captcha' => 'required|captcha'
	 //    ]);
		// if ($validator->fails()) {
		//     return response()->json(['success'=>1, 'wrongCaptcha'=>1], 200);
		// }
		$id_steel = $request->steel_type_select;
		$id_blade = $request->blade_type_select;
		$blade_length = $request->blade_length_select;
		$blade_height = $request->blade_height_select;
		$butt_width = $request->butt_width_select;
		$id_bolster = $request->bolster_type_select;
		$id_handle = $request->handle_type_select;
		$id_handleMaterial = $request->handle_material_type_select;
		$handle_length = $request->handle_length_select;
		$phone = $request->phone;
		$name = $request->name;
		$typeSend = 1;
		$typeOfPayment = PAY_LATER;//$request->type_of_payment;
		$sumOfOrder = 0;
		if ($id_steel == '' || $id_blade == '' || $blade_length == '' || $blade_height == '' || $butt_width == '' || $id_bolster == '' || $id_handle == '' || $id_handleMaterial == '' || $handle_length == '' || $phone == '' || $name == '')
		{
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка. Не всё указано'.$id_steel.'nxt'.$id_blade.'nxt'.$blade_length.'nxt'.$blade_height.'nxt'.$butt_width.'nxt'.$id_bolster.'nxt'.$id_handle.'nxt'.$id_handleMaterial.'nxt'.$handle_length.'nxt'.$phone.'nxt'.$name;
			echo json_encode($resData);
			return;

		} else {
			try {
				$hardness_blade = Blade::select(['typeOfBlade.id', 'hardness.k as hardness'])
	            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
	            {
	                $join->on('typeOfBlade.hardness', '=', 'hardness.id');
	            })
	            ->where('typeOfBlade.id', $id_blade)->get()->first();
				$hardness_handle = Handle::select(['typeOfHandle.id', 'hardness.k as hardness'])
	            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
	            {
	                $join->on('typeOfHandle.hardness', '=', 'hardness.id');
	            })
	            ->where('typeOfHandle.id', $id_handle)->get()->first();
	            $hardness_blade = $hardness_blade->hardness;
	            $hardness_handle = $hardness_handle->hardness;
				$price_steel=intval(Steel::where('id', $id_steel)->value('price'));
				$price_bolster=intval(Bolster::where('id', $id_bolster)->value('price'));
				$price_handle_material=intval(HandleMaterial::where('id', $id_handleMaterial)->value('price'));
			    $sBlade =$blade_length*$blade_height;
			    $buttWidthBlade = $butt_width;
			    $buttKoef = 1; 
			    if ($buttWidthBlade > 3.5) {
			        $buttKoef = 1.1;
			    }
			    if ($buttWidthBlade > 4.5) {
			        $buttKoef = 1.2;
			    }
			    $sumOfOrder = 200+$hardness_blade*$price_steel*3.5*$buttKoef*self::getBladeKoef($sBlade)*$sBlade/3510+$price_bolster+$price_handle_material*$hardness_handle*self::getHandleKoef($handle_length)*3+500;

				$additionOfBladeF = AdditionOfBlade::all();
				foreach ($additionOfBladeF as $additionInDBF) {
					$nameAdditionF = 'addition_' . $additionInDBF->id;
					if ($request->$nameAdditionF) {
							$sumOfOrder+=$additionInDBF->price;
					}
				}
			    if ($sumOfOrder > 4500) {
			        $sumOfOrder = $sumOfOrder*1.20;
			    } else {
			        $sumOfOrder = $sumOfOrder*1.25;
			    }
			    $sumOfOrder = round(round($sumOfOrder)/10)*10;
			    $sumOfOrder = round($sumOfOrder*0.85);
			} catch(\Exception $e){
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка установки суммы';
				echo json_encode($resData);
				return;
			}
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
				$password = md5(uniqid($phone, true));
				$user->password = Hash::make($password);
				$user->phone = $phone;
				$user->email = 'vyatich@rusvyatich.pro';
				$user->name = $name;
				$user->id_area = 1;
				$user->status = CUSTOMER;
				$user->account_status_id = 1;

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
				$resData['note'] = 'Ошибка создания аккаунта';
				echo json_encode($resData);
				return;
			}
			try{
				$order = new Order();     
				$order->id_user = $userId;
				$order->sum_of_order = $sumOfOrder; 
				$order->id_payment = $typeOfPayment; 
				$order->id_status = ENTERED; 
				$order->id_type_order = CONSTRUCT_ORDER; 
				$order->id_type_send = $typeSend; 
				$order->purpose = 'Позвонить клиенту'; 
				$order->days_for_order = NULL; 
				$order->save();
				$orderId = $order->id;
				
				$additionOfBlade = AdditionOfBlade::all();
				foreach ($additionOfBlade as $additionInDB) {
					$nameAddition = 'addition_' . $additionInDB->id;
					if ($request->$nameAddition) {
						$addition = new AdditionInOrder;
						$addition->id_order = $orderId;
						$addition->id_addition = $additionInDB->id;
						$addition->save();
						$idsAdditions[] = $addition->id;
					}
				}
				$spuskInOrder = new SpuskInOrder;
				$spuskInOrder->id_order = $orderId;
				$spuskInOrder->id_spusk = $request->spusk;
				$spuskInOrder->save();
				$idSpuskInOrder = $spuskInOrder->id;

				$deliveryCost = TypeOfSend::where('id', $typeSend)->value('price');

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Сумма заказа: ' . $sumOfOrder . " + " . $deliveryCost . ' (доставка) = ' . ( $deliveryCost + $sumOfOrder) . ' р.'; 
                $message->id_message_type = SUM_CHANGED_MESSAGE;
                $message->save();
                $messageIdSum = $message->id;

                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Статус заказа: ' . StatusOrder::where('id', ENTERED)->value('name');
                $message->id_message_type = STATUS_CHANGED_MESSAGE;
                $message->save();
				$messageId = $message->id;

				
				$out = new OutFromOrder(); //установка выхода из просмотра сообщений для пользователя
				$out->id_user = $userId;
				$out->id_order = $orderId;
				$out->id_individual = NULL;
				$out->save();
				$out1 = $out->id;

				$out = new OutFromOrder(); //установка выхода из просмотра сообщений для оператора
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка создания заказа';
				echo json_encode($resData);
				return;
			}
			try {
				$properties = new KnifePropertiesInOrder();
				$properties->id_order = $orderId;
				$properties->id_typeOfSteel = $id_steel;
				$properties->id_typeOfBlade = $id_blade;
				$properties->blade_length = $blade_length;
				$properties->blade_height = $blade_height;
				$properties->butt_width = $butt_width;
				$properties->id_typeOfBolster = $id_bolster;
				$properties->id_typeOfHandle = $id_handle;
				$properties->id_typeOfHandleMaterial = $id_handleMaterial;
				$properties->handle_length = $handle_length;
				$properties->save();
				$propertiesId = $properties->id;
			} catch (\Exception $e) {
				if (isset($propertiesId)) KnifePropertiesInOrder::destroy($propertiesId);
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId)) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Свойства ножа не сохранены';
				echo json_encode($resData);
				return;
			}
			$steel = Steel::where('id', $id_steel)->value('name');
			$blade = Blade::where('id', $id_blade)->value('name'); 
			$bolster = Bolster::where('id', $id_bolster)->value('name');
			$handle = Handle::where('id', $id_handle)->value('name');
			$handleMaterial = HandleMaterial::where('id', $id_handleMaterial)->value('name');
			ignore_user_abort(true);
			try {
				if (($typeOfPayment == PAY_CARD) || ($typeOfPayment == PAY_PERSENT)) {
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
				if (isset($propertiesId)) KnifePropertiesInOrder::destroy($propertiesId);
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
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
				
			$resData['success'] = 1;
			$resData['phone'] = $phone;
			$resData['note'] = 'Сообщение отправлено';
			$resData['payId'] = $typeOfPayment;
			$resData['orderId'] = $orderId;
			$resData = json_encode($resData);
	        // header("Connection: close");
	        // header("Content-Length: " . mb_strlen($resData));
	        echo $resData;
	  //       flush();
			// $this->sendMessageToEmailFromConstructConsult($steel, $blade, $blade_length, $blade_height, $butt_width, $bolster, $handle, $handleMaterial, $handle_length, $textFromCustomer, $phone, $name, $sumOfOrder, $typeOfPayment);

	    }
	}

	/*
	*
	*Сохранение
	*конструктора 
	*для авторизованного пользователя
	*
	*/
	 public function sendConstructAuth(request $request) 
	{
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	$resData['success'] = 0;
			$resData['note'] = 'Ошибка аутентификации';
			$resData['res'] = UNAUTH_ERROR;
			return json_encode($resData);
        }
        
		$id_steel = $request->steel_type_select;
		$id_blade = $request->blade_type_select;
		$blade_length = $request->blade_length_select;
		$blade_height = $request->blade_height_select;
		$butt_width = $request->butt_width_select;
		$id_bolster = $request->bolster_type_select;
		$id_handle = $request->handle_type_select;
		$id_handleMaterial = $request->handle_material_type_select;
		$handle_length = $request->handle_length_select;
		$textFromCustomer = $request->additionallyConstruct;
		$typeOfPayment = PAY_LATER; //$request->type_of_payment;
		$typeSend = $request->typeSend; 
		$oprosSale = $request->oprosSale;
		$sumOfOrder = 0;
		if ($id_steel == '' || $id_blade == '' || $blade_length == '' || $blade_height == '' || $butt_width == '' || $id_bolster == '' || $id_handle == '' || $id_handleMaterial == '' || $handle_length == '' || $typeOfPayment  == '' || $request->oprosSale == '')
		{
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка. Не всё указано';
			echo json_encode($resData);
			return;
		} else {
			try {
				$hardness_blade = Blade::select(['typeOfBlade.id', 'hardness.k as hardness'])
	            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
	            {
	                $join->on('typeOfBlade.hardness', '=', 'hardness.id');
	            })
	            ->where('typeOfBlade.id', $id_blade)->get()->first();
				$hardness_handle = Handle::select(['typeOfHandle.id', 'hardness.k as hardness'])
	            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
	            {
	                $join->on('typeOfHandle.hardness', '=', 'hardness.id');
	            })
	            ->where('typeOfHandle.id', $id_handle)->get()->first();
	            $hardness_blade = $hardness_blade->hardness;
	            $hardness_handle = $hardness_handle->hardness;
				$price_steel=intval(Steel::where('id', $id_steel)->value('price'));
				$price_bolster=intval(Bolster::where('id', $id_bolster)->value('price'));
				$price_handle_material=intval(HandleMaterial::where('id', $id_handleMaterial)->value('price'));
			    $sBlade =$blade_length*$blade_height;
			    $buttWidthBlade = $butt_width;
			    $buttKoef = 1; 
			    if ($buttWidthBlade > 3.5) {
			        $buttKoef = 1.1;
			    }
			    if ($buttWidthBlade > 4.5) {
			        $buttKoef = 1.2;
			    }
			    $sumOfOrder = $hardness_blade*$price_steel*4*$buttKoef*self::getBladeKoef($sBlade)*$sBlade/3510+$price_bolster+$price_handle_material*$hardness_handle*self::getHandleKoef($handle_length)*3+500;
			    // $resData['1'] = $hardness_blade;
			    //  $resData['2'] = $price_steel;
			    //   $resData['3'] = $buttKoef;
			    //    $resData['4'] = $sBlade;
			    //     $resData['5'] = ;
			    //      $resData['6'] = ;

				$additionOfBladeF = AdditionOfBlade::all();
				foreach ($additionOfBladeF as $additionInDBF) {
					$nameAdditionF = (($request->mobile == 1) ? 'additionPhone_' . $additionInDBF->id : 'addition_' . $additionInDBF->id);
					if ($request->$nameAdditionF) {
						$resData['sumofOrderBefore'] = $sumOfOrder; 
						$resData['sumAdded'] = $additionInDBF->price;
							$sumOfOrder+=$additionInDBF->price;
						$resData['sumofOrderAfter'] = $sumOfOrder; 
					}
				}
			    if ($sumOfOrder > 4500) {
			        $sumOfOrder = $sumOfOrder*1.20;
			    } else {
			        $sumOfOrder = $sumOfOrder*1.25;
			    }
			    $sumOfOrder = round(round($sumOfOrder)/10)*10;
			    if($oprosSale) {
			    	$sumOfOrder = round($sumOfOrder*0.85);
			    } else {
			    	$sumOfOrder = $sumOfOrder*0.9;
			    }
			} catch(\Exception $e){
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка установки суммы';
				echo json_encode($resData);
				return;
			}
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
				$resData['note'] = 'Ошибка аккаунта';
				echo json_encode($resData);
				return;
			}
			try {
				$order = new Order();     
				$order->id_user = $userId;
				$order->sum_of_order = $sumOfOrder; 
				$order->id_payment = $typeOfPayment; 
				$order->id_status = ENTERED; 
				$order->id_type_order = CONSTRUCT_ORDER; 
				$order->id_type_send = $typeSend; 
				$order->customer_note = $textFromCustomer; 
				$order->purpose = 'Позвонить клиенту'; 
				$order->days_for_order = NULL; 
				$order->save();
				$orderId = $order->id;
				
				$additionOfBlade = AdditionOfBlade::all();
				foreach ($additionOfBlade as $additionInDB) {
					$nameAddition = (($request->mobile == 1) ? 'additionPhone_' . $additionInDB->id : 'addition_' . $additionInDB->id);
					if ($request->$nameAddition) {
						$addition = new AdditionInOrder;
						$addition->id_order = $orderId;
						$addition->id_addition = $additionInDB->id;
						$addition->save();
						$idsAdditions[] = $addition->id;
					}
				}

				$spuskInOrder = new SpuskInOrder;
				$spuskInOrder->id_order = $orderId;
				$spuskInOrder->id_spusk = (($request->mobile == 1) ? $request->spuskPhone : $request->spusk);
				$spuskInOrder->save();
				$idSpuskInOrder = $spuskInOrder->id;

				$deliveryCost = TypeOfSend::where('id', $typeSend)->value('price');
				
                $message = new Message();
                $message->id_order = $orderId; 
                $message->message = 'Сумма заказа: ' . $sumOfOrder . " + " . $deliveryCost . ' (доставка) = ' . ( $deliveryCost + $sumOfOrder) . ' р.'; 
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка создания заказа';
				echo json_encode($resData);
				return;
			}
			try {
				$properties = new KnifePropertiesInOrder();
				$properties->id_order = $orderId;
				$properties->id_typeOfSteel = $id_steel;
				$properties->id_typeOfBlade = $id_blade;
				$properties->blade_length = $blade_length;
				$properties->blade_height = $blade_height;
				$properties->butt_width = $butt_width;
				$properties->id_typeOfBolster = $id_bolster;
				$properties->id_typeOfHandle = $id_handle;
				$properties->id_typeOfHandleMaterial = $id_handleMaterial;
				$properties->handle_length = $handle_length;
				$properties->save();
				$propertiesId = $properties->id;
			} catch (\Exception $e) {
				if (isset($propertiesId)) KnifePropertiesInOrder::destroy($propertiesId);
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
				if (isset($idsAdditions)){
					foreach ($idsAdditions as $idAddition) {
						AdditionOfBlade::destroy($idAddition);
					}
				}
				SpuskInOrder::destroy($idSpuskInOrder);
				if (isset($out1)) OutFromOrder::destroy($out1);
				if (isset($out2)) OutFromOrder::destroy($out2);
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageIdSum)) Message::destroy($messageIdSum);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) Order::destroy($orderId);
				$resData['success'] = 0;
				$resData['note'] = 'Свойства ножа не сохранены';
				echo json_encode($resData);
				return;
			}
			$steel = Steel::where('id', $id_steel)->value('name');
			$blade = Blade::where('id', $id_blade)->value('name'); 
			$bolster = Bolster::where('id', $id_bolster)->value('name');
			$handle = Handle::where('id', $id_handle)->value('name');
			$handleMaterial = HandleMaterial::where('id', $id_handleMaterial)->value('name');
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
		    $this->sendMessageToEmailFromConstruct($steel, $blade, $blade_length, $blade_height, $butt_width, $bolster, $handle, $handleMaterial,  $handle_length, $textFromCustomer, $phone, $name, $surname, $patronymic, $locality, $street, $house, $region, $mailIndex,  $sumOfOrder, $typeOfPayment);
	    }
	}

	/*Отправка сообщения заказа конструктора*/
	private function sendMessageToEmailFromConstruct($steel, $blade, $blade_length, $blade_height, $butt_width , $bolster, $handle, $handleMaterial, $handle_length, $textFromCustomer, $phone, $name, $surname, $patronymic, $locality, $street, $house, $region, $mailIndex, $sumOfOrder, $typeOfPayment)
	{
		$type_of_payment = typeOfPayment::where('id', $typeOfPayment)->value('name');
		try {
		    Mail::send('emails.constructMail', ['steel' => $steel, 'blade' => $blade, 'blade_length' => $blade_length, 'blade_height' => $blade_height, 'butt_width' => $butt_width, 'bolster' => $bolster, 'handle' => $handle, 'handleMaterial' => $handleMaterial, 'handle_length' => $handle_length, 'textFromCustomer' => $textFromCustomer, 'phone' => $phone, 'name' => $name, 'surname' => $surname, 'patronymic' => $patronymic, 'locality' => $locality, 'street' => $street, 'house' => $house, 'region' => $region, 'mailIndex' => $mailIndex, 'type_of_payment' => $type_of_payment, 'sumOfOrder' => $sumOfOrder], function ($message) use ($name)
		    {
		    	$message->to('rezovgg@gmail.com', $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject('КОНСТРУКТОР НОЖА!');
		    });
		    return true;   
		} catch (\Exception $e) {
			return false;
		}
	}

	private function getHandleKoef($handleLengthFunc) {
	    $afterDot = $handleLengthFunc/100 - 1;
		($handleLengthFunc<100) ? ($afterDot = 0) : ($afterDot = $afterDot*2.5);
	    return (1+$afterDot);
	}
	private function getBladeKoef($mBlade) {
		($mBlade<3510) ? ($mBlade=1.03) : $mBlade = $mBlade/3510;
	    return ($mBlade);
	}

	private function sendMessageToEmailFromConstructConsult($steel, $blade, $blade_length, $blade_height, $butt_width , $bolster, $handle, $handleMaterial, $handle_length, $textFromCustomer, $phone, $name, $sumOfOrder, $typeOfPayment) {

		$type_of_payment = typeOfPayment::where('id', $typeOfPayment)->value('name');
		try {
		    Mail::send('emails.consultMail', ['steel' => $steel, 'blade' => $blade, 'blade_length' => $blade_length, 'blade_height' => $blade_height, 'butt_width' => $butt_width, 'bolster' => $bolster, 'handle' => $handle, 'handleMaterial' => $handleMaterial, 'handle_length' => $handle_length, 'textFromCustomer' => $textFromCustomer, 'phone' => $phone, 'name' => $name, 'type_of_payment' => $type_of_payment, 'sumOfOrder' => $sumOfOrder], function ($message) use ($name)
		    {
		    	$message->to('rezovgg@gmail.com', $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject('Консультация ножа!');
		    });
		    return true;   
		} catch (\Exception $e) {
			return false;
		}
	}
}
