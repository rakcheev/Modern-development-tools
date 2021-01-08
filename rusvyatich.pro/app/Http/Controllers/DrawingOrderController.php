<?php

/*
*
*Контроллер сохранения 
*индивидульного заказа
*
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\IndividualOrder;
use App\UserOwn;
use App\SelectOperator;
use App\OutFromOrder;
use App\Message;
use App\StatusOrder;
use App\Sms;
use App\PasswordAdd;
use Mail;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class DrawingOrderController extends Controller
{
	/*
	*
	*Сохранение
	*индивидуального заказа
	*
	*/
	public function sendDrawing(request $request)
	{	  
		$resData['wrongCaptcha'] = 0;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if ($userId){
	        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка аутентификации';
				$resData['res'] = UNAUTH_ERROR;
				echo json_encode($resData);
				return;
	        }
        } else {
			$validator = \Validator::make($request->all(), [
		        'captcha' => 'required|captcha'
		    ]);
			if ($validator->fails()) {
			    return response()->json(['success'=>1, 'wrongCaptcha'=>1], 200);
			}
        }
        $unauth = false; //флаг если пользователь уже зарегистрирован
        if (!$userId) {
        	$unauth = true;
			$name = $request->name;
			$email = $request->email;
			$phone = $request->phone;
			$id_area = $request->zone;
		} else {
			$user = UserOwn::where('id', $userId)->first();
			$name = $user->name;
			$phone = $user->phone;
			$email = $user->email;
			$id_area = $user->id_area;
		}
		try {
			$description = $request->description;
			$image = $request->file('file');
			if (\File::size($image) > 2*1024*1024 || \File::size($image) === 0) {
				$resData['success'] = 2;
				$resData['note'] = 'Ошибка. Размер картинки велик (не более 2 мб)';
				echo json_encode($resData);
				return;
			}
			if ($name == '' || $phone == '' || $email == '' || (!$image && $description == '')){
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка. Не всё указано';
				echo json_encode($resData);
				return;
			}
		} catch (\Exception $e) {
				$resData['success'] = 0;
				echo json_encode($resData);
				return;
		}
		if (is_null($userId)) {
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
				$password = md5(uniqid(($phone . $name), true));
				$user->password = Hash::make($password);
				$tokDb = str_random(8); 
				$user->tok = Hash::make($tokDb);
				$user->last_tok_update = DB::raw("NOW()");
				$user->phone = $phone;
				$user->name = $name;
				$user->email = $email;
				$user->account_status_id = ACTIVE;
				$user->id_area = $id_area;
				$user->status = CUSTOMER;
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
			} catch (\Exception $e) {
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка сохранения пользователя'.$e->getMessage();
				echo json_encode($resData);
				return;
			}
		}
		try {
			$order = new IndividualOrder();
			$order->id_user = $userId;
			$order->id_status = ENTERED;
			$order->purpose = 'Сообщить сумму заказа';
			$order->id_type_order = IMAGE_ORDER;
			$order->description = $description;
			$order->id_payment = PAY_LATER;
			$order->save();
			$orderId = $order->id;
			$resData['orderId'] = $orderId;

            $message = new Message();
            $message->id_individual = $orderId; 
            $message->message = 'Статус заказа: ' . StatusOrder::where('id', ENTERED)->value('name');
            $message->id_message_type = STATUS_CHANGED_MESSAGE;
            $message->save();
			$messageId = $message->id;
			
			$out = new OutFromOrder(); //установка времени выхода из просмотра сообщений для пользователя
			$out->id_user = $userId;
			$out->id_order = NULL;
			$out->id_individual = $orderId;
			$out->save();
			$out1 = $out->id;

			$operator = UserOwn::where('id', $userId)->value('id_operator'); //установка времени выхода из просмотра сообщений для оператора
			$out = new OutFromOrder(); 
			$out->id_user = $operator;
			$out->id_order = NULL;
			$out->id_individual = $orderId;
			$out->save();
			$out2 = $out->id;

			$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для  админа 
			$out->id_user = UserOwn::where('status', ADMIN)->value('id');
			$out->id_order = NULL;
			$out->id_individual = $orderId;
			$out->save();
			$out3 = $out->id;


			$mainOperators = UserOwn::where('status', MAIN_OPERATOR)->get();
			foreach ($mainOperators as $mainOperator) {
				$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для  главного оператора 
				$out->id_user = $mainOperator->id;
				$out->id_order = NULL;
				$out->id_individual = $orderId;
				$out->save();
				$outersOperators[] = $out->id;
			}

			$mainMasters = UserOwn::where('status', MAIN_MASTER)->get();
			foreach ($mainMasters as $mainMaster) {
				$out = new OutFromOrder();  //установка времени выхода из просмотра сообщений для главных мастеров
				$out->id_user = $mainMaster->id;
				$out->id_order = NULL;
				$out->id_individual = $orderId;
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
			if (isset($out3)) OutFromOrder::destroy($out3);
			if (isset($messageId)) Message::destroy($messageId);
			if (isset($orderId)) IndividualOrder::destroy($orderId);
			if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
			if (isset($userId) && $unauth) UserOwn::destroy($userId);

			
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка сохранения заказа';
			echo json_encode($resData);
			return;
		}
		$imageName = false;
        if ($image) {
        	try {
        		$ext = \File::extension($image->getClientOriginalName());
        		$imageName = $orderId . '.' . $ext;
				$image->move(base_path('public/orderImages'), $imageName);
				IndividualOrder::where('id', $orderId)->update(['image' => $imageName]);
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
				if (isset($out3)) OutFromOrder::destroy($out3);
				if (isset($messageId)) Message::destroy($messageId);
				if (isset($orderId)) IndividualOrder::destroy($orderId);
				if (isset($passwordAddId)) PasswordAdd::destroy($passwordAddId);
				if (isset($userId) && $unauth) UserOwn::destroy($userId);
				$resData['success'] = 0;
				$resData['note'] = 'Ошибка сохранения картинки';
				echo json_encode($resData);
				return;
			}
        }

        //Отправляем смс
			// if (isset($userId) && $unauth){
			// 	try {
			// 		$message = "Ваш логин: " . $phone . " Ваш пароль: " . $password;
			// 		$sms = new Sms();
			// 		$resSms = $sms->sendSms($phone, $message);
			// 		if (!$resSms ) {
			// 			if (isset($outersMasters)){
			// 				foreach ($outersMasters as $outer) {
			// 					OutFromOrder::destroy($outer);
			// 				}
			// 			}
			// 			if (isset($outersOperators)){
			// 				foreach ($outersOperators as $outer) {
			// 					OutFromOrder::destroy($outer);
			// 				}
			// 			}
			// 			if (isset($out1)) OutFromOrder::destroy($out1);
			// 			if (isset($out2)) OutFromOrder::destroy($out2);
			// 			if (isset($out3)) OutFromOrder::destroy($out3);
			// 			if (isset($messageId)) Message::destroy($messageId);
			// 			if (isset($orderId)) IndividualOrder::destroy($orderId);
			// 			if (isset($userId) && $unauth) UserOwn::destroy($userId);
		 //                if ($imageName && file_exists(base_path('public/orderImages') . '/' . $imageName)) {
		 //                    unlink(base_path('public/orderImages') . '/' . $imageName);
		 //                }   

			// 			$resData['success'] = 0;
			// 			$resData['note'] = 'Ошибка отправки пароля';
			// 			echo json_encode($resData);
			// 			return;
			// 		}
			// 	} catch (\Exception $e) {
			// 		$resData['success'] = 0;
			// 		$resData['note'] = $e->getMessage();
			// 		echo json_encode($resData);
			// 		return;
						
			// 	}
			// }
			//////////////////////
		$path = false;
        if ($imageName) $path = base_path('public/orderImages') . '\\' . $imageName;
		$resData['success'] = 1;
		$resData['note'] = 'Сообщение отправленно'; 
		$resData['phone'] = $phone;
		$resData = json_encode($resData);
		ignore_user_abort(true);
	    header("Connection: close");
	    header("Content-Length: " . mb_strlen($resData));
	    echo $resData;
	    flush(); // releasing the browser from waiting
        $this->sendIndividualMessageToEmail($orderId, $phone, $name, $description, $path);
	}

	/*
	*
	*Отправка сообщения 
	*индивидуального заказа
	*
	*/
	private function sendIndividualMessageToEmail($orderId, $phone, $name, $description, $path)
	{
		try {
		    Mail::send('emails.drawingMail', ['orderId' => $orderId, 'phone' => $phone, 'name' => $name, 'description' => $description], function ($message) use ($name, $path)
		    {	
		    	if ($path) $message->attach($path);
		    	$message->to('rezovgg@gmail.com', $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject('ИНДИВИДУАЛЬНЫЙ ЗАКАЗ!');
		    });
		    return true;   
		} catch (\Exception $e) {
			return false;
		}
	}
}
