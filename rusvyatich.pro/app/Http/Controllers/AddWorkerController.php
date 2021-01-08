<?php

/*
*
*Контроллер добавления
*работников
*
*для администратора
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\UserStatus;
use App\Sms;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class AddWorkerController extends Controller
{
    public function index()
    {
        $title = 'Добавление сотрудника';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $userStatuses = UserStatus::where([
        	['id', '<>', CUSTOMER],
        	['id', '<>', ADMIN]
        ])->get();
        return view('addWorker')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'statuses'=>$userStatuses]);
    }

	/*
	*
	*Добавление
	*работника
	*
	*для администратора
	*
	*/
    public function add(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;

        if (!$userId || !$status  || !$token || ($status !== ADMIN && $status !== MAIN_MASTER && $status !== MAIN_OPERATOR) || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
	        $resData['success'] = 0;
			echo json_encode($resData);
			return;
        }

        switch ($status) {
        	case MAIN_OPERATOR:
        		if (intval($request->status) !== OPERATOR){	
			        $resData['success'] = 0;
			        $resData['note'] = 'Тип сотрудника недоступен';
					echo json_encode($resData);
					return;
        		}
        		break;
        	case MAIN_MASTER:
        		if (intval($request->status) !== MASTER){	
			        $resData['success'] = 0;
			        $resData['note'] = 'Тип сотрудника недоступен';
					echo json_encode($resData);
					return;
        		}
        		break;
        		
        	default:
        		break;
        }
        $phone = $request->phone;

		$userDB = UserOwn::select(['id', 'phone'])
        ->where([
        	['phone', $phone],
        	['account_status_id', ACTIVE]
        ]);
        if ($userDB->count()) {
			$resData['success'] = 0;
			$resData['note'] = 'Дублирование телефона';
			echo json_encode($resData);
			return;
        }
        
		$name = $request->name;
		$surname = $request->surname;
		$patronymic = $request->patronymic;
		$locality = $request->locality;
		$street = $request->street;
		$house = $request->house;
		$flat = $request->flat;
		$region = $request->region;
		$mailIndex = $request->mailIndex;	
		$status = $request->status;
		try {
			$user = new UserOwn();
			$password = str_random(8);
			$user->password = Hash::make($password);
			$user->phone = $phone;
			$user->name = $name;
			$user->account_status_id = ACTIVE;
			$user->surname = $surname; 
			$user->patronymic = $patronymic; 
			$user->status = $status;
			$user->locality = $locality;
			$user->street = $street; 
			$user->house = $house; 
			$user->region = $region; 
			$user->flat = $flat;
			$user->mail_index = $mailIndex;
			$user->save();
			$userId = $user->id;
		} catch (\Exception $e){
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка сохранения';
			echo json_encode($resData);
			return;
		}
		$message = "Ваш логин: " . $phone . " Ваш пароль: " . $password;
		$sms = new Sms();
		$resSms = $sms->sendSms($phone, $message);
		if (!$resSms ) {
			UserOwn::destroy($userId);
			$resData['success'] = 0;
			$resData['note'] = 'Ошибка отправки пароля';
			echo json_encode($resData);
			return;
		}
		$resData = array();
		$resData['success'] = 1;
		echo json_encode($resData);
    }
}