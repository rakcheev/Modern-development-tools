<?php

/*
*
*Контроллер страницы
*индивидуального заказа
*
*для работников
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\StatusOrder;
use App\OutFromOrder;
use App\WorkTime;
use App\TypeOfSend;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class PageOrderIndividualController extends Controller
{
    public function index($id)
    {
        $title = 'Индивидуальный заказ № ' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        if ($status === MAIN_OPERATOR ) DB::table('individual_order')->where('id', $id)->update(['id_viewed' => 2]);
        if ($status === OPERATOR ) DB::table('individual_order')->where('id', $id)->update(['id_viewed' => 2]);
        if ($status === MAIN_MASTER ) DB::table('individual_order')->where('id', $id)->update(['id_viewed_master' => 2]);
 		$order =  DB::table('individual_order')
        ->select('individual_order.*', 'types_of_payment.name as namePayment', 'areas.fst_hours', 'areas.scnd_hours', DB::raw('users.id as user_id'), DB::raw('TIMESTAMPDIFF(SECOND, NOW(), `users`.`last_visit`) as raznOnline'), 'users.id_operator', 'users.name', 'users.surname', 'users.patronymic', 'users.phone', 'users.email', 'users.region', 'users.account_status_id', 'users.locality', 'users.sms_alert_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.id as statusId', 'order_statuses.name as statusOrder')->where([
		    ['individual_order.id', '=', $id]
        ])
		->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
		{
            $join->on('individual_order.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `areas`) areas'), function($join)
        {
            $join->on('users.id_area', '=', 'areas.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
		{
            $join->on('order_statuses.id', '=', 'individual_order.id_status');
        })
        ->join(DB::raw('(SELECT * FROM `types_of_payment`) types_of_payment'), function($join)
        {
            $join->on('individual_order.id_payment', '=', 'types_of_payment.id');
        })
        ->first();
        if (abs($order->raznOnline) > 300) { //менее пяти минут назад (онлайн)
            $order->raznOnline = false;
        } else {
            $order->raznOnline = true;
        }
        $statusesOrder =  DB::table('order_statuses_for_type_users')
        ->select('order_statuses_for_type_users.id_order_status as id', 'order_statuses.name as name')->where([
            ['id_user_type', '=', $status],
            ['id_permission', '=', '1']
        ])
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses_for_type_users.id_order_status', '=', 'order_statuses.id');
        });
        $statusesOrder  = StatusOrder::select(['id', 'name'])->where('id', $order->statusId)
        ->union($statusesOrder)
        ->orderBy('id')
        ->get();
        $mainMasters = DB::table('users')
        ->select('id')
        ->where('status', MAIN_MASTER)
        ->get();
        $mainOperators = DB::table('users')
        ->select('id')
        ->where('status', MAIN_OPERATOR)
        ->get();
        $masters = null;
        if ($status === MAIN_MASTER) {
            $masters = UserOwn::where('status', MASTER)->orWhere([['id', '=', $userId], ['status', '=', MAIN_MASTER]])->get();
        }
        $operators = null;
        if ($status === MAIN_OPERATOR) {
            $operators = UserOwn::where('status', OPERATOR)->orWhere([['id', '=', $userId], ['status', '=', MAIN_OPERATOR]])->get();
        }
        $myOuter = OutFromOrder::where([
            ['id_user', '=', $userId],
            ['id_individual', '=', $id]
        ])->value('id');
        return view('pageOrderIndividualAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'order' => $order, 'statusesOrder'=> $statusesOrder, 'masters'=>$masters, 'operators'=>$operators, 'typeOrder'=>IMAGE_ORDER, 'mainMasters'=>$mainMasters, 'mainOperators'=>$mainOperators, 'outer'=>$myOuter, 'timeWork'=>WorkTime::isWork(), 'typeOfSends'=>TypeOfSend::all()] );
	}
}
