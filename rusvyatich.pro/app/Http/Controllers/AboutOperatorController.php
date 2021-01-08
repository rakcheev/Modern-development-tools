<?php

/*
*
*Получение страницы
*просмотра о Операторе
*и его закзах
*
*/

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use Session;

class AboutOperatorController extends Controller
{

    public function index($id)
    {
        $title = 'Оператор №'. $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        if (UserOwn::where('id', $id)->exists()){
			$user = UserOwn::find($id);
        } else {
			return redirect()->route('workersAdminHome');
        }
		if ($user->status !== OPERATOR) return redirect()->route('workersAdminHome');
        $ordersFirst =  DB::table('orders')
        ->select('orders.id', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'),'orders.sum_of_order', 'orders.id_type_order', 'orders.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc limit 1) as message'), 'users.id_operator')->where([
            ['id_operator', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
        {
            $join->on('orders.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `types_of_order`) types_of_order'), function($join)
        {
            $join->on('orders.id_type_order', '=', 'types_of_order.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses.id', '=', 'orders.id_status');
        });
        $orders =  DB::table('individual_order')
        ->select('individual_order.id', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), 'individual_order.sum_of_order', 'individual_order.id_type_order', 'individual_order.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc limit 1) as message'), 'users.id_operator')->where([
            ['id_operator', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
        {
            $join->on('individual_order.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `types_of_order`) types_of_order'), function($join)
        {
            $join->on('individual_order.id_type_order', '=', 'types_of_order.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses.id', '=', 'individual_order.id_status');
        })
        ->union($ordersFirst)
        ->orderBy('created_at','DESC')
        ->get();
        foreach ($orders as $order) {
            $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
            $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
        }
    	return view('aboutMaster')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers' , 'user'=>$user, 'orders'=>$orders, 'captionToTable'=> 'Заказы оператора']);

    }
}
