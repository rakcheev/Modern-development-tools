<?php

/*
*
* Контроллер всплывающих
* окон
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;

class CloseWindowController extends Controller
{
    public function close() {
		$window = Cookie::forever('dialogWindow', 1);
		$resData['success'] = 1;
		return response()->json($resData)->withCookie($window);
    }
}
