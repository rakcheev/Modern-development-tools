<?php

/*
*
*Модель для отправки email сообщений в
*зависимости от изменения заказа
*
*/

namespace App;

use Illuminate\Support\Facades\DB;
use Mail;
use App\UserOwn;
use App\StatusOrder;

class EmailMessage 
{
	/*Сообщения о статусах*/
    public function statusMessage($statusId, $typeOrder , $orderId)
    {	
    	$subject = 'Cтатус заказа поменялся';
    	switch ($statusId) {
    		case ENTERED:
    			$mess = false;
    			break;
    		case CONFIRMED:
    			$mess = false;
    			break;
    		case CONVERSATION:
    			$mess = false;
    			break;
    		case MADE:
    			$subject = "Заказ изготваливается";
    			$mess = "Ваш заказ изготавливается, по готовности вам прийдет оповещение";
    			break;
    		case READY:
    			$subject = "Ваш заказ готов";
    			$mess = "Вы можете посмотреть фото готового изделия в личном кабинете";
    			break;
    		case PENDING:
    			$mess = false;
    			break;
    		case SENT:
    			$mess = false;
    			break;
    		case ONTHEPOSTOFFICE:
    			$mess = false;
    			break;
    		case DELIVERED:
    			$mess = false;
    			break;
    		case REFUSED:
    			$mess = false;
    			break;
    		case DONE:
    			$mess = false;
    			break;
    		
    		default:
    			$mess = false;
    			break;
    	}
    	if (!$mess) {
    		return true;
    	} else {

	    	switch ($typeOrder) {
	    		case CONSTRUCT_ORDER:
	    		case CART_ORDER:
	                $user =  DB::table('orders')->select('users.email', 'users.name')->where([
	                    ['orders.id', '=', $orderId],
	            		['users.account_status_id', '=', ACTIVE]
	                ])
	                    ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
	                        {
	                            $join->on('orders.id_user', '=', 'users.id');
	                        })->first();
	    			break;
	    		case IMAGE_ORDER:
	                $user =  DB::table('individual_order')->select('users.email', 'users.name')->where([
	                            ['individual_order.id', '=', $orderId],
	            				['users.account_status_id', '=', ACTIVE]
	                        ])
	                        ->join(DB::raw('(SELECT * FROM `users`) users'), function($join)
	                        {
	                            $join->on('individual_order.id_user', '=', 'users.id');
	                        })->first();
	    			break;
	    		
	    		default:
	    			return false;
	    			break;
	    	}
	    	$email = $user->email;
	    	$name = $user->name;
	    	$status = StatusOrder::where('id', $statusId)->value('name');
	    	if (!$email) {
	    		return true;
	    	}


			try {
			    Mail::send('emails.statusMail', ['orderId' => $orderId, 'typeOrder' => $typeOrder, 'status' => $status,'mess' => $mess, 'name' => $name], function ($message) use ($name, $subject, $email)
			    {	
			    	//$message->to('fhagerot23@yandex.ru', $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject($subject);
			    	$message->to($email, $name)->from('vyatich@rusvyatich.pro', 'Вятич')->subject($subject);
			    });
			    return true;   
			} catch (\Exception $e) {
				return $e->getMessage();
			}
    	}
    	return true;
    }

}
