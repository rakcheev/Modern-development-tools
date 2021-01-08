<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\PasswordAdd;
use App\UserOwn;
use Session;
use Cookie;

class PasswordAddController extends Controller
{
    
    public function addPassword(request $request)
    { 	
    	try {
    		

	    	$hashId = $request->session()->pull('passwordHashId', false);
	    	$hash = $request->session()->pull('passwordHash', false);
	    	if (!$hash || !$hashId || !Hash::check($hash, PasswordAdd::where('id', $hashId)->value('access_hash'))) {
	        	$resData['success'] = 2;
				$resData['note'] = 'Пустой пароль';
				$resData['1'] = $hashId;
				$resData['2'] = $hash;
				return json_encode($resData);
	        }
	        if (!$request->password || strlen($request->password) < 8) {
	        	PasswordAdd::destroy($hashId); 
	        	$resData['success'] = 2;
	        	$resData['zz'] = strlen($request->password);
	        	$resData['note'] = 'Пустой пароль';
	        	echo json_encode($resData); 
	        	return;
	        }
	 
	        $passwordAdd = PasswordAdd::where('id', $hashId)->first();
	        $userOwn = UserOwn::where('id', $passwordAdd->id_user)->first();
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
	        PasswordAdd::destroy($hashId); 
	        $resData['success'] = 1;
			return response()->json($resData)->withCookie($tok)->withCookie($log);
	    } catch (\Exception $e) {
        	$resData['success'] = 0;
    		echo json_encode($resData);
    		return;
    	}

    }
}
