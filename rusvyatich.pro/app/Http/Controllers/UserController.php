<?php

/*
*
*Контроллер страницы
*личного кабинета пользователя
*
*для всех
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use Session;
use Cookie;

ignore_user_abort(true);
set_time_limit(0);

class UserController extends Controller
{	
	/*
	*
	*получение главной страницы
	*профиля
	*/
    public function index()
    {
        $title = 'Личный кабинет';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
		$user = UserOwn::find($userId);
    		return view('user')->with(['title'=>$title, 'toUser'=>"#", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers' , 'user'=>$user]);
    }

	/*
	*
	*изменение личных данных
	*
	*/
    public function changeUser(request $request)
    {	
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
        
		$name = $request->name;
		$surname = $request->surname;
		$patronymic = $request->patronymic;
		$locality = $request->locality;
		$street = $request->street;
		$house = $request->house;
		$flat = $request->flat;
		$region = $request->region;
        $email = $request->email;
		$mailIndex = $request->mailIndex;
		if ($name == '' || $surname == '' || $patronymic == '' || $locality == '' || $street == '' || $house == '' || $region == '' || $mailIndex == '' /*|| $request->sms_alert == ''*/ || $request->zoneInd == '' ||  $email == '') {
            $resData['success'] = 0;
            $resData['note'] = 'Не всё указано';
			echo json_encode($resData);
			return;
		}
		try {	
			$user = UserOwn::find($userId);
			$user->name = $name;
			$user->surname = $surname;
			$user->patronymic = $patronymic;
			$user->locality = $locality;
			$user->street = $street;
			$user->house = $house;
			$user->flat = $flat;
			$user->region = $region;
			$user->mail_index = $mailIndex;
            $user->email = $email;
			//$user->sms_alert_id = $request->sms_alert;
			$user->id_area = $request->zoneInd;
			$user->save();
		} catch (\Exception $e) {	
            $resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
		$resData = array();
		$resData['success'] = 1;
		echo json_encode($resData);
    }

	/*
	*
	*Удаление пользователя (Не активнен)
	*
	*/
    public function dropUser(request $request)
    {	
           
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
        
        if ($status !== CUSTOMER) {
            $resData['success'] = 0;
            $resData['res'] = ACCESS_ERROR;
			echo json_encode($resData);
			return;
        }
        $ordersFirst =  DB::table('orders')
                    ->select('orders.id', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), 'orders.sum_of_order', 'orders.id_type_order', 'orders.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc limit 1) as message'), DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where([
                        ['orders.id_user', '=', $userId],
                        ['orders.id_status', '<>', REFUSED],
                        ['orders.id_status', '<>', DONE]
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
                        ['individual_order.id_status', '<>', REFUSED],
                        ['individual_order.id_status', '<>', DONE]
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
            $resData['note'] = "Ошибка. Отмените все активные заказы и повторите процедуру";
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
		Session::forget('userId');
		Session::forget('status');
		Session::forget('token');
		$log = Cookie::forget('login');
		$tok = Cookie::forget('rememberme');
        Session::save();
		$resData['success'] = 1;
		return response()->json($resData)->withCookie($tok)->withCookie($log);
    }

	/*
	*
	*Удаление пользователя (Не активнен)
	*
	*/
    public function dropUserByOperator(request $request)
    {	
    	if(!$request->userId) {
            $resData['success'] = 0;
            $resData['res'] = $request->userId;
			echo json_encode($resData);
			return;
    	}
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
           
        if (!($status == OPERATOR || $status == MAIN_OPERATOR)) {
            $resData['success'] = 0;
            $resData['res'] = ACCESS_ERROR;
			echo json_encode($resData);
			return;
        }try {
        	
        $ordersFirst =  DB::table('orders')
                    ->select('orders.id', DB::raw('(select DATE(`orders`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`orders`.`created_at`)) as TimeCreate'), 'orders.sum_of_order', 'orders.id_type_order', 'orders.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_order` =  `orders`.`id` order by `created_at` desc limit 1) as message'))->where([
                        ['orders.id_user', '=', $request->userId],
                        ['orders.id_status', '<>', REFUSED],
                        ['orders.id_status', '<>', DONE]
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
                    ->select('individual_order.id', DB::raw('(select DATE(`individual_order`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`individual_order`.`created_at`)) as TimeCreate'), 'individual_order.sum_of_order', 'individual_order.id_type_order', 'individual_order.created_at', 'types_of_order.name as orderType' , 'order_statuses.name as statusOrder', DB::raw('(select message from `messages` where `id_individual` =  `individual_order`.`id` order by `created_at` desc limit 1) as message'))
                    ->where([
                        ['individual_order.id_user', '=', $request->userId],
                        ['individual_order.id_status', '<>', REFUSED],
                        ['individual_order.id_status', '<>', DONE]
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
        } catch (\Exception $e) {
        $resData['success'] = 0;
            $resData['rz'] = 1;
            $resData['rzr'] = $e->getMessage();
			echo json_encode($resData);
			return;
        	
        }
        if ($orders > 0) {	
            $resData['success'] = 0;
            $resData['note'] = "Ошибка. Отмените все активные заказы и повторите процедуру";
			echo json_encode($resData);
			return;
        }
		try {
			$user = UserOwn::find($request->userId);
			$user->account_status_id = DELETED;
            $token = (str_random(4)).'userWasDeleted';
            $user->tok = Hash::make($token);
            $token = (str_random(4)).'userWasDeleted';
            $user->remember_me = Hash::make($token);
			$user->save();
		} catch (\Exception $e) {	
            $resData['success'] = 0;
            $resData['sudccess'] = $e->getMessage();
			echo json_encode($resData);
			return;
		}
		$resData['success'] = 1;
		return response()->json($resData);
    }
}



