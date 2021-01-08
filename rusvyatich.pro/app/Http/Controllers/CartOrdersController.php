<?php

/*
*
*Контроллер листа заказов из корзины
*для работников
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use Session;

ignore_user_abort(true);
set_time_limit(0);

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

class CartOrdersController extends Controller
{
    public function index()
    {	
        $title = 'Личный кабинет';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        return view('homeAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"#", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'constructOrdersLink'=>"/home/constructOrders", 'individualOrdersLink'=>"/home/individualOrders", 'cartOrdersLink'=>"#", 'allOrdersLink'=>"/home/allOrders", 'typeId'=>3]);
	}
}
