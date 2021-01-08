<?php

/*
*
*Контроллер изменений статусов
*заказов
*
*для всех
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Order;
use App\Knife;
use App\Message;
use App\IndividualOrder;
use App\StatusOrder;
use App\OutFromOrder;
use App\ProductsInOrder;
//use App\SmsMessage;
use App\EmailMessage;
use App\TypeOfSend;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class ChangeOrderController extends Controller
{

    /*Отказ от заказа*/
	public function refuseOrder(request $request)
	{
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        
        switch ($request->typeOrder) {
        	case CONSTRUCT_ORDER:
                try {
                    if (Order::where('id', $request->orderId)->value('id_status') == REFUSED) {
                            $resData['success'] = 0;
                            $resData['message'] = 'Уже изменен';
                            $resData['res'] = UNCHANGE_ERROR;
                            echo json_encode($resData);
                            return;    
                    };
                    switch ($status) {
                        case MAIN_OPERATOR:
                            Order::where([
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', CONSTRUCT_ORDER]
                            ])->update(['id_status'=>REFUSED]);
                            break;
                        case CUSTOMER:
                            Order::where([
                                ['id_user', '=', $userId],
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', CONSTRUCT_ORDER]
                            ])->update(['id_status'=>REFUSED]);
                            break;
                        case OPERATOR:
                            if (UserOwn::where('id', Order::where([
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', CONSTRUCT_ORDER]
                            ])->value('id_user'))->value('id_operator') == $userId) {
                                Order::where([
                                    ['id', '=', $request->orderId],
                                    ['id_type_order', '=', CONSTRUCT_ORDER]
                                ])->update(['id_status'=>REFUSED]);
                            } else {       
                                $resData['success'] = 0;
                                echo json_encode($resData);
                                return;       
                            }
                            break;
                        default:    
                            $resData['success'] = 0;
                            $resData['res'] = ACCESS_ERROR;
                            $resData['message'] = 'Вы не можете отменять заказ';
                            echo json_encode($resData);
                            return;      
                            break;
                    }
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', REFUSED)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
        		break;
        	case IMAGE_ORDER:
                try {
                    if (IndividualOrder::where('id', $request->orderId)->value('id_status') == REFUSED) {
                            $resData['success'] = 0;
                            $resData['message'] = 'Уже изменен';
                            $resData['res'] = UNCHANGE_ERROR;
                            echo json_encode($resData);
                            return;
                    };
                    switch ($status) {
                        case MAIN_OPERATOR:
                            IndividualOrder::where([
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', IMAGE_ORDER]
                            ])->update(['id_status'=>REFUSED]);
                            break;
                        case CUSTOMER:
                            IndividualOrder::where([
                                ['id_user', '=', $userId],
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', IMAGE_ORDER]
                            ])->update(['id_status'=>REFUSED]);
                            break;
                        case OPERATOR:
                                if (UserOwn::where('id', IndividualOrder::where([
                                    ['id', '=', $request->orderId],
                                    ['id_type_order', '=', IMAGE_ORDER]
                                ])->value('id_user'))->value('id_operator') == $userId) {
                                    IndividualOrder::where([
                                        ['id', '=', $request->orderId],
                                        ['id_type_order', '=', IMAGE_ORDER]
                                    ])->update(['id_status'=>REFUSED]);
                                } else {       
                                    $resData['success'] = 0;
                                    echo json_encode($resData);
                                    return;       
                                }
                                break;
                        default:    
                            $resData['success'] = 0;
                            $resData['res'] = ACCESS_ERROR;
                            $resData['message'] = 'Вы не можете отменять заказ';
                            echo json_encode($resData);
                            return;      
                            break;
                    }
                    $message = new Message();
                    $message->id_individual = $request->orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', REFUSED)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;    
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
        		break;
        	case CART_ORDER:
                try {
                    if (Order::where('id', $request->orderId)->value('id_status') == REFUSED) {
                            $resData['success'] = 0;
                            $resData['message'] = 'Уже изменен';
                            $resData['res'] = UNCHANGE_ERROR;
                            echo json_encode($resData);
                            return;    
                    };
                    $prevStatus = Order::where('id', $request->orderId)->value('id_status');
                    $ids = ProductsInOrder::select('id_product')->where('id_order', $request->orderId)->get()->toArray();
                    switch ($status) {
                        case MAIN_OPERATOR:
                            Order::where([
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', CART_ORDER]
                            ])->update(['id_status'=>REFUSED]);
                            break;
                        case CUSTOMER:
                            Order::where([
                                ['id_user', '=', $userId],
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', CART_ORDER]
                            ])->update(['id_status'=>REFUSED]);
                            break;
                        case OPERATOR:
                            if (UserOwn::where('id', Order::where([
                                ['id', '=', $request->orderId],
                                ['id_type_order', '=', CART_ORDER]
                            ])->value('id_user'))->value('id_operator') == $userId) {
                                Order::where([
                                    ['id', '=', $request->orderId],
                                    ['id_type_order', '=', CART_ORDER]
                                ])->update(['id_status'=>REFUSED]);
                            } else {       
                                $resData['success'] = 0;
                                echo json_encode($resData);
                                return;       
                            }
                            break;
                        default:    
                            $resData['success'] = 0;
                            $resData['res'] = ACCESS_ERROR;
                            $resData['message'] = 'Вы не можете отменять заказ';
                            echo json_encode($resData);
                            return;      
                            break;
                    }
                    if ($prevStatus >= SENT) {
                        Knife::whereIn('id', $ids)->update(['id_status'=>IN_REFUSED]); 
                    } else {
                        Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]); 
                    }
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', REFUSED)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
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
    }  
    
    /*
    *
    *Изменение статуса заказа
    *
    *return json
    */
    public function changeStatus(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        
        switch ($request->typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    $prevStatus = Order::where('id', $request->orderId)->value('id_status');
                    
                    /*Кто может менять статус если заказ отменен*/
                    if ($request->idStatus == REFUSED) {
                        switch ($status) {
                            case MAIN_OPERATOR:
                                break;
                            case OPERATOR:
                                if (UserOwn::where('id', Order::where([
                                    ['id', '=', $request->orderId],
                                    ['id_type_order', '=', CONSTRUCT_ORDER]
                                ])->value('id_user'))->value('id_operator') == $userId) {
                                } else {       
                                    $resData['success'] = 0;
                                    echo json_encode($resData);
                                    return;       
                                }
                                break;
                            case ADMIN:
                            case MAIN_MASTER:
                            case CUSTOMER:
                            case MASTER:
                                $resData['success'] = 0;
                                $resData['res'] = ACCESS_ERROR;
                                $resData['message'] = 'Не в вашей компетенции';
                                echo json_encode($resData);
                                return;    
                                break;
                            default:
                                $resData['success'] = 0;
                                $resData['res'] = ACCESS_ERROR;
                                $resData['message'] ='Не в вашей компетенции';
                                echo json_encode($resData);
                                return; 
                                break;
                        }
                    }
                    Order::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])->update(['id_status'=>$request->idStatus]);
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', $request->idStatus)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    // $sms = new SmsMessage();
                    // $resData['sms'] = $sms->statusMessage($request->idStatus, CONSTRUCT_ORDER , $request->orderId);
                    if ($request->idStatus == REFUSED) $resData['refused'] = 1;
                    if ($prevStatus == REFUSED) $resData['reload'] = 1;
                    $resData = json_encode($resData);
                    ignore_user_abort(true);
                    header("Connection: close");
                    header("Content-Length: " . mb_strlen($resData));
                    echo $resData;
                    flush(); // releasing the browser from waiting
                    $email = new EmailMessage();
                    $email->statusMessage($request->idStatus, CONSTRUCT_ORDER , $request->orderId);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case IMAGE_ORDER:
                try {
                    $prevStatus = IndividualOrder::where('id', $request->orderId)->value('id_status');
                    
                    /*Кто может менять статус если заказ отменен*/
                    if ($request->idStatus == REFUSED) {
                        switch ($status) {
                            case MAIN_OPERATOR:
                                break;
                            case OPERATOR:
                                if (UserOwn::where('id', IndividualOrder::where([
                                    ['id', '=', $request->orderId],
                                    ['id_type_order', '=', IMAGE_ORDER]
                                ])->value('id_user'))->value('id_operator') == $userId) {
                                } else {       
                                    $resData['success'] = 0;
                                    echo json_encode($resData);
                                    return;       
                                }
                                break;
                            case ADMIN:
                            case MAIN_MASTER:
                            case CUSTOMER:
                            case MASTER:
                                $resData['success'] = 0;
                                $resData['res'] = ACCESS_ERROR;
                                $resData['message'] = 'не в вашей компетенции';
                                echo json_encode($resData);
                                return;    
                                break;
                            default:
                                $resData['success'] = 0;
                                $resData['message'] ='не в вашей компетенции';
                                echo json_encode($resData);
                                return;    
                                break;
                        }
                    }
                    IndividualOrder::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])->update(['id_status'=>$request->idStatus]);
                    $message = new Message();
                    $message->id_individual = $request->orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', $request->idStatus)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    // $sms = new SmsMessage();
                    // $resData['sms'] = $sms->statusMessage($request->idStatus, IMAGE_ORDER , $request->orderId);
                    if ($request->idStatus == REFUSED) $resData['refused'] = 1;
                    if ($prevStatus == REFUSED) $resData['reload'] = 1;
                    $resData = json_encode($resData);
                    ignore_user_abort(true);
                    header("Connection: close");
                    header("Content-Length: " . mb_strlen($resData));
                    echo $resData;
                    flush(); // releasing the browser from waiting
                    $email = new EmailMessage();
                    $email->statusMessage($request->idStatus, IMAGE_ORDER , $request->orderId);
                    return;
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    
                    /*Кто может менять статус если заказ отменен*/
                    if ($request->idStatus == REFUSED) {
                        switch ($status) {
                            case MAIN_OPERATOR:
                                break;
                            case OPERATOR:
                                if (UserOwn::where('id', Order::where([
                                    ['id', '=', $request->orderId],
                                    ['id_type_order', '=', CART_ORDER]
                                ])->value('id_user'))->value('id_operator') == $userId) {
                                } else {       
                                    $resData['success'] = 0;
                                    $resData['message'] = 'не в вашей компетенции';
                                    echo json_encode($resData);
                                    return;       
                                }
                                break;
                            case ADMIN:
                            case MAIN_MASTER:
                            case CUSTOMER:
                            case MASTER:
                                $resData['success'] = 0;
                                $resData['res'] = ACCESS_ERROR;
                                $resData['message'] = 'не в вашей компетенции';
                                echo json_encode($resData);
                                return;    
                                break;
                            default:
                                $resData['success'] = 0;
                                $resData['message'] ='не в вашей компетенции';
                                echo json_encode($resData);
                                return;    
                                break;
                        }
                    }
                    $prevStatus = Order::where('id', $request->orderId)->value('id_status');
                    $ids = ProductsInOrder::select('id_product')->where('id_order', $request->orderId)->get()->toArray();
                    Order::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])->update(['id_status'=>$request->idStatus]);
                    switch ($request->idStatus) {
                        /*case CONFIRMED:
                                Knife::whereIn('id', $ids)->update(['id_status'=>IN_ORDER]); 
                            break;*/
                        
                        case REFUSED:
                                if ($prevStatus >= SENT) {
                                    Knife::whereIn('id', $ids)->update(['id_status'=>IN_REFUSED]); 
                                } else {
                                    Knife::whereIn('id', $ids)->update(['id_status'=>AVAILABLE]); 
                                }
                            break;
                        
                        case DONE:
                                Knife::whereIn('id', $ids)->update(['id_status'=>SELLED]); 
                            break;

                    } 
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Статус заказа: ' . StatusOrder::where('id', $request->idStatus)->value('name');
                    $message->id_message_type = STATUS_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    // $sms = new SmsMessage();
                    // $resData['sms'] = $sms->statusMessage($request->idStatus, CART_ORDER , $request->orderId);
                    if ($request->idStatus == REFUSED) $resData['refused'] = 1;
                    if ($prevStatus == REFUSED) $resData['reload'] = 1;
                    $resData = json_encode($resData);
                    ignore_user_abort(true);
                    header("Connection: close");
                    header("Content-Length: " . mb_strlen($resData));
                    echo $resData;
                    flush(); // releasing the browser from waiting
                    $email = new EmailMessage();
                    $email->statusMessage($request->idStatus, CART_ORDER, $request->orderId);
                    return; 
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
    }

    /*
    *
    *Изменение мастера заказа
    *
    *return json
    */
    public function changeMaster(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status || !$token) {  
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        if(!UserOwn::where('id', $userId)->exists()) {  
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        if (!Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }

        $idMaster = $request->idMaster;
        if ($idMaster == 0) $idMaster = NULL;
        switch ($request->typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    Order::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])->update(['id_master'=>$idMaster]);
                    OutFromOrder::whereIn('id_user', UserOwn::select('id')->where('status', MASTER)->get()->toArray())
                    ->where('id_order', $request->orderId)
                    ->delete();
                    if ($idMaster) {
                        $out = new OutFromOrder(); 
                        $out->id_user = $idMaster;
                        $out->id_order = $request->orderId;
                        $out->id_individual = NULL;
                        $out->save();
                        $masterMessage =  'Ваш кузнец: ' . UserOwn::where('id', $idMaster)->value('name');
                    } else {
                        $masterMessage = 'У заказа нет кузнеца';
                    }
                    $message = new Message();
                    $message->id_order = $request->orderId;
                    $message->message = $masterMessage;
                    $message->id_message_type = MASTER_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    //$sms = new SmsMessage();
                    //$resData['sms'] = $sms->masterMessage($masterMessage, CONSTRUCT_ORDER , $request->orderId);
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;

            case IMAGE_ORDER:
                try {
                    IndividualOrder::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])->update(['id_master'=>$idMaster]);
                    OutFromOrder::whereIn('id_user', UserOwn::select('id')->where('status', MASTER)->get()->toArray())
                    ->where('id_order', $request->orderId)
                    ->delete();
                    if ($idMaster) {
                        $out = new OutFromOrder(); 
                        $out->id_user = $idMaster;
                        $out->id_order = NULL;
                        $out->id_individual = $request->orderId;
                        $out->save();
                        $masterMessage =  'Ваш кузнец: ' . UserOwn::where('id', $idMaster)->value('name');
                    } else {
                        $masterMessage = 'У заказа нет кузнеца';
                    }
                    $message = new Message();
                    $message->id_individual = $request->orderId;
                    $message->message = $masterMessage;
                    $message->id_message_type = MASTER_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    //$sms = new SmsMessage();
                    //$resData['sms'] = $sms->masterMessage($masterMessage, IMAGE_ORDER , $request->orderId);
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    Order::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])->update(['id_master'=>$idMaster]);
                    OutFromOrder::whereIn('id_user', UserOwn::select('id')->where('status', MASTER)->get()->toArray())
                    ->where('id_order', $request->orderId)
                    ->delete();
                    if ($idMaster) {
                        $out = new OutFromOrder(); 
                        $out->id_user = $idMaster;
                        $out->id_order = $request->orderId;
                        $out->id_individual = NULL;
                        $out->save();
                        $masterMessage =  'Ваш кузнец: ' . UserOwn::where('id', $idMaster)->value('name');
                    } else {
                        $masterMessage = 'У заказа нет кузнеца';
                    }
                    $message = new Message();
                    $message->id_order = $request->orderId;
                    $message->message = $masterMessage;
                    $message->id_message_type = MASTER_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    //$sms = new SmsMessage();
                    //$resData['sms'] = $sms->masterMessage($masterMessage, CART_ORDER , $request->orderId);
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }    
                break;
            
            default:
                $resData['success'] = 0;
                $resData['message'] = 'Нет типа';
                echo json_encode($resData);
                return;
        }
    }




    /*
    *
    *Изменение суммы заказа
    *
    *return json
    */
    public function changeSum(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }

        $sum = $request->sum;
        switch ($request->typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    $constructOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])
                    ->first();
                    $deliveryCost = TypeOfSend::where('id', $constructOrder->id_type_send)->value('price');
                    if (($sum + $deliveryCost) > $constructOrder->money_payed) {
                        $constructOrder->id_payed = NOT_PAYED;
                    } else {
                        $constructOrder->id_payed = PAYED;
                    }
                    $constructOrder->sum_of_order = $sum;
                    $constructOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $deliveryCost = TypeOfSend::where('id', $constructOrder->id_type_send)->value('price');
                    $message->message = 'Сумма заказа: ' . $sum . " + " . $deliveryCost . ' (доставка) = ' . ( $deliveryCost + $sum) . ' р.'; 
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case IMAGE_ORDER:
                try {
                    $individOrder = IndividualOrder::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])
                    ->first();
                    $deliveryCost = TypeOfSend::where('id', $individOrder->id_type_send)->value('price');
                    if (($sum + $deliveryCost) > $individOrder->money_payed) {
                        $individOrder->id_payed = NOT_PAYED;
                    } else {
                        $individOrder->id_payed = PAYED;
                    }
                    $individOrder->sum_of_order=$sum;
                    $individOrder->save();
                    $message = new Message();
                    $message->id_individual = $request->orderId;
                    $message->message = 'Сумма заказа: ' . $sum . " + " . $deliveryCost . ' (доставка) = ' . ( $deliveryCost + $sum) . ' р.'; 
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;    
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    $cartOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])
                    ->first();
                    $deliveryCost = TypeOfSend::where('id', $cartOrder->id_type_send)->value('price');
                    if (($sum + $deliveryCost) > $cartOrder->money_payed) {
                        $cartOrder->id_payed = NOT_PAYED;
                    } else {
                        $cartOrder->id_payed = PAYED;
                    }
                    $cartOrder->sum_of_order = $sum;
                    $cartOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Сумма заказа: ' . $sum . " + " . $deliveryCost . ' (доставка) = ' . ( $deliveryCost + $sum) . ' р.'; 
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
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
    }



    /*
    *
    *Изменение способа отправки
    *
    *return json
    */
    public function changeSendType(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        
        $typeSend = $request->typeSend;
        switch ($request->typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    $constructOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])
                    ->first();
                    $deliveryCostNext = TypeOfSend::where('id', $typeSend)->value('price');
                    if ($constructOrder->money_payed < ($constructOrder->sum_of_order + $deliveryCostNext)) {
                        $constructOrder->id_payed = NOT_PAYED;
                    } else {
                        $constructOrder->id_payed = PAYED;
                    }
                    $constructOrder->id_type_send = $typeSend;
                    $constructOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Сумма заказа: ' . $constructOrder->sum_of_order . " + " . $deliveryCostNext . ' (доставка) = ' . ( $deliveryCostNext + $constructOrder->sum_of_order) . ' р.'; 
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case IMAGE_ORDER:
                try {
                    $individOrder = IndividualOrder::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])
                    ->first();
                    $deliveryCostNext = TypeOfSend::where('id', $typeSend)->value('price');
                    if ($individOrder->money_payed < ($individOrder->sum_of_order + $deliveryCostNext)) {
                        $individOrder->id_payed = NOT_PAYED;
                    } else {
                        $individOrder->id_payed = PAYED;
                    }
                    $individOrder->id_type_send = $typeSend;
                    $individOrder->save();
                    $message = new Message();
                    $message->id_individual = $request->orderId;
                    $message->message = 'Сумма заказа: ' . $individOrder->sum_of_order . " + " . $deliveryCostNext . ' (доставка) = ' . ( $deliveryCostNext + $individOrder->sum_of_order) . ' р.';
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;    
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    $cartOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])
                    ->first();
                    $deliveryCostNext = TypeOfSend::where('id', $typeSend)->value('price');
                    if ($cartOrder->money_payed < ($cartOrder->sum_of_order + $deliveryCostNext)){
                        $cartOrder->id_payed = NOT_PAYED;
                    } else {
                        $cartOrder->id_payed = PAYED;
                    }
                    $cartOrder->id_type_send = $typeSend;
                    $cartOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Сумма заказа: ' . $cartOrder->sum_of_order . " + " . $deliveryCostNext . ' (доставка) = ' . ( $deliveryCostNext + $cartOrder->sum_of_order) . ' р.'; 
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
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
    }

    /*
    *
    *Изменение дней заказа
    *
    *return json
    */
    public function changeDay(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }

        $day = $request->day;
        switch ($request->typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    $constructOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])
                    ->first();
                    $constructOrder->days_for_order = $day;
                    $constructOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Дней на заказ: ' . $day;
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case IMAGE_ORDER:
                try {
                    $individOrder = IndividualOrder::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])
                    ->first();
                    $individOrder->days_for_order=$day;
                    $individOrder->save();
                    $message = new Message();
                    $message->id_individual = $request->orderId;
                    $message->message = 'Дней на заказ: ' . $day;
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;    
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    $cartOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])
                    ->first();
                    $cartOrder->days_for_order = $day;
                    $cartOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Дней на заказ: ' . $day;
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
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
    }

    /*
    *
    *Изменение внесенной оплаты
    *
    *return json
    */
    public function changePay(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }
        
        $pay = $request->pay;
        switch ($request->typeOrder) {
            case CONSTRUCT_ORDER:
                try {
                    $constructOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])
                    ->first();
                    $constructOrder->money_payed = $pay;
                    $deliveryCost = TypeOfSend::where('id', $constructOrder->id_type_send)->value('price');
                    if ($constructOrder->money_payed >= ($constructOrder->sum_of_order + $deliveryCost)) $constructOrder->id_payed = PAYED;
                    $constructOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Всего внесено: ' . $pay . ' р.';
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case IMAGE_ORDER:
                try {
                    $individOrder = IndividualOrder::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])
                    ->first();
                    $deliveryCost = TypeOfSend::where('id', $individOrder->id_type_send)->value('price');
                    $individOrder->money_payed=$pay;
                    if ($individOrder->money_payed >= ($individOrder->sum_of_order + $deliveryCost)) $individOrder->id_payed = PAYED;
                    $individOrder->save();
                    $message = new Message();
                    $message->id_individual = $request->orderId;
                    $message->message = 'Всего внесено: ' . $pay . ' р.';
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;    
                } catch (\Exception $e) {
                    $resData['success'] = 0;
                    $resData['message'] = 'Неизвестная ошибка';
                    echo json_encode($resData);
                    return;    
                }
                break;
            case CART_ORDER:
                try {
                    $cartOrder = Order::where([
                        //['id_user', '=', $userId], на оператора
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])
                    ->first();
                    $deliveryCost = TypeOfSend::where('id', $cartOrder->id_type_send)->value('price');
                    $cartOrder->money_payed = $pay;
                    if ($cartOrder->money_payed >= ($cartOrder->sum_of_order + $deliveryCost)) $cartOrder->id_payed = PAYED;
                    $cartOrder->save();
                    $message = new Message();
                    $message->id_order = $request->orderId; 
                    $message->message = 'Всего внесено: ' . $pay . ' р.';
                    $message->id_message_type = SUM_CHANGED_MESSAGE;
                    $message->save();
                    $messageId = $message->id;
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
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
    }
    /*
    *
    *Изменение заметки заказа
    *
    *return json
    */
    public function savePurpose(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }

        try {
            switch ($request->typeOrder) {
                case CONSTRUCT_ORDER:
                    Order::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CONSTRUCT_ORDER]
                    ])->update(['purpose'=>$request->purpose]);
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                    break;
                case IMAGE_ORDER:
                    IndividualOrder::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', IMAGE_ORDER]
                    ])->update(['purpose'=>$request->purpose]);
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                    break;
                case CART_ORDER:   
                    Order::where([
                        ['id', '=', $request->orderId],
                        ['id_type_order', '=', CART_ORDER]
                    ])->update(['purpose'=>$request->purpose]);
                    $resData['success'] = 1;
                    echo json_encode($resData);
                    return;       
                default:
                    $resData['success'] = 0;
                    echo json_encode($resData);
                    return;
            }
        } catch (\Exception $e) {
            $resData['success'] = 0;
            $resData['message'] = 'Неизвестная ошибка';
            echo json_encode($resData);
            return;    
        }
    }
}
