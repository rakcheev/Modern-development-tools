<?php

/*
*
*Контроллер первой страницы
*при входе в кабинет
*
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use App\CheckChange;
use App\OutFromOrder;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Заказы';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        switch ($status) {
            case CUSTOMER:
                return view('homeCustomer')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>'#']);
                break;
            case MASTER:
                return view('homeCustomer')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>'#'/*', orders'=>$orders*/]);
                break;
            case MAIN_MASTER:
                if(\Route::currentRouteName() == 'customerHome') {
                    return view('homeCustomer')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'constructOrdersLink'=>"#", 'individualOrdersLink'=>"/home/individualOrders", 'cartOrdersLink'=>"/home/cartOrders", 'typeId'=>1]);

                } else {
                    return view('homeAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"#", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'constructOrdersLink'=>"#", 'individualOrdersLink'=>"/home/individualOrders", 'cartOrdersLink'=>"/home/cartOrders", 'allOrdersLink'=>"/home/allOrders", 'typeId'=>1]);
                }
                break;
            case OPERATOR:
            case MAIN_OPERATOR:
                return view('homeAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"#", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'constructOrdersLink'=>"#", 'individualOrdersLink'=>"/home/individualOrders", 'cartOrdersLink'=>"/home/cartOrders", 'allOrdersLink'=>"/home/allOrders", 'typeId'=>1]);
                break;
            case ADMIN:
                return view('homeAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"#", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'constructOrdersLink'=>"#", 'individualOrdersLink'=>"/home/individualOrders", 'cartOrdersLink'=>"/home/cartOrders", 'allOrdersLink'=>"/home/allOrders", 'typeId'=>1]);
                break;
            default:
                return redirect('/');
        }
    }

    /*
    *
    *Получение заказов по типам
    *для realtime подгрузки
    *
    *return json
    */
    public function getOrders(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        
        $typeId = $request->typeOfOrder;
        $resData = array();
        if (!$typeId){
            $resData['success'] = 0;
            $resData['orders'] = null;
            echo json_encode($resData);
            return;
        }
        $resData['success'] = 0;
        $resData['orders'] = null;
        $newConstruct = 0;
        $newIndividual = 0;
        $newCart = 0;
        switch ($typeId) {
            case ALL_ACTIVE_ORDERS:
                try {
                    switch ($status) {
                        case OPERATOR:
                            $whereNewCartArray = [
                                ['users.id_operator','=', $userId],
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '<>', REFUSED]
                            ];
                            $whereNewIndividualArray = [
                                ['users.id_operator','=', $userId],
                                ['individual_order.id_viewed','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED]
                            ];
                            $whereNewConstructArray = [
                                ['users.id_operator','=', $userId],
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                                ['orders.id_status', '<>', REFUSED]
                            ];
                            break;
                        case MAIN_MASTER:
                            $whereNewCartArray = [
                                ['orders.id_viewed_master','=', 1],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                //['orders.id_status', '<>', ENTERED]
                            ];
                            $whereNewIndividualArray = [
                                ['individual_order.id_viewed_master','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                //['individual_order.id_status', '<>', ENTERED]
                            ];
                            $whereNewConstructArray = [
                                ['orders.id_viewed_master','=', 1],
                                ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                //['orders.id_status', '<>', ENTERED]
                            ];
                            break;
                        default:
                            $whereNewCartArray = [
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '<>', REFUSED]
                            ];
                            $whereNewIndividualArray = [
                                ['individual_order.id_viewed','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED]
                            ];
                            $whereNewConstructArray = [
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                                ['orders.id_status', '<>', REFUSED]
                            ];
                            break;
                    }
                    $newCart =  DB::table('orders')->select('orders.*', 'users.id_operator')->where($whereNewCartArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'orders.id_user');
                        }
                        )
                        ->count();
                    $newIndividual = DB::table('individual_order')->select('individual_order.*', 'users.id_operator')->where($whereNewIndividualArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'individual_order.id_user');
                        }
                        )
                        ->count();
                    $newConstruct = DB::table('orders')->select('orders.*', 'users.id_operator')->where($whereNewConstructArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'orders.id_user');
                        }
                        )
                        ->count();

                } catch (\Exception $e) {
                    $resData['orderdds'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;
                }
                switch ($status) {
                    case MAIN_MASTER:
                        $whereArray = [
                            //['id_status', '<>', ENTERED],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['orders.id_status', '=', REFUSED],
                            ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        $whereArrayIndividual = [
                            //['id_status', '<>', ENTERED],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArrayIndividual = [
                            ['individual_order.id_status', '=', REFUSED],
                            ['individual_order.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                    case OPERATOR:
                        $whereArray = [
                            ['id_operator', '=', $userId],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['users.id_operator', '=', $userId],
                            ['orders.id_status', '=', REFUSED],
                            ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        $whereArrayIndividual = [
                            ['id_operator', '=', $userId],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArrayIndividual = [
                            ['individual_order.id_status', '=', REFUSED],
                            ['users.id_operator', '=', $userId],
                            ['individual_order.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                    default:
                        $whereArray = [
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['orders.id_status', '=', REFUSED],
                            ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        $whereArrayIndividual = [
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArrayIndividual = [
                            ['individual_order.id_status', '=', REFUSED],
                            ['individual_order.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                }
                try {
                $ordersFirst =  DB::table('orders')
                        ->select('orders.id', 'orders.created_at', 'orders.sum_of_order', 'orders.id_viewed', 'orders.id_viewed_master', 'orders.id_type_order', 'types_of_order.name as orderType', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), DB::raw('(select DATE(`orders`.`updated_at`)) as DateUpdate'), DB::raw('(select TIME(`orders`.`updated_at`)) as TimeUpdate'), 'users.name', 'users.phone', 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where($whereArray)->orWhere($orWhereArray)
                    ->join(DB::raw('(SELECT * FROM `types_of_order`) types_of_order'), function($join)
                    {
                        $join->on('orders.id_type_order', '=', 'types_of_order.id');
                    })
                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
                    {
                        $join->on('orders.id_user', '=', 'users.id');
                    })
                    ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
                    {
                        $join->on('order_statuses.id', '=', 'orders.id_status');
                    });
                $orders =  DB::table('individual_order')
                    ->select('individual_order.id', 'individual_order.created_at', 'individual_order.sum_of_order','individual_order.id_viewed', 'individual_order.id_viewed_master', 'individual_order.id_type_order', 'types_of_order.name as orderType', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), DB::raw('(select DATE(`individual_order`.`updated_at`)) as DateUpdate'), DB::raw('(select TIME(`individual_order`.`updated_at`)) as TimeUpdate'),'users.name', 'users.phone', 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_individual` =  `individual_order`.`id` and `messages`.`created_at` >' . DB::raw('(select `time_outer` from `outersFromOrder` where `outersFromOrder`.`id_individual` = `individual_order`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where($whereArrayIndividual)->orWhere($orWhereArrayIndividual)
                    ->join(DB::raw('(SELECT * FROM `types_of_order`) types_of_order'), function($join)
                    {
                        $join->on('individual_order.id_type_order', '=', 'types_of_order.id');
                    })
                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
                    {
                        $join->on('individual_order.id_user', '=', 'users.id');
                    })
                    ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
                    {
                        $join->on('order_statuses.id', '=', 'individual_order.id_status');
                    })
                    ->union($ordersFirst)
                    ->orderBy('created_message', 'DESC')
                    ->orderBy('created_at','DESC')
                    ->get();
                    foreach ($orders as $order) {
                        $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
                        $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
                        $order->DateUpdate = date("d.m.Y ", strtotime($order->DateUpdate ));
                        $order->TimeUpdate = date("G:i", strtotime($order->TimeUpdate));
                    }
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['mss'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;
                }
                break;

            case CONSTRUCT_ORDER:
                try {
                    switch ($status) {
                        case OPERATOR:
                            $whereNewCartArray = [
                                ['users.id_operator','=', $userId],
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                ['orders.id_status', '<>', DONE]
                            ];
                            $whereNewIndividualArray = [
                                ['users.id_operator','=', $userId],
                                ['individual_order.id_viewed','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                ['individual_order.id_status', '<>', DONE]
                            ];
                            break;
                        case MAIN_MASTER:
                            $whereNewCartArray = [
                                ['orders.id_viewed_master','=', 1],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                //['orders.id_status', '<>', ENTERED],
                                ['orders.id_status', '<>', DONE]
                            ];
                            $whereNewIndividualArray = [
                                ['individual_order.id_viewed_master','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                //['individual_order.id_status', '<>', ENTERED],
                                ['individual_order.id_status', '<>', DONE]
                            ];
                            break;

                        default:
                            $whereNewCartArray = [
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                ['orders.id_status', '<>', DONE]
                            ];
                            $whereNewIndividualArray = [
                                ['individual_order.id_viewed','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                ['individual_order.id_status', '<>', DONE]
                            ];
                            break;
                    }
                    $newCart =  DB::table('orders')->select('orders.*', 'users.id_operator')->where($whereNewCartArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'orders.id_user');
                        }
                        )
                        ->count();
                    $newIndividual = DB::table('individual_order')->select('individual_order.*', 'users.id_operator')->where($whereNewIndividualArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'individual_order.id_user');
                        }
                        )
                        ->count();

                } catch (\Exception $e) {
                    $resData['orderdds'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;
                }
                switch ($status) {
                    case MAIN_MASTER:
                        $whereArray = [
                            ['id_type_order', '=', CONSTRUCT_ORDER],
                            //['id_status', '<>', ENTERED],
                            ['id_status', '<>', DONE],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                            ['orders.id_status', '=', REFUSED],
                            ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                    case OPERATOR:
                        $whereArray = [
                            ['id_type_order', '=', CONSTRUCT_ORDER],
                            ['id_status', '<>', DONE],
                            ['id_operator', '=', $userId],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['users.id_operator', '=', $userId],
                            ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                            ['orders.id_status', '=', REFUSED],
                            ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                    default:
                        $whereArray = [
                            ['id_type_order', '=', CONSTRUCT_ORDER],
                            ['id_status', '<>', DONE],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                            ['orders.id_status', '=', REFUSED],
                            ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                }
                try {
                    $orders =  DB::table('orders')
                        ->select('orders.*', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), DB::raw('(select DATE(`orders`.`updated_at`)) as DateUpdate'), DB::raw('(select TIME(`orders`.`updated_at`)) as TimeUpdate'), 'users.name', 'users.phone', 'users.locality', 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))
                        ->where($whereArray)
                        //->orWhere($orWhereArray) видеть отмененные три дня давности
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
                        {
                            $join->on('orders.id_user', '=', 'users.id');
                        })
                        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
                        {
                            $join->on('order_statuses.id', '=', 'orders.id_status');
                        })
                        ->orderBy('created_message', 'DESC')
                        ->orderBy('orders.created_at', 'DESC')
                        ->get();
                    foreach ($orders as $order) {
                        $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
                        $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
                        $order->DateUpdate = date("d.m.Y ", strtotime($order->DateUpdate ));
                        $order->TimeUpdate = date("G:i", strtotime($order->TimeUpdate));
                    }
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['mss'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;
                }
                break;
            case IMAGE_ORDER:
                switch ($status) {
                    case OPERATOR:
                        $whereNewCartArray = [
                            ['users.id_operator','=', $userId],
                            ['orders.id_viewed','=', 1],
                            ['orders.id_type_order', '=', CART_ORDER],
                            ['orders.id_status', '<>', REFUSED],
                            ['orders.id_status', '<>', DONE]
                        ];
                        $whereNewConstructArray = [
                            ['users.id_operator','=', $userId],
                            ['orders.id_viewed','=', 1],
                            ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                            ['orders.id_status', '<>', REFUSED],
                            ['orders.id_status', '<>', DONE]
                        ];
                        break;
                    case MAIN_MASTER:
                        $whereNewCartArray = [
                            ['orders.id_viewed_master','=', 1],
                            ['orders.id_type_order', '=', CART_ORDER],
                            ['orders.id_status', '<>', REFUSED],
                            //['orders.id_status', '<>', ENTERED],
                            ['orders.id_status', '<>', DONE]
                        ];
                        $whereNewConstructArray = [
                            ['orders.id_viewed_master','=', 1],
                            ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                            ['orders.id_status', '<>', REFUSED],
                            //['orders.id_status', '<>', ENTERED],
                            ['orders.id_status', '<>', DONE]
                        ];
                        break;

                    default:
                        $whereNewCartArray = [
                            ['orders.id_viewed','=', 1],
                            ['orders.id_type_order', '=', CART_ORDER],
                            ['orders.id_status', '<>', REFUSED],
                            ['orders.id_status', '<>', DONE]
                        ];
                        $whereNewConstructArray = [
                            ['orders.id_viewed','=', 1],
                            ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                            ['orders.id_status', '<>', REFUSED],
                            ['orders.id_status', '<>', DONE]
                        ];
                        break;
                }
                $newCart =  DB::table('orders')->select('orders.*', 'users.id_operator')->where($whereNewCartArray)
                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                    {
                        $join->on('users.id', '=', 'orders.id_user');
                    }
                    )
                    ->count();
                $newConstruct = DB::table('orders')->select('orders.*', 'users.id_operator')->where($whereNewConstructArray)
                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                    {
                        $join->on('users.id', '=', 'orders.id_user');
                    }
                    )
                    ->count();
                switch ($status) {
                    case MAIN_MASTER:
                        $whereArray = [
                            ['id_type_order', '=', IMAGE_ORDER],
                            //['id_status', '<>', ENTERED],
                            ['id_status', '<>', DONE],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['individual_order.id_type_order', '=', IMAGE_ORDER],
                            ['individual_order.id_status', '=', REFUSED],
                            ['individual_order.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                    case OPERATOR:
                        $whereArray = [
                            ['id_type_order', '=', IMAGE_ORDER],
                            ['id_status', '<>', DONE],
                            ['id_operator', '=', $userId],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['users.id_operator', '=', $userId],
                            ['individual_order.id_type_order', '=', IMAGE_ORDER],
                            ['individual_order.id_status', '=', REFUSED],
                            ['individual_order.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                    default:
                        $whereArray = [
                            ['id_type_order', '=', IMAGE_ORDER],
                            ['id_status', '<>', DONE],
                            ['id_status', '<>', REFUSED]
                        ];
                        $orWhereArray = [
                            ['individual_order.id_type_order', '=', IMAGE_ORDER],
                            ['individual_order.id_status', '=', REFUSED],
                            ['individual_order.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                        ];
                        break;
                }
                $orders =  DB::table('individual_order')
                    ->select('individual_order.*', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), DB::raw('(select DATE(`individual_order`.`updated_at`)) as DateUpdate'), DB::raw('(select TIME(`individual_order`.`updated_at`)) as TimeUpdate'),'users.name', 'users.phone', 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_individual` =  `individual_order`.`id` and `messages`.`created_at` >' . DB::raw('(select `time_outer` from `outersFromOrder` where `outersFromOrder`.`id_individual` = `individual_order`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))
                    ->where($whereArray)
                  //->orWhere($orWhereArray) видеть отмененные три дня давности
                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
                    {
                        $join->on('individual_order.id_user', '=', 'users.id');
                    })
                    ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
                    {
                        $join->on('order_statuses.id', '=', 'individual_order.id_status');
                    })
                    ->orderBy('created_message', 'DESC')
                    ->orderBy('individual_order.created_at', 'DESC')
                    ->get();
                foreach ($orders as $order) {
                    $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
                    $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
                    $order->DateUpdate = date("d.m.Y ", strtotime($order->DateUpdate ));
                    $order->TimeUpdate = date("G:i", strtotime($order->TimeUpdate));
                }
               
                break;
            case CART_ORDER:
                try {
                    switch ($status) {
                        case OPERATOR:
                            $whereNewConstructArray = [
                                ['users.id_operator','=', $userId],
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                ['orders.id_status', '<>', DONE]
                            ];
                            $whereNewIndividualArray = [
                                ['users.id_operator','=', $userId],
                                ['individual_order.id_viewed','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                ['individual_order.id_status', '<>', DONE]
                            ];
                            break;
                        case MAIN_MASTER:
                            $whereNewConstructArray = [
                                ['orders.id_viewed_master','=', 1],
                                ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                //['orders.id_status', '<>', ENTERED],
                                ['orders.id_status', '<>', DONE]
                            ];
                            $whereNewIndividualArray = [
                                ['individual_order.id_viewed_master','=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                //['individual_order.id_status', '<>', ENTERED],
                                ['individual_order.id_status', '<>', DONE]
                            ];
                            break;

                        default:
                            $whereNewConstructArray = [
                                ['orders.id_viewed','=', 1],
                                ['orders.id_type_order', '=', CONSTRUCT_ORDER],
                                ['orders.id_status', '<>', REFUSED],
                                ['orders.id_status', '<>', DONE]
                            ];
                            $whereNewIndividualArray = [
                                ['individual_order.id_viewed', '=', 1],
                                ['individual_order.id_type_order', '=', IMAGE_ORDER],
                                ['individual_order.id_status', '<>', REFUSED],
                                ['individual_order.id_status', '<>', DONE]
                            ];
                            break;
                    }
                    $newIndividual = DB::table('individual_order')->select('individual_order.*', 'users.id_operator')->where($whereNewIndividualArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'individual_order.id_user');
                        }
                        )
                        ->count();
                    $newConstruct = DB::table('orders')->select('orders.*', 'users.id_operator')->where($whereNewConstructArray)
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join) use ($userId)
                        {
                            $join->on('users.id', '=', 'orders.id_user');
                        }
                        )
                        ->count();
                    switch ($status) {
                        case MAIN_MASTER:
                            $whereArray = [
                                ['id_type_order', '=', CART_ORDER],
                                //['id_status', '<>', ENTERED],
                                ['id_status', '<>', DONE],
                                ['id_status', '<>', REFUSED]
                            ];
                            $orWhereArray = [
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '=', REFUSED],
                                ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                            ];
                            break;
                        case OPERATOR:
                            $whereArray = [
                                ['id_type_order', '=', CART_ORDER],
                                ['id_status', '<>', DONE],
                                ['id_operator', '=', $userId],
                                ['id_status', '<>', REFUSED]
                            ];
                            $orWhereArray = [
                                ['users.id_operator', '=', $userId],
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '=', REFUSED],
                                ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                            ];
                            break;
                        default:
                            $whereArray = [
                                ['id_type_order', '=', CART_ORDER],
                                ['id_status', '<>', DONE],
                                ['id_status', '<>', REFUSED]
                            ];
                            $orWhereArray = [
                                ['orders.id_type_order', '=', CART_ORDER],
                                ['orders.id_status', '=', REFUSED],
                                ['orders.updated_at', '>', DB::raw('(SELECT DATE_ADD(NOW(),INTERVAL -1 DAY))')]
                            ];
                    }
                    $orders =  DB::table('orders')
                        ->select('orders.*', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), DB::raw('(select DATE(`orders`.`updated_at`)) as DateUpdate'), DB::raw('(select TIME(`orders`.`updated_at`)) as TimeUpdate'), 'users.name', 'users.phone', 'users.locality', 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where($whereArray)
                      //->orWhere($orWhereArray) видеть отмененные три дня давности
                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
                        {
                            $join->on('orders.id_user', '=', 'users.id');
                        })
                        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
                        {
                            $join->on('order_statuses.id', '=', 'orders.id_status');
                        })
                        ->orderBy('created_message', 'DESC')
                        ->orderBy('orders.created_at', 'DESC')
                        ->get();
                    foreach ($orders as $order) {
                        $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
                        $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
                        $order->DateUpdate = date("d.m.Y ", strtotime($order->DateUpdate ));
                        $order->TimeUpdate = date("G:i", strtotime($order->TimeUpdate));
                    }
                    break;

                } catch (\Exception $e) {
                    $resData['gg'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;
                }
        }
        $resData['changeConstruct'] = $newConstruct;
        $resData['changeIndividual'] = $newIndividual;
        $resData['changeCart'] = $newCart;
        if ($orders) {
            $resData['orders'] = $orders;
            $resData['success'] = 1;
            $resData['changes'] = 1;
        } else {
            $resData['success'] = 1;
            $resData['changes'] = 0;
        }
        echo json_encode($resData);
    }

    /*Получение заказов для клиентов и мастеров*/
    public function getOrdersCustomer() 
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        switch ($status) {
            case CUSTOMER:
                $ordersFirst =  DB::table('orders')
                    ->select('orders.id', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), 'orders.sum_of_order', 'orders.id_type_order', 'orders.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where([
                        ['orders.id_user', '=', $userId],
                        ['orders.id_status', '<>', REFUSED]
                    ])
                    ->orWhere([
                        ['orders.id_user', '=', $userId],
                        ['orders.id_status', '=', REFUSED],
                        ['orders.money_payed', '>', 0]
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
                    ->select('individual_order.id', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), 'individual_order.sum_of_order', 'individual_order.id_type_order', 'individual_order.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_individual` =  `individual_order`.`id` and `messages`.`created_at` >' . DB::raw('(select `time_outer` from `outersFromOrder` where `outersFromOrder`.`id_individual` = `individual_order`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))
                    ->where([
                        ['individual_order.id_user', '=', $userId],
                        ['individual_order.id_status', '<>', REFUSED]
                    ])
                    ->orWhere([
                        ['individual_order.id_user', '=', $userId],
                        ['individual_order.id_status', '=', REFUSED],
                        ['individual_order.money_payed', '>', 0]
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
                    ->orderBy('created_message','DESC')
                    ->orderBy('created_at','DESC')
                    ->get();
                foreach ($orders as $order) {
                    $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
                    $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
                }
                break;
            case MASTER:
            case MAIN_MASTER:
                try {
                    $ordersFirst =  DB::table('orders')
                        ->select('orders.id', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), 'orders.sum_of_order', 'orders.id_type_order', 'orders.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where([
                            ['orders.id_master', '=', $userId],
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
                        ->select('individual_order.id', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), 'individual_order.sum_of_order', 'individual_order.id_type_order', 'individual_order.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as message'), DB::raw('(select created_at from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc, `messages`.`id` desc limit 1) as created_message'), DB::raw('(select count(*) from `messages` where `id_individual` =  `individual_order`.`id` and `messages`.`created_at` >' . DB::raw('(select `time_outer` from `outersFromOrder` where `outersFromOrder`.`id_individual` = `individual_order`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))
                        ->where([
                            ['individual_order.id_master', '=', $userId],
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
                        ->orderBy('created_message','DESC')
                        ->orderBy('created_at','DESC')
                        ->get();

                    foreach ($orders as $order) {
                        $order->DateCreate = date("d.m.Y ", strtotime($order->DateCreate ));
                        $order->TimeCreate = date("G:i", strtotime($order->TimeCreate));
                    } 
                } catch (\Exception $e) {
                    $resData['success'] = 0;

                    $resData['log'] = DB::getQueryLog();
                    $resData['mess'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;
                }
                break;
            default:
                $orders = null;
        }
        if ($orders) {
            $resData['orders'] = $orders;
            $resData['success'] = 1;
            $resData['changes'] = 1;
        } else {
            $resData['success'] = 1;
            $resData['changes'] = 0;
        }
        echo json_encode($resData);
    }
}
