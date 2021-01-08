<?php

/*
*
* Контроллер страницы
* смены пароля
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class ChangePasswordController extends Controller
{
    public function index(){
    	$title = "Изменение пароля";
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        return view('changePassword')->with(['title'=>$title]);
    }

    public function changePassword(request $request) {

        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
	        $resData['success'] = 0;
			echo json_encode($resData);
			return;
        }

        if (strlen($request->newPassword) < 5) {
			$resData['success'] = 7;
			echo json_encode($resData);
			return;
        }
        if ($request->newPassword !== $request->newPasswordCheck) {
			$resData['success'] = 6;
			echo json_encode($resData);
			return;
        }
        $user = UserOwn::find($userId);
		if (Hash::check($request->password,  $user->password)) {
			$user->password = Hash::make($request->newPassword);
			$user->save();
		    $resData['success'] = 1;
		} else {
			$resData['success'] = 5; 
		}
		echo json_encode($resData);
    }
}
