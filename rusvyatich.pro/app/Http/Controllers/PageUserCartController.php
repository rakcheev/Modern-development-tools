<?php

/*
*
*Контроллер страницы
*заказа из корзины
*
*для клиентов
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use App\Order;
use App\ChangeOrder;
use App\OutFromOrder;
use App\WorkTime;
use App\TypeOfSend;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class PageUserCartController extends Controller
{
    public function index($id)
    {
        $title = 'Заказ из корзины №' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        $order = DB::table('orders')->select('orders.*', DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at`, NOW()) as raznSec'), 'users.id_operator')->where([
            ['orders.id', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
        {
            $join->on('orders.id_user', '=', 'users.id');
        })
        ->first();
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

        if(!$order || !($knifesInOrder || $knifesSerialInOrder) || (($order->id_status == REFUSED) && ($order->money_payed == 0))) {
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
            ['id_order', '=', $id]
        ])->value('id');
        return view('pageUserCart')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>'/home/orders', 'products'=>$knifesInOrder, 'productsSerial'=>$knifesSerialInOrder, 'order'=>$order, 'typeOrder'=>CART_ORDER, 'mainMasters'=>$mainMasters, 'mainOperators'=>$mainOperators, 'outer'=>$myOuter, 'minutes'=>$minutes, 'seconds'=>$seconds, 'timeWork'=>WorkTime::isWork(), 'typeOfSend' => TypeOfSend::where('id', $order->id_type_send)->get()->first()]);
    }
}
