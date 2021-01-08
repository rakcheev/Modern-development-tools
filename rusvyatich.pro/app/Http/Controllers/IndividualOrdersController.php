<?php

/*
*
*Контроллер листа индивидуальных заказов
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

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class IndividualOrdersController extends Controller
{
    public function index()
    {	
        $title = 'Индивидуальные заказы';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        return view('homeAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"#", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'constructOrdersLink'=>"/home/constructOrders", 'individualOrdersLink'=>"#", 'cartOrdersLink'=>"/home/cartOrders", 'allOrdersLink'=>"/home/allOrders",  'typeId'=>2]);
	}
}
