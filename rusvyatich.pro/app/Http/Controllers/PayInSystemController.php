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

class PayInSystemController extends Controller
{
    public function index($orderType, $id)
	{
        $userId = Session::has('userId') ? Session::get('userId') : null;
        switch ($orderType) {
         	case 'ordersConstruct':
		    case 'ordersCart':
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
		        if (!$order || $order->id_payed == PAYED || $order->id_status == REFUSED) {
		            return redirect()->route('home');
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
		            	return redirect()->route('home');
		        		break;
		        }
         		break;
         	case 'ordersIndividual':
		 		$order =  DB::table('individual_order')
		        ->select('individual_order.*', 'users.id_operator', 'users.name', 'users.surname', 'users.patronymic', 'users.phone', 'users.email', 'users.region', 'users.locality', 'users.sms_alert_id', 'users.street', 'users.house', 'users.flat', 'users.mail_index', 'order_statuses.name as statusOrder')->where([
				    ['individual_order.id', '=', $id],
				    ['individual_order.id_user', '=', $userId ]
		        ])
				->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
				{
		            $join->on('individual_order.id_user', '=', 'users.id');
		        })
		        ->join(DB::raw('(SELECT * FROM `order_statuses`) order_statuses'), function($join)
				{
		            $join->on('order_statuses.id', '=', 'individual_order.id_status');
		        })
		        ->first();
		        if (!$order || $order->id_payed == PAYED || $order->id_status == REFUSED) {
		            return redirect()->route('home');
		        }
				$title = 'Оплата';
				return view('paymentImage')->with(['title'=>$title, 'order' => $order, 'typeOrder'=>IMAGE_ORDER]);
		        break;
         	default :
		        return redirect()->route('home');
         		break;
        }

	}
}
