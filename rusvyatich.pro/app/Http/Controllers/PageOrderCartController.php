<?php

/*
*
*Контроллер страницы
*заказа из корзины
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
use App\StatusOrderForTypeUser;
use App\OutFromOrder;
use App\ChangeOrder;
use App\WorkTime;
use App\TypeOfSend;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class PageOrderCartController extends Controller
{
    public function index($id)
    {
        $title = 'Заказ из корзины № ' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        Session::put('cartMessages', 0); //указывает на надобность в загрузке сообщений
        if ($status === MAIN_OPERATOR ) DB::table('orders')->where('id', $id)->update(['id_viewed' => 2]);
        if ($status === OPERATOR ) DB::table('orders')->where('id', $id)->update(['id_viewed' => 2]);
        if ($status === MAIN_MASTER ) DB::table('orders')->where('id', $id)->update(['id_viewed_master' => 2]);
 		$order =  DB::table('orders')
        ->select('orders.*', DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at`, NOW()) as raznSec'), 'types_of_payment.name as namePayment', 'areas.fst_hours', 'areas.scnd_hours', DB::raw('users.id as user_id'), DB::raw('TIMESTAMPDIFF(SECOND, NOW(), `users`.`last_visit`) as raznOnline'), 'users.name', 'users.surname', 'users.account_status_id', 'users.id_operator', 'users.patronymic', 'users.phone', 'users.email', 'users.region', 'users.locality', 'users.sms_alert_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.id as statusId', 'order_statuses.name as statusOrder')->where([
		    ['orders.id', '=', $id]
        ])
		->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
		{
            $join->on('orders.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `areas`) areas'), function($join)
        {
            $join->on('users.id_area', '=', 'areas.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
		{
            $join->on('order_statuses.id', '=', 'orders.id_status');
        })
        ->join(DB::raw('(SELECT * FROM `types_of_payment`) types_of_payment'), function($join)
        {
            $join->on('orders.id_payment', '=', 'types_of_payment.id');
        })
        ->first();
        if (abs($order->raznOnline) > 300) { //менее пяти минут назад (онлайн)
            $order->raznOnline = false;
        } else {
            $order->raznOnline = true;
        }
        $minutes = -10;
        $seconds = -10;
        if (!$order || $order->id_payment !== PAY_CARD || $order->id_payed == PAYED || $order->id_status == REFUSED){

        } else {
            if (($order->raznSec >= TIME_BEFORE_ERASE) && ($order->money_payed == 0)) {
                ChangeOrder::eraseUnpayed();
                return redirect()->route('home');
            }
            $rest = TIME_BEFORE_ERASE - $order->raznSec;
            $minutes = floor(($rest)/60);
            $seconds = $rest - $minutes * 60;
            if ($order->money_payed>0) {
                $minutes = -10;
                $seconds = -10;
            }
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

        $knifesInOrder =  DB::table('products_in_order')
        ->select('products_in_order.*' ,'knifes.*', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images` where `knife_images`.`id_knife` =  `knifes`.`id` order by `knife_images`.`number` asc limit 1) as  image'))->where([
            ['products_in_order.id_order', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `knifes`) knifes'), function($join)
        {
            $join->on('products_in_order.id_product', '=', 'knifes.id');
        })
        ->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')
        ->get();

        $knifesSerialInOrder =  DB::table('products_serial_in_order')
        ->select('products_serial_in_order.count as countInOrder' ,'serial_knifes.*', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images_serial` where `knife_images_serial`.`id_knife` =  `serial_knifes`.`id` order by `knife_images_serial`.`number` asc limit 1) as  image'))->where([
            ['products_serial_in_order.id_order', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `serial_knifes`) serial_knifes'), function($join)
        {
            $join->on('products_serial_in_order.id_product', '=', 'serial_knifes.id');
        })
        ->join('typeOfSteel', 'serial_knifes.id_steel', '=', 'typeOfSteel.id')
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
            ['id_order', '=', $id]
        ])->value('id');
        return view('pageOrderCartAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'order' => $order, 'masters'=>$masters, 'operators'=>$operators, 'statusesOrder'=> $statusesOrder, 'products'=> $knifesInOrder, 'productsSerial'=>$knifesSerialInOrder, 'typeOrder'=>CART_ORDER, 'mainMasters'=>$mainMasters, 'mainOperators'=>$mainOperators, 'outer'=>$myOuter, 'minutes'=>$minutes, 'seconds'=>$seconds, 'timeWork'=>WorkTime::isWork(), 'typeOfSends'=>TypeOfSend::all()] );
	}
}