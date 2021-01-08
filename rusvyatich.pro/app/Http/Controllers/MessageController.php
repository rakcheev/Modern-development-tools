<?php

/*
*
*Контроллер сообщений
*для всех
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\UserOwn;
use App\Order;
use App\IndividualOrder;
use App\Message;
use App\OutFromOrder;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class MessageController extends Controller
{   
    /*
    *
    *Отправка сообщения
    *
    *return json
    */
    public function sendMessage(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        $image = $request->file('file');
        if (!$userId || !$status || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok')) || (!$image && $request->message == '')) {
            $resData['success'] = 0;
			echo json_encode($resData);
			return;
        }
        
		if (\File::size($image) > 2*1024*1024 || \File::size($image) === 0) {
			$resData['success'] = 2;
			$resData['message'] = 'размер картинки велик';
			echo json_encode($resData);
			return;
		}
		
		try {
	        $message = new Message();
	        $message->id_sender = $userId;
	        if ($request->typeOrder == CONSTRUCT_ORDER) {
	        	if (Order::where('id', $request->orderId)->value('id_user') == $userId || $status === ADMIN || $status === MAIN_OPERATOR || $status === MAIN_MASTER || Order::where('id', $request->orderId)->value('id_master') == $userId || UserOwn::where('id', Order::where('id', $request->orderId)->value('id_user'))->value('id_operator') == $userId) {
					$message->id_order = $request->orderId;
	        	} else {
                    $resData['success'] = 0;
                    $resData['note'] = 'Не ваш заказ';
                    $resData['message'] = 'Не ваш заказ';
                    echo json_encode($resData);
                    return;
                }
            } elseif ($request->typeOrder == IMAGE_ORDER) {
                if (IndividualOrder::where('id', $request->orderId)->value('id_user') == $userId || $status === ADMIN || $status === MAIN_OPERATOR || $status === MAIN_MASTER || IndividualOrder::where('id', $request->orderId)->value('id_master') == $userId ||IndividualOrder::where('id', $request->orderId)->value('id_master') == $userId || UserOwn::where('id', IndividualOrder::where('id', $request->orderId)->value('id_user'))->value('id_operator') == $userId) {
                    $message->id_individual = $request->orderId;
                } else {
                    $resData['success'] = 0;
                    $resData['message'] = 'Не ваш заказ';
                    $resData['note'] = 'Не ваш заказ';
                    echo json_encode($resData);
                    return;
                }
	        } elseif ($request->typeOrder == CART_ORDER) {
                if (Order::where('id', $request->orderId)->value('id_user') == $userId || $status === ADMIN || $status === MAIN_OPERATOR || UserOwn::where('id', Order::where('id', $request->orderId)->value('id_user'))->value('id_operator') == $userId) {
                    $message->id_order = $request->orderId;
                } else {
                    $resData['success'] = 0;
                    $resData['message'] = 'У вас нет прав на отправку сообщения';
                    echo json_encode($resData);
                    return;
                }
	        } else {
				$resData['success'] = 0;
                $resData['note'] = 'Не подходит тип заказа';
				$resData['message'] = 'Не подходит тип заказа';
				echo json_encode($resData);
				return;
	        }
	        $message->message = $request->message;
            $message->id_message_type = SIMPLE_MESSAGE;
	        $message->save();
	        $messageId = $message->id;
		} 
		catch (\Exception $e) {
			$resData['success'] = 0;
            $resData['note'] = 'Сообщение не сохранено'. 'Неизвестная ошибка';
			$resData['message'] = 'Сообщение не сохранено';
			echo json_encode($resData);
			return;
		}
		$imageName = false;
        if ($image) {
        	try {
        		$ext = \File::extension($image->getClientOriginalName());
        		$imageName = $messageId . '.' . $ext;
				$image->move(base_path('public/messageImages'), $imageName);
				Message::where('id', $messageId)->update(['attach_image' => $imageName]);
			} catch (\Exception $e) {
				Message::destroy($messageId);
				$resData['success'] = 0;
				$resData['message'] = 'Ошибка сохранения картинки';
				echo json_encode($resData);
				return;
			}
        }
		$resData['success'] = 1;
		echo json_encode($resData);
	}

    /*
    *
    *Получение всех сообщений для 
    *real time переписки
    *
    *return json
    */
	public function getMessages(request $request)
    {   
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $typeId = $request->typeOrder;
        $orderId = $request->orderId;
        $resData = array();
        if (!$typeId) {
            $resData['success'] = 0;
            $resData['messages'] = null;
            echo json_encode($resData);
            return;
        }
        switch ($typeId) {
            case CONSTRUCT_ORDER:
                    $msc = microtime(true); 
                    $messages =  DB::table('messages')
                        ->select('messages.*', DB::raw('(select DATE(`messages`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`messages`.`created_at`)) as TimeCreate'), 'users.status', 'users.name as senderName', 'user_statuses.name')->where([
                            ['id_order', '=', $orderId]
                        ])
                        ->leftjoin(DB::raw('(SELECT * FROM `users`) users'), function($join)
                        {
                            $join->on('messages.id_sender', '=', 'users.id');
                        })
                        ->leftjoin(DB::raw('(SELECT * FROM `user_statuses`) user_statuses'), function($join)
                        {
                            $join->on('users.status', '=', 'user_statuses.id');
                        })
                        ->orderBy('messages.created_at')
                        ->orderBy('messages.id')
                        ->get();
                        $msc = microtime(true)-$msc;
                        $out = OutFromOrder::select('time_outer', 'online')->where([
                            ['id_user', '=', $userId],
                            ['id_order', '=', $orderId]
                        ])->first();
                        $onlineUser = OutFromOrder::where([
                            ['id_user', '=', Order::where('id', $orderId)->value('id_user')],
                            ['id_order', '=', $orderId]
                        ])->select('online', DB::raw('(select DATE(`outersFromOrder`.`time_outer`)) as DateOut'), DB::raw('(select TIME(`outersFromOrder`.`time_outer`)) as TimeOut'))->first(); 
                        if ($onlineUser) {
                            $onlineUser->DateOut = date("d.m.Y", strtotime($onlineUser->DateOut));
                            $onlineUser->TimeOut = date("G:i", strtotime($onlineUser->TimeOut));    
                        }
                        $resData['msc'] = $msc;
                        if (!empty($messages[0])) $messages = self::delSameName($messages, $out, $userId);
                break;
            case CART_ORDER:
                    $msc = microtime(true);
                    $messages =  DB::table('messages')
                        ->select('messages.*', DB::raw('(select DATE(`messages`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`messages`.`created_at`)) as TimeCreate'), 'users.status', 'users.name as senderName', 'user_statuses.name')->where([
                            ['id_order', '=', $orderId]
                        ])
                        ->leftjoin(DB::raw('(SELECT * FROM `users`) users'), function($join)
                        {
                            $join->on('messages.id_sender', '=', 'users.id');
                        })
                        ->leftjoin(DB::raw('(SELECT * FROM `user_statuses`) user_statuses'), function($join)
                        {
                            $join->on('users.status', '=', 'user_statuses.id');
                        })
                        ->orderBy('messages.created_at')
                        ->orderBy('messages.id')
                        ->get();
                        $msc = microtime(true)-$msc;
                        $resData['msc'] = $msc;
                        $out = OutFromOrder::select('time_outer', 'online')->where([
                            ['id_user', '=', $userId],
                            ['id_order', '=', $orderId]
                        ])->first();
                        $onlineUser = OutFromOrder::where([
                            ['id_user', '=', Order::where('id', $orderId)->value('id_user')],
                            ['id_order', '=', $orderId]
                        ])->select('online', DB::raw('(select DATE(`outersFromOrder`.`time_outer`)) as DateOut'), DB::raw('(select TIME(`outersFromOrder`.`time_outer`)) as TimeOut'))->first(); 
                        if ($onlineUser) {
                            $onlineUser->DateOut = date("d.m.Y", strtotime($onlineUser->DateOut));
                            $onlineUser->TimeOut = date("G:i", strtotime($onlineUser->TimeOut));    
                        }
                        $resData['msc'] = $msc;
                        if (!empty($messages[0])) $messages = self::delSameName($messages, $out, $userId);
                break;
            case IMAGE_ORDER:
                
                    $msc = microtime(true);        
                    $messages =  DB::table('messages')
                        ->select('messages.*', DB::raw('(select DATE(`messages`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`messages`.`created_at`)) as TimeCreate'), 'users.status', 'users.name as senderName', 'user_statuses.name')->where([
                            ['id_individual', '=', $orderId]
                        ])
                        ->leftjoin(DB::raw('(SELECT * FROM `users`) users'), function($join)
                        {
                            $join->on('messages.id_sender', '=', 'users.id');
                        })
                        ->leftjoin(DB::raw('(SELECT * FROM `user_statuses`) user_statuses'), function($join)
                        {
                            $join->on('users.status', '=', 'user_statuses.id');
                        })
                        ->orderBy('messages.created_at')
                        ->orderBy('messages.id')
                        ->get();
                        $msc = microtime(true)-$msc;
                        $resData['msc'] = $msc;

                        $out = OutFromOrder::select('time_outer', 'online')->where([
                            ['id_user', '=', $userId],
                            ['id_individual', '=', $orderId]
                        ])->first();
      
                        $onlineUser = OutFromOrder::where([
                            ['id_user', '=', IndividualOrder::where('id', $orderId)->value('id_user')],
                            ['id_individual', '=', $orderId]
                        ])->select('online', DB::raw('(select DATE(`outersFromOrder`.`time_outer`)) as DateOut'), DB::raw('(select TIME(`outersFromOrder`.`time_outer`)) as TimeOut'))->first();  
                        
                        if ($onlineUser) {
                            $onlineUser->DateOut = date("d.m.Y", strtotime($onlineUser->DateOut));
                            $onlineUser->TimeOut = date("G:i", strtotime($onlineUser->TimeOut));    
                        }
                        $resData['msc'] = $msc;
                        if (!empty($messages[0])) $messages = self::delSameName($messages, $out, $userId);
                    /*$resData['query'] =  DB::table('messages')
                        ->select('messages.*', DB::raw('(select DATE(`messages`.`created_at`)) as DateCreate'), DB::raw('(select TIME(`messages`.`created_at`)) as TimeCreate'), 'users.status', 'users.name as senderName', 'user_statuses.name')->where([
                            ['id_individual', '=', $orderId]
                        ])
                        ->leftjoin(DB::raw('(SELECT * FROM `users`) users'), function($join)
                        {
                            $join->on('messages.id_sender', '=', 'users.id');
                        })
                        ->leftjoin(DB::raw('(SELECT * FROM `user_statuses`) user_statuses'), function($join)
                        {
                            $join->on('users.status', '=', 'user_statuses.id');
                        })
                        ->orderBy('messages.created_at')->toSql();*/

                break;
            default:
                $onlineUser = null;
                $messages = null;
        }
        if ($messages) {
            $resData['messages'] = $messages;
            $resData['onlineCustomer'] = $onlineUser;
            $resData['success'] = 1;
            $resData['error'] = 0;
        } else {
            $resData['success'] = 0;
            $resData['error'] = 'Нет сообщений';
        }
        echo json_encode($resData);
    }

    /*Обработка сообщений дат отправителей...*/
    private function delSameName($messages, $outers, $userId){
        $out = $outers->time_outer;
        $unreadedKey = -1;
        $flagNews = false; //флаг новых сообщений
        $prevIdSender = $messages[0]->id_sender;
        $prevDate = date("d.m.Y", strtotime($messages[0]->DateCreate));
        $prevTime = date("G:i", strtotime($messages[0]->TimeCreate));
        foreach ($messages as $key => $message) {
            $message->DateCreate = date("d.m.Y", strtotime($message->DateCreate));
            $message->TimeCreate = date("G:i", strtotime($message->TimeCreate)); 
            if ($message->created_at > $out && !$flagNews){
                $message->unreaded = 1;
                $flagNews = true; //найдены новые сообщения
                $unreadedKey = $key;
            } else {
                $message->unreaded = 0;
            }
            if ($key !== 0) {    
                $nowIdSender = $message->id_sender;
                $nowDate = $message->DateCreate;
                $nowTime = $message->TimeCreate;
                if ($message->id_sender === $prevIdSender && $nowDate === $prevDate && $message->unreaded  == 0) {
                    $message->id_sender = 0;
                }
                if ($message->DateCreate === $prevDate) {
                    $message->DateCreate = 0;
                }
                if ($message->TimeCreate === $prevTime && $prevIdSender === $nowIdSender && $nowDate === $prevDate && $message->id_message_type === SIMPLE_MESSAGE && $message->unreaded  == 0) {
                    $message->TimeCreate = 0;
                }
                $prevIdSender = $nowIdSender;
                $prevDate = $nowDate;
                $prevTime = $nowTime;
            }
        }
        foreach ($messages as $key => $message) {
            if ($outers->online == ONLINE && $unreadedKey >= 0) {
                if ($key >= $unreadedKey) {
                    if($message->id_sender == $userId){
                        $messages[$unreadedKey]->unreaded = 0;
                    }
                }
            }
        }
        return $messages;
    }
}
