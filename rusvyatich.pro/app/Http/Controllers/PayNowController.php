<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use App\Order;
use App\KnifePropertiesInOrder;
use App\ProductsInOrder;
use App\Steel;
use App\Blade;
use App\Bolster;
use App\Handle;
use App\HandleMaterial;
use App\SpuskInOrder;
use App\Spusk;
use App\StatusOrder;
use App\OutFromOrder;
use App\PayNow;
use App\ChangeOrder;
use App\Message;
use App\Knife;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class PayNowController extends Controller
{
    public function index($id)
    {   

        $tokenAccess = Session::has('accessToken') ? Session::get('accessToken') : null;
        if (!Hash::check($tokenAccess, PayNow::where('id_order', $id)->value('token'))) {
            Session::forget('accessToken');
            Session::forget('orderUnpayed');
            Session::save();
            return redirect('/');
        }
        $order =  DB::table('orders')
        ->select('orders.*', DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at`, NOW()) as raznSec'), 'users.id_operator', 'users.name', 'users.surname', 'users.patronymic', 'users.phone', 'users.email', 'users.region', 'users.locality', 'users.sms_alert_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.name as statusOrder')->where([
            ['orders.id', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
        {
            $join->on('orders.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses.id', '=', 'orders.id_status');
        })
        ->first();
        if (!$order || $order->id_payment === PAY_LATER || $order->id_payed == PAYED || $order->id_status == REFUSED) { 
            Session::forget('accessToken');
            Session::forget('orderUnpayed');
            Session::save();
            return redirect('/');
        }
        if ($order->raznSec >= TIME_BEFORE_ERASE) {
            ChangeOrder::eraseUnpayed();
            return redirect('/');
        }
        $rest = TIME_BEFORE_ERASE - $order->raznSec;
        $minutes = floor(($rest)/60);
        $seconds = $rest - $minutes * 60;
        if ($order->id_payment === PAY_LATER || $order->money_payed > 0) {
            $minutes = false;
            $seconds = false;
        }
        if ($order->raznSec >= TIME_BEFORE_ERASE && ($order->money_payed == 0)) {
           if ($order->id_payment !== PAY_LATER) {
                ChangeOrder::eraseUnpayed();
                return redirect()->route('home');
            } else {
                $minutes = false;
                $seconds = false;
            }
        } 
        switch ($order->id_type_order) {
            case CART_ORDER:
                $knifesInOrder =  DB::table('products_in_order')
                ->select('products_in_order.*' ,'knifes.*')->where([
                    ['products_in_order.id_order', '=', $id]
                ])
                ->join(DB::raw('(SELECT * FROM `knifes`) knifes'), function($join)
                {
                    $join->on('products_in_order.id_product', '=', 'knifes.id');
                })
                ->get();
                $title = 'Оплата';
                return view('paymentCart')->with(['title'=>$title, 'order' => $order,'products'=> $knifesInOrder, 'typeOrder'=>CART_ORDER, 'minutes'=>$minutes, 'seconds'=>$seconds]);
                break;
            case CONSTRUCT_ORDER:
                $knifeProperties = KnifePropertiesInOrder::where('id_order', $id)->first();
                $steels = Steel::orderBy('popularity')->get();
                $blades = Blade::orderBy('popularity')->get();
                $bolsters = Bolster::orderBy('popularity')->get();
                $handles = Handle::orderBy('popularity')->get();
                $handleMaterials = HandleMaterial::orderBy('popularity')->get();
                $additions =  DB::table('additionOfBlade')
                ->select('additionOfBlade.*')->where([
                    ['additionInOrder.id_order', '=', $id]
                ])
                ->join(DB::raw('(SELECT * FROM `additionInOrder`) additionInOrder'), function($join)
                {
                    $join->on('additionOfBlade.id', '=', 'additionInOrder.id_addition');
                })->get();
                $title = 'Оплата';
                return view('paymentConstruct')->with(['title'=>$title, 'order' => $order, 'properties'=>$knifeProperties, 'steels'=>$steels, 'blades'=>$blades, 'bolsters'=>$bolsters, 'handles'=>$handles, 'handleMaterials'=>$handleMaterials, 'typeOrder'=>CONSTRUCT_ORDER, 'minutes'=>$minutes, 'seconds'=>$seconds, 'spusk'=>Spusk::where('id', SpuskInOrder::where('id_order', $id)->value('id_spusk'))->value('name'), 'additions'=>$additions]);
                break;
            default:
                return redirect('/');
                break;
        }
    }

    /*Оплатить сейчас для авторизованных*/
    public function authPay($id) 
    {
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $order =  DB::table('orders')
        ->select('orders.*', DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at`, NOW()) as raznSec'), 'users.id_operator', 'users.name', 'users.surname', 'users.patronymic', 'users.phone', 'users.email', 'users.region', 'users.locality', 'users.sms_alert_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.name as statusOrder')->where([
            ['orders.id', '=', $id],
            ['orders.id_user', '=', $userId ]
        ])
        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
        {
            $join->on('orders.id_user', '=', 'users.id');
        })
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses.id', '=', 'orders.id_status');
        })
        ->first();
        if (!$order || $order->id_payment === PAY_LATER || $order->id_payed == PAYED || $order->id_status == REFUSED) {
            return redirect('/');
        }
        if ($order->raznSec >= TIME_BEFORE_ERASE) {
            ChangeOrder::eraseUnpayed();
            return redirect('/');
        }
        $rest = TIME_BEFORE_ERASE - $order->raznSec;
        $minutes = floor(($rest)/60);
        $seconds = $rest - $minutes * 60;
        if ($order->id_payment === PAY_LATER || $order->money_payed > 0) {
            $minutes = false;
            $seconds = false;
        }
        if ($order->raznSec >= TIME_BEFORE_ERASE && ($order->money_payed == 0)) {
            if ($order->id_payment === PAY_LATER) {
                ChangeOrder::eraseUnpayed();
                return redirect()->route('home');
            } else {
                $minutes = false;
                $seconds = false;
            }
        } 
        switch ($order->id_type_order) {
            case CART_ORDER:
                $knifesInOrder =  DB::table('products_in_order')
                ->select('products_in_order.*' ,'knifes.*')->where([
                    ['products_in_order.id_order', '=', $id]
                ])
                ->join(DB::raw('(SELECT * FROM `knifes`) knifes'), function($join)
                {
                    $join->on('products_in_order.id_product', '=', 'knifes.id');
                })
                ->get();
                $title = 'Оплата';
                return view('paymentCart')->with(['title'=>$title, 'order' => $order,'products'=> $knifesInOrder, 'typeOrder'=>CART_ORDER, 'minutes'=>$minutes, 'seconds'=>$seconds]);
                break;
            case CONSTRUCT_ORDER:
                $knifeProperties = KnifePropertiesInOrder::where('id_order', $id)->first();
                $steels = Steel::orderBy('popularity')->get();
                $blades = Blade::orderBy('popularity')->get();
                $bolsters = Bolster::orderBy('popularity')->get();
                $handles = Handle::orderBy('popularity')->get();
                $handleMaterials = HandleMaterial::orderBy('popularity')->get();
                $additions =  DB::table('additionOfBlade')
                ->select('additionOfBlade.*')->where([
                    ['additionInOrder.id_order', '=', $id]
                ])
                ->join(DB::raw('(SELECT * FROM `additionInOrder`) additionInOrder'), function($join)
                {
                    $join->on('additionOfBlade.id', '=', 'additionInOrder.id_addition');
                })->get();
                $title = 'Оплата';
                return view('paymentConstruct')->with(['title'=>$title, 'order' => $order, 'properties'=>$knifeProperties, 'steels'=>$steels, 'blades'=>$blades, 'bolsters'=>$bolsters, 'handles'=>$handles, 'handleMaterials'=>$handleMaterials, 'typeOrder'=>CONSTRUCT_ORDER, 'minutes'=>$minutes, 'seconds'=>$seconds, 'spusk'=>Spusk::where('id', SpuskInOrder::where('id_order', $id)->value('id_spusk'))->value('name'), 'additions'=>$additions]);
                break;
            default:
                return redirect('/');
                break;
        }

    }

    /*Отказ от заказа и удаление Аккаунта*/
    public function refuseOrderUnauth(request $request)
    {
        $tokenAccess = Session::has('accessToken') ? Session::get('accessToken') : null;
        $orderId = Session::has('orderUnpayed') ? Session::get('orderUnpayed') : null;
        $userId = Order::where('id', $orderId)->value('id_user');
        $typeOrder = Order::where('id', $orderId)->value('id_type_order');
        if (!Hash::check($tokenAccess, PayNow::where('id_order', $orderId)->value('token'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }

        switch ($typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    Order::where([
                        ['id', '=', $orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])->update(['id_status'=>REFUSED]);
                    $message = new Message();
                    $message->id_order = $orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', REFUSED)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    $ids = ProductsInOrder::select('id_product')->where('id_order', $orderId)->get()->toArray();

                    Order::where([
                        ['id', '=', $orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])->update(['id_status'=>REFUSED]);
                    Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]); 
                    $message = new Message();
                    $message->id_order = $orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', REFUSED)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            
            default:
                $resData['success'] = 0;
                echo json_encode($resData);
                return;
        }
        Session::forget('accessToken');
        Session::forget('orderUnpayed');
        Session::save();
        $ordersFirst =  DB::table('orders')
                    ->select('orders.id', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), 'orders.sum_of_order', 'orders.id_type_order', 'orders.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc limit 1) as message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where([
                        ['orders.id_user', '=', $userId],
                        ['orders.id_status', '<>', REFUSED]
                    ])
                    ->join(DB::raw('(SELECT * FROM `types_of_order`) types_of_order'), function($join)
                    {
                        $join->on('orders.id_type_order', '=', 'types_of_order.id');
                    })
                    ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
                    {
                        $join->on('order_statuses.id', '=', 'orders.id_status');
                    });
                $orders =  DB::table('individual_order')
                    ->select('individual_order.id', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), 'individual_order.sum_of_order', 'individual_order.id_type_order', 'individual_order.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc limit 1) as message'), DB::raw('(select count(*) from `messages` where `id_individual` =  `individual_order`.`id` and `messages`.`created_at` >' . DB::raw('(select `time_outer` from `outersFromOrder` where `outersFromOrder`.`id_individual` = `individual_order`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))
                    ->where([
                        ['individual_order.id_user', '=', $userId],
                        ['individual_order.id_status', '<>', REFUSED]
                    ])
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
                    ->get()
                    ->count();
        if ($orders > 0) {  
            $resData['success'] = 0;
            $resData['note'] = "У Вас есть активные заказы";
            echo json_encode($resData);
            return;
        }
        try {   
            $user = UserOwn::find($userId);
            $user->account_status_id = DELETED;
            $user->save();
        } catch (\Exception $e) {   
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }   
        $resData['success'] = 1;
        echo json_encode($resData);
        return;
    }
}
