<?php

/*
*
*Контроллер страницы
*заказа из конструктора
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
use App\KnifePropertiesInOrder;
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

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class PageUserConstructController extends Controller
{
    public function index($id)
    {
        $title = 'Заказ по конструктору №' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
 		$order =  DB::table('orders')
        ->select('orders.*', DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at`, NOW()) as raznSec'),  'users.id_operator', 'users.name', 'users.surname', 'users.patronymic', 'users.phone', 'users.region', 'users.locality', 'users.sms_alert_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.name as statusOrder')->where([
		    ['orders.id', '=', $id],
		    ['orders.id_user', '=', $userId]
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
        if(!$order || (($order->id_status == REFUSED) && ($order->money_payed == 0))) {
            return redirect()->route('home');
        }
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
        $statusesOrder = StatusOrder::all();
        $knifeProperties = KnifePropertiesInOrder::where('id_order', $id)->first();
        $steels = Steel::orderBy('popularity')->get();
        $blades = Blade::orderBy('popularity')->get();
        $bolsters = Bolster::orderBy('popularity')->get();
        $handles = Handle::orderBy('popularity')->get();
        $handleMaterials = HandleMaterial::orderBy('popularity')->get();
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
        return view('pageUserConstruct')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>'/home/orders', 'properties'=>$knifeProperties, 'statusesOrder'=> $statusesOrder, 'steels'=>$steels, 'blades'=>$blades, 'bolsters'=>$bolsters, 'handles'=>$handles, 'handleMaterials'=>$handleMaterials, 'order'=>$order, 'typeOrder'=>CONSTRUCT_ORDER, 'mainMasters'=>$mainMasters, 'mainOperators'=>$mainOperators, 'outer'=>$myOuter, 'minutes'=>$minutes, 'seconds'=>$seconds, 'timeWork'=>WorkTime::isWork(), 'spusk'=>Spusk::where('id', SpuskInOrder::where('id_order', $id)->value('id_spusk'))->value('name'), 'additions'=>$additions, 'typeOfSend' => TypeOfSend::where('id', $order->id_type_send)->get()->first()]);
    }

}
