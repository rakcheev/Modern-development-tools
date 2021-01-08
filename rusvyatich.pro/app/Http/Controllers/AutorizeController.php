<?php

/*
*
*Контроллер авторизаци пользователя  
*через форму
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use Session;
use Cookie;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class AutorizeController extends Controller
{	
    public function index()
    {
        $title = 'Вятич - вход';
        $descriptionPage = 'Введите логин и пароль чтобы войти';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	return view('auth')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage]);
        }

        return redirect('/home');
	}

	/*ajax авторизация пользователя*/
	public function authUser(request $request)
	{
		$resData['success'] = 0;
		$user = UserOwn::select(['id', 'status', 'name', 'phone', 'password','remember_me', DB::raw('TIMESTAMPDIFF(HOUR, NOW(), `last_error_entrance`) as raznEntrance')])
        ->where([
        	['phone', $request->username],
        	['account_status_id', ACTIVE]
        ]);

	    $timeTryingEntrance = Session::has('timeTryingEntrance') ? Session::get('timeTryingEntrance') : null;
	    if ($timeTryingEntrance) {
			$difference = abs(time() - $timeTryingEntrance);
	    	$resData['reRequest'] = ($difference < WAIT_LOGIN_TIME) ? (WAIT_LOGIN_TIME - $difference) : 0;
				if ($difference < WAIT_LOGIN_TIME) {
				$log = Cookie::forget('login');
				$tok = Cookie::forget('rememberme');
				$resData['success'] = 7;//подождите проверка пароля
				return response()->json($resData)->withCookie($tok)->withCookie($log);
				}
	    }
	  	if ($user->count()) {
		    $user = $user->first();
		    if (Hash::check($request->password, $user->password)) {
		       $resData['success'] = 1;
		       Session::put('userId', $user->id);
		       $token = str_random(8);
		       Session::put('token', $token);
		       Session::put('status', $user->status);
			   $user->tok = Hash::make($token);
			   $user->last_tok_update = DB::raw('NOW()');
		       if ($request->rememberme == 1) {
		       		$rememberToken = str_random(8);
		       		$user->remember_me = Hash::make($rememberToken);
		       		$log = Cookie::forever('login', $request->username);
		       		$tok = Cookie::forever('rememberme', $rememberToken);
		       } else {
				    $log = Cookie::forget('login');
				    $tok = Cookie::forget('rememberme');
		       }
			   $user->save();
		    } else {
				$resData['res'] = WRONG_LOGIN_PASSWORD;
				$user->increment('count_error_entrance', 1);
				$user->last_error_entrance = DB::raw('NOW()');
				$user->save();
		    	Session::forget('userId');
		    	Session::forget('status');
		    	Session::forget('token');
		    	Session::put('timeTryingEntrance', time());
				$log = Cookie::forget('login');
				$tok = Cookie::forget('rememberme');
		    }
		} else {
			$resData['res'] = WRONG_LOGIN_PASSWORD;
		    Session::put('timeTryingEntrance', time());
			$log = Cookie::forget('login');
			$tok = Cookie::forget('rememberme');
		    Session::forget('userId');
		    Session::forget('status');
		    Session::forget('token');
		}
		Session::forget('accessToken');
		Session::forget('orderUnpayed');
		Session::save();
		return response()->json($resData)->withCookie($tok)->withCookie($log);
	}

	/*ajax выход рользователя*/
	public function outUser(request $request)
	{
		try {
			Session::forget('userId');
			Session::forget('status');
			Session::forget('token');
		    $log = Cookie::forget('login');
		    $tok = Cookie::forget('rememberme');
		    Session::save();
		} catch (\Exception $e) {
			$resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
		
		$resData['success'] = 1;
		return response()->json($resData)->withCookie($tok)->withCookie($log);
	}
}
