<?php

/*
*
*Модель для отправки sms сообщений в
*зависимости от изменения заказа
*
*/

namespace App;

use Illuminate\Support\Facades\DB;
use App\Sms;
use App\UserOwn;

class SmsMessage 
{
	/*Смс о статусах*/
    public function statusMessage($statusId, $typeOrder , $orderId)
    {	
    	switch ($statusId) {
    		case ENTERED:
    			$message = false;
    			break;
    		case CONFIRMED:
    			$message = false;
    			break;
    		case CONVERSATION:
    			$message = false;
    			break;
    		case MADE:
    			$message = false;
    			break;
    		case READY:
    			$message = "Заказ готов. Вы можете посмотреть фото изделия в кабинете";
    			break;
    		case PENDING:
    			$message = false;
    			break;
    		case SENT:
    			$message = false;
    			break;
    		case ONTHEPOSTOFFICE:
    			$message = false;
    			break;
    		case DELIVERED:
    			$message = false;
    			break;
    		case REFUSED:
    			$message = false;
    			break;
    		case DONE:
    			$message = false;
    			break;
    		
    		default:
    			$message = false;
    			break;
    	}
    	if (!$message) {
    		return true;
    	} else {

	    	switch ($typeOrder) {
	    		case CONSTRUCT_ORDER:
	    		case CART_ORDER:
	                $phone =  DB::table('orders')->where([
	                    ['orders.id', '=', $orderId],
	                    ['users.sms_alert_id', '=', SEND_SMS],
	            		['users.account_status_id', '=', ACTIVE]
	                ])
	                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
	                        {
	                            $join->on('orders.id_user', '=', 'users.id');
	                        })
	                        ->value('phone');
	    			break;
	    		case IMAGE_ORDER:
	                $phone =  DB::table('individual_order')->where([
	                            ['individual_order.id', '=', $orderId],
	                    		['users.sms_alert_id', '=', SEND_SMS], //стоит галочка отправлять sms
	            				['users.account_status_id', '=', ACTIVE]
	                        ])
	                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
	                        {
	                            $join->on('individual_order.id_user', '=', 'users.id');
	                        })
	                        ->value('phone');
	    			break;
	    		
	    		default:
	    			return false;
	    			break;
	    	}
	    	if (!$phone) {
	    		return true;
	    	}
    		$sms = new Sms();
			$resSms = $sms->sendSms($phone, $message);
			return $resSms;
    	}
    	return true;
    }

    /*Sms о установках мастеров*/
    public function masterMessage($message, $typeOrder , $orderId)
    {	
	    switch ($typeOrder) {
	   		case CONSTRUCT_ORDER:
	    	case CART_ORDER:
	            $phone =  DB::table('orders')->where([
	                ['orders.id', '=', $orderId],
	                ['users.sms_alert_id', '=', SEND_SMS],  //стоит галочка отправлять sms
	            	['users.account_status_id', '=', ACTIVE]
	            ])
	        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
	        {
			    $join->on('orders.id_user', '=', 'users.id');
	        })
	        ->value('phone');
	    	break;
	    case IMAGE_ORDER:
	        $phone =  DB::table('individual_order')->where([
	            ['individual_order.id', '=', $orderId],
	            ['users.sms_alert_id', '=', SEND_SMS], //стоит галочка отправлять sms
	            ['users.account_status_id', '=', ACTIVE]

	        ])
	        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
	        {
	            $join->on('individual_order.id_user', '=', 'users.id');
	        })
	        ->value('phone');
	    	break;
	    		
	    default:
	    	return false;
	    	break;
	    }
	    if (!$phone) {
	    	return false;
	    }
    	$sms = new Sms();
		$resSms = $sms->sendSms($phone, $message);
		return $resSms;
    }

}
