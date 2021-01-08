<?php

/*
*
*Контроллер восстановления пароля
*через форму
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\PasswordRecovery;
use App\Sms;
use Mail;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class ResetPasswordController extends Controller
{	
    public function index()
    {
        $title = 'Восстановление пароля';
        $descriptionPage = 'Вятич - восстановление пароля';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	return view('resetPassword')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage]);
        }
        
        return redirect('/home');
	}

	/*Восстановление пароля с email*/
    public function email()
    {
        $title = 'Восстановление пароля';
        $descriptionPage = 'Вятич - восстановление пароля';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	return view('resetPasswordByEmail')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage]);
        }
        
        return redirect('/home');
	}

	/*ajax сброс пароля пользователя*/
	public function resetPassword(request $request)
	{
		$resData['success'] = 1;
		$user = UserOwn::select(['id', 'status', 'name', 'phone', 'password','remember_me'])
        ->where([
        	['phone', $request->username],
        	['account_status_id', ACTIVE]
        ]);
	  	if ($user->count()) {
		    $user = $user->first();
		    $diff = DB::table('users')
        	->select(DB::raw('TIMESTAMPDIFF(SECOND, NOW(), `last_reset`) as razn'), DB::raw('TIMESTAMPDIFF(DAY, NOW(), `last_reset`) as raznDay'),'reset_limit')->where('id', $user->id)->first();
        	$dayLimit = $diff->reset_limit;
        	if (abs($diff->raznDay) >= 1) {
        		$dayLimit = RESET_LIMIT;
        	} else {
	        	if ($diff->reset_limit <= 0) {
	        		$resData['note'] ="Колличество попыток на сегодня окончено";
	        		$resData['success'] = 2;
	        		$resData['limit'] = 0;
					echo json_encode($resData);
					return;
	        	}
        	}
        	if (abs($diff->razn) < RESET_TIME) {
        		$resData['timeOut'] = RESET_TIME - abs($diff->razn);
				echo json_encode($resData);
				return;
        	}
        	$dayLimit--;
		    $pwd = str_random(5);
		    $user->password = Hash::make($pwd);
		    $user->reset_limit = $dayLimit;
		    $user->last_reset = DB::raw("NOW()");
		    $user->save();
    		$phoneForSms = preg_replace('/[^0-9]/', '', $request->username);
    		$sms = new Sms();
			$resSms = $sms->sendSms("79251955978", $pwd);
			if ($resSms) {
				$resData['success'] = 1;
				$resData['limit'] = $dayLimit;
				$resData['timeOut'] = RESET_TIME;
			} else {
				$resData['success'] = 0;
				echo json_encode($resData);
				return;
			}
		} else {
        	$lastReset = Session::has('last_reset') ? Session::get('last_reset') : null;
        	$phon = Session::has('phone') ? Session::get('phone') : null;
        	$timeOut = RESET_TIME;
        	$flagNewPhone = false;
        	if ($phon) {
        		if ($phon != $request->username) {
        			Session::put('phone', $request->username);
					$flagNewPhone = true;
        		}
        	} else {
        		Session::put('phone', $request->username);
        	}
			if ($lastReset && !$flagNewPhone) {                                   
				if (abs(strtotime($lastReset) - strtotime(date("H:i:s"))) > 300) {
					Session::put('last_reset', date("H:i:s"));
					$timeOut = RESET_TIME;
				} else {
					$timeOut = RESET_TIME - abs(strtotime($lastReset) - strtotime(date("H:i:s")));
				}
			} else {
				Session::put('last_reset', date("H:i:s"));
				$timeOut = RESET_TIME;
			}
			Session::save();
			$resData['success'] = 1;
			$resData['limit'] = RESET_LIMIT - 1;
			$resData['timeOut'] = $timeOut;
		}
		echo json_encode($resData);
	}

	public function safeEmailStr($email)
	{
	    $em   = explode("@",$email);
	    $name = implode(array_slice($em, 0, count($em)-1), '@');
	    $len  = floor(strlen($name)/2);

	    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
	}

	/*ajax сброс пароля пользователя*/
	public function resetPasswordByEmail(request $request)
	{
		$resData['success'] = 1;
		$user = UserOwn::select(['id', 'status', 'reset_limit', 'name', 'email', 'phone', 'password','remember_me', DB::raw('TIMESTAMPDIFF(DAY, NOW(), `last_reset`) as raznDay'), DB::raw('TIMESTAMPDIFF(SECOND, NOW(), `last_reset`) as razn'),'reset_limit'])
        ->where([
        	['phone', $request->username],
        	['account_status_id', ACTIVE]
        ]);
	  	if ($user->count()) {
		    $user = $user->first();
			$resData['email'] = self::safeEmailStr($user->email); 
		    if (abs($user->razn) < RESET_TIME_EMAIL) {
        		$resData['alreadySended'] = 1;
        		$resData['timeOut'] = RESET_TIME_EMAIL - abs($user->razn);
				echo json_encode($resData);
				return;
		    }
		    $dayLimit = $user->reset_limit;
        	if (abs($user->raznDay) >= 1) {
        		$dayLimit = RESET_LIMIT;
        	} else {
	        	if ($user->reset_limit <= 0) {
	        		$resData['note'] ="Колличество попыток на сегодня окончено";
	        		$resData['success'] = 4;
	        		$resData['limit'] = 0;
					echo json_encode($resData);
					return;
	        	}
        	}
		    $passwordRecovery = DB::table('password_recovery')
        	->select('access_hash', 'expires', DB::raw('ABS(TIMESTAMPDIFF(SECOND, NOW(), `created_at`)) as raznReset'))->where('id_user', '=', $user->id)
        	->whereRaw('ABS(TIMESTAMPDIFF(SECOND, NOW(), `created_at`)) < `expires`')
        	->orderBy('created_at','desc')
        	->first();
        	if ($passwordRecovery) {
        		$resData['timeOut'] = $passwordRecovery->expires - $passwordRecovery->raznReset;
				echo json_encode($resData);
				return;
        	}
        	$dayLimit--;
        	$user->reset_limit = $dayLimit;
		    $user->last_reset = DB::raw("NOW()");
		    $accessHash = dechex(time()).md5(uniqid($user->email));
		    $passwordRecovery =  new PasswordRecovery();
		    $passwordRecovery->id_user = $user->id;
		    $passwordRecovery->access_hash = Hash::make($accessHash);
		    $passwordRecovery->expires = RESET_TIME_EMAIL; //секунды
		    $passwordRecovery->save();
		    $user->save();
		    $resData['timeOut'] = RESET_TIME_EMAIL;
			$link = 'https://rusvyatich.pro/reset/'.$user->id.'/'. $accessHash; 
			$resData['limit'] = $dayLimit;
			$this->sendMessageToEmailPasswordReset($link, $user->email, $user->phone, $user->name, $user->surname, $user->patronymic);
		} else {
			$resData['success'] = 0; //пользователь не найден
			$resData['note'] = "Пользователь не найден";
		}
		echo json_encode($resData);
	}


	/*
	*
	*Отправка сообщения для восстановления пароляы
	*
	*/
	private function sendMessageToEmailPasswordReset($link, $email, $phone, $name, $surname, $patronymic)
	{
		
		try {
		    Mail::send('emails.resetPassword', ['link' => $link, 'phone' => $phone, 'name' => $name, 'surname' => $surname, '[patronymic' => $patronymic], function ($message) use ($name, $email)
		    {
		    	//$message->to('fhagerot23@yandex.ru', $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject('Восстановление пароля');
		    	$message->to($email, $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject('Восстановление пароля');
		    });
		    return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}
