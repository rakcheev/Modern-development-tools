<?php

/*
*
*Контроллер страницы
*заказа из конструктора
*
*для работников
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\KnifePropertiesInOrder;
use App\UserOwn;
use App\Steel;
use App\Blade;
use App\Bolster;
use App\Handle;
use App\HandleMaterial;
use App\SpuskInOrder;
use App\Spusk;
use App\StatusOrder;
use App\OutFromOrder;
use App\ChangeOrder;
use App\WorkTime;
use App\TypeOfSend;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class PageOrderConstructController extends Controller
{
    public function index($id)
    {
        $title = 'Заказ c конструктора №'.$id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        Session::put('constructMessages', 0); //указывает на надобность в загрузке сообщений
        if ($status === MAIN_OPERATOR ) DB::table('orders')->where('id', $id)->update(['id_viewed' => 2]);
        if ($status === OPERATOR ) DB::table('orders')->where('id', $id)->update(['id_viewed' => 2]);
        if ($status === MAIN_MASTER ) DB::table('orders')->where('id', $id)->update(['id_viewed_master' => 2]);
 		$order =  DB::table('orders')
        ->select('orders.*', DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at`, NOW()) as raznSec'), 'types_of_payment.name as namePayment', 'areas.fst_hours', 'areas.scnd_hours', DB::raw('users.id as user_id'), DB::raw('TIMESTAMPDIFF(SECOND, NOW(), `users`.`last_visit`) as raznOnline'),  'users.id_operator', 'users.name', 'users.surname', 'users.patronymic', 'users.phone', 'users.email', 'users.region', 'users.locality', 'users.sms_alert_id', 'users.account_status_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.id as statusId', 'order_statuses.name as statusOrder')->where([
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
        $statusesOrder =  DB::table('order_statuses_for_type_users')
        ->select('order_statuses_for_type_users.id_order_status as id', 'order_statuses.name as name')->where([
            ['id_user_type', '=', $status],
            ['id_permission', '=', '1']
        ])
        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
        {
            $join->on('order_statuses_for_type_users.id_order_status', '=', 'order_statuses.id');
        });
        $additions =  DB::table('additionOfBlade')
        ->select('additionOfBlade.*')->where([
            ['additionInOrder.id_order', '=', $id]
        ])
        ->join(DB::raw('(SELECT * FROM `additionInOrder`) additionInOrder'), function($join)
        {
            $join->on('additionOfBlade.id', '=', 'additionInOrder.id_addition');
        })->get();
        $minutes = -10;
        $seconds = -10;
        if (!$order || $order->id_payment === PAY_LATER || $order->id_payed == PAYED || $order->id_status == REFUSED){

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
        $statusesOrder  = StatusOrder::select(['id', 'name'])->where('id', $order->statusId)
        ->union($statusesOrder)
        ->orderBy('id')
        ->get();
        $knifeProperties = KnifePropertiesInOrder::where('id_order', $id)->first();
        $steels = Steel::orderBy('popularity')->get();
        $blades = Blade::orderBy('popularity')->get();
        $bolsters = Bolster::orderBy('popularity')->get();
        $handles = Handle::orderBy('popularity')->get();
        $handleMaterials = HandleMaterial::orderBy('popularity')->get();
        $myOuter = OutFromOrder::where([
            ['id_user', '=', $userId],
            ['id_order', '=', $id]
        ])->value('id');
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
        return view('pageOrderConstructAdmin')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'order' => $order, 'properties'=>$knifeProperties, 'statusesOrder'=> $statusesOrder, 'steels'=>$steels, 'blades'=>$blades, 'bolsters'=>$bolsters, 'handles'=>$handles, 'handleMaterials'=>$handleMaterials, 'masters'=>$masters, 'operators'=>$operators, 'typeOrder'=>CONSTRUCT_ORDER, 'mainMasters'=>$mainMasters, 'mainOperators'=>$mainOperators, 'outer'=>$myOuter, 'minutes'=>$minutes, 'seconds'=>$seconds, 'timeWork'=>WorkTime::isWork(), 'spusk'=>Spusk::where('id', SpuskInOrder::where('id_order', $id)->value('id_spusk'))->value('name'), 'additions'=>$additions, 'typeOfSends'=>TypeOfSend::all()]);
    }
}
