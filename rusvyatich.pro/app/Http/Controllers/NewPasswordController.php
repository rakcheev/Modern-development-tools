<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\PasswordRecovery;
use Session;
use Cookie;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class NewPasswordController extends Controller
{
    public function index($idUser, $accessHash)
    {
        $title = 'Новый пароль';
        $descriptionPage = 'Вятич - ввод нового пароля';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        $User = UserOwn::where('id', $idUser)->first();
        //DB::enableQueryLog();
        $passwordRecovery = DB::table('password_recovery')
        ->select('access_hash', 'expires')->where('id_user', '=', $idUser)
        ->whereRaw('ABS(TIMESTAMPDIFF(SECOND, NOW(), `created_at`)) < `expires`')
        ->orderBy('created_at','desc')
        ->first();
        
        if (!$passwordRecovery) return redirect('/');
        if (!Hash::check($accessHash, $passwordRecovery->access_hash)) return redirect('/');
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	return view('newPassword')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage, 'access_hash' => $accessHash, 'id_user' => $idUser]);
        }
        
        return redirect('/home');
	}

	/*Установка нового пароля*/
	public function newPassword(request $request) 
	{
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if ($userId || $status  || $token || Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	$resData['success'] = 2; //вы авторизованы
        	echo json_encode($resData);
        	return;
        }
        $passwordRecovery = DB::table('password_recovery')
        ->select('id', 'access_hash', 'expires')->where('id_user', '=', $request->id_user)
        ->whereRaw('ABS(TIMESTAMPDIFF(SECOND, NOW(), `created_at`)) < `expires`')
        ->orderBy('created_at','desc')
        ->first();
        if (!$passwordRecovery) {
        	$resData['success'] = 0; //не рабочая ссылка
            $resData['note'] = "Ссылка восстановления устарела. Пожалуйста запросите новую";
        	echo json_encode($resData);
        	return;
        }
        if (!Hash::check($request->access_hash, $passwordRecovery->access_hash)) {
        	$resData['success'] = 0; //не рабочая ссылка
        	echo json_encode($resData);
        	return;
        }
        if (!$request->password || strlen($request->password) < 8) {
        	$resData['success'] = 0; //пустой пароль
            $resData['note'] = "Неподходящий пароль";
        	echo json_encode($resData);
        	return;
        }
        $userOwn = UserOwn::where('id', $request->id_user)->first();
        if (!$userOwn) {
        	$resData['success'] = 0; //не рабочая ссылка
            $resData['note'] = "Данный пользователь не существует";
        	echo json_encode($resData);
        	return;
        } 
        $userOwn->password = Hash::make($request->password);
        $request->session()->put('userId', $userOwn->id);
        $token = str_random(8);
        $request->session()->put('token', $token);
        $request->session()->put('status', $userOwn->status);
	    $userOwn->tok = Hash::make($token);
	    $userOwn->last_tok_update = DB::raw('NOW()');
   		$rememberToken = str_random(8);
   		$userOwn->remember_me = Hash::make($rememberToken);
   		$log = Cookie::forever('login', $userOwn->phone);
   		$tok = Cookie::forever('rememberme', $rememberToken);
        $userOwn->save();
        PasswordRecovery::destroy($passwordRecovery->id);
    	$resData['success'] = 1; // готово
		return response()->json($resData)->withCookie($tok)->withCookie($log);
	}
}
