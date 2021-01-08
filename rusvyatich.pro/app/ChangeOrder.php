<?php

namespace App;

use Illuminate\Support\Facades\DB;
use App\Order;
use App\Message;

class ChangeOrder
{
    /*Удаление неоплаченных заказов и возвращение ножей на сайт*/
    public static function eraseUnpayed() 
    {
        DB::table('knifes')->where([
            ['orders.id_payment', '<>', PAY_LATER],
            [DB::raw('TIMESTAMPDIFF(SECOND, `orders`.`created_at` , NOW())'), '>', TIME_BEFORE_ERASE-2],
            ['orders.id_payed', '=', NOT_PAYED],
            ['orders.money_payed', '=', 0],
            ['orders.id_status', '<>', REFUSED]
        ])
        ->join(DB::raw('(SELECT * FROM `products_in_order`) products_in_order'), function($join)
        {
            $join->on('knifes.id', '=', 'products_in_order.id_product');
        })
        ->join(DB::raw('(SELECT * FROM `orders`) orders'), function($join)
        {
            $join->on('products_in_order.id_order', '=', 'orders.id');
        })->update(['knifes.id_status'=>AVAILABLE]);
        $refuseName = StatusOrder::where('id', REFUSED)->value('name');
        $ordersId = Order::select('id')->where([
            ['id_payment', '<>', PAY_LATER],
            [DB::raw('TIMESTAMPDIFF(SECOND, `created_at` , NOW())'), '>', TIME_BEFORE_ERASE-2],
            ['id_payed', '=', NOT_PAYED],
            ['orders.money_payed', '=', 0],
            ['id_status', '<>', REFUSED]
        ])->pluck('id');
        $data = array();
            foreach ($ordersId as $orderId) {
                $data[] = array('id_order' => $orderId, 'message'=> 'Статус заказа: ' . $refuseName, 'id_message_type' => STATUS_CHANGED_MESSAGE); // Формирование массива для insert
            }
        Message::insert($data);
        Order::where([
            ['id_payment', '<>', PAY_LATER],
            [DB::raw('TIMESTAMPDIFF(SECOND, `created_at` , NOW())'), '>', TIME_BEFORE_ERASE-2],
            ['id_payed', '=', NOT_PAYED],
            ['orders.money_payed', '=', 0],
            ['id_status', '<>', REFUSED]
        ])->update(['id_status'=>REFUSED]);
    }
}
