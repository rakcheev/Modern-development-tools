<?php

/*
*
*Контроллер страницы индивидуального заказа 
*
*
*для клиентов
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use App\OutFromOrder;
use App\WorkTime;
use App\TypeOfSend;
use Session;


header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class PageUserIndividualController extends Controller
{
    public function index($id)
    {
        $title = 'Индивидуальный заказ №' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        $order =  DB::table('individual_order')
        ->select('individual_order.*',  'users.id_operator', 'order_statuses.name as statusOrder')->where([
            ['individual_order.id', '=', $id],
            ['individual_order.id_user', '=', $userId]
        ])
        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
        {
            $join->on('individual_order.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses.id', '=', 'individual_order.id_status');
        })->first();
        if(!$order || (($order->id_status == REFUSED) && ($order->money_payed == 0))) {
            return redirect()->route('home');
        }
        $mainMasters = DB::table('users')
        ->select('id')
        ->where('status', MAIN_MASTER)
        ->get();
        $mainOperators = DB::table('users')
        ->select('id')
        ->where('status', MAIN_OPERATOR)
        ->get();
        $myOuter = OutFromOrder::where([
            ['id_user', '=', $userId],
            ['id_individual', '=', $id]
        ])->value('id');
        return view('pageUserIndividual')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>'/home/orders', 'order'=>$order, 'typeOrder'=>IMAGE_ORDER, 'mainMasters'=>$mainMasters, 'mainOperators'=>$mainOperators, 'outer'=>$myOuter, 'timeWork'=>WorkTime::isWork(), 'typeOfSend' => TypeOfSend::where('id', $order->id_type_send)->get()->first()]);
    }
}
