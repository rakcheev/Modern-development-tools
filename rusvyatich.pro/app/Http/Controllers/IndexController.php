<?php

/*
*
*Контроллер главной страницы
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Knife;
use App\Steel;
use App\Blade;
use App\Bolster;
use App\Handle;
use App\HandleMaterial;
use App\AdditionOfBlade;
use App\Spusk;
use App\UserOwn;
use App\Order;
use App\Messages;
use App\TypeOfSend;
use Session;
use Cookie;
use Request as Request2;

// header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
// header("Pragma: no-cache"); // HTTP 1.0.
// header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class IndexController extends Controller
{
    
    public function index()
    {
        $title = 'Вятич - конструктор ножей.';
        $descriptionPage = 'Ножи ручной работы на заказ. Расчет суммы заказа ножа в графическом конструкторе. Отправка по всей России.';
        //$knifes = DB::table('knifes')->select(['knifes.*', 'typeOfSteel.name as steel', DB::raw('(select image from `knife_images` where `id_knife` =  `knifes`.`id` order by `knife_images`.`number` asc limit 1) as  image')])->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')->where('id_status', 1)->take(3)->get();


            $knifesIndividual = DB::table('knifes')->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')->select(['knifes.id', 'knifes.created_at', 'knifes.name', 'knifes.id_steel','knifes.id_steel', 'knifes.blade_length', 'knifes.blade_width', 'knifes.blade_thickness', 'knifes.handle_length', 'knifes.price', 'knifes.description', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images` where `knife_images`.`id_knife` =  `knifes`.`id` order by `knife_images`.`number` asc limit 1) as  image'), DB::raw("'individual' as source")])->where('id_status', 1);

            $knifes = DB::table('serial_knifes')->join('typeOfSteel', 'serial_knifes.id_steel', '=', 'typeOfSteel.id')->select(['serial_knifes.id', 'serial_knifes.created_at', 'serial_knifes.name', 'serial_knifes.id_steel','serial_knifes.id_steel', 'serial_knifes.blade_length', 'serial_knifes.blade_width', 'serial_knifes.blade_thickness', 'serial_knifes.handle_length', 'serial_knifes.price', 'serial_knifes.description', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images_serial` where `knife_images_serial`.`id_knife` =  `serial_knifes`.`id` order by `knife_images_serial`.`number` asc limit 1) as  image'), DB::raw("'serial' as source")])->where([
                //['serial_knifes.count', '>=', '1'], 
                ['serial_knifes.viewable', '=', '1'] 
            ])
            ->union($knifesIndividual)
            ->orderBy('source', 'DESC')
            //->orderBy('created_at', 'DESC')
            ->take(4)
            ->get();
        $typeOfSends = TypeOfSend::where('viewable', 1)->get();
        $steels = Steel::where('viewable', 1)->orderBy('popularity')->get();
        $blades = Blade::where('viewable', 1)->orderBy('popularity')->get();
        $bolsters = Bolster::where('viewable', 1)->orderBy('popularity')->get();
        $handles = Handle::where('viewable', 1)->orderBy('popularity')->get();
        $handleMaterials = HandleMaterial::where('viewable', 1)->orderBy('popularity')->get();
        $spuski= Spusk::where('viewable', 1)->get();
        $additionOfBlade= AdditionOfBlade::where('viewable', 1)->get();
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $status = Session::has('status') ? Session::get('status') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        $unpayedId =  Session::has('orderUnpayed') ? Session::get('orderUnpayed') : null;
        if ($unpayedId) {
            $orderUnpayed = Order::select('id_status', 'id_payed')->where('id', $unpayedId)->first();
            if (($orderUnpayed && ($orderUnpayed->id_status === REFUSED || $orderUnpayed->id_payed === PAYED)) || !$orderUnpayed) {
                Session::forget('accessToken');
                Session::forget('orderUnpayed');
                Session::save();
            }
        }
        $username = 'Войти';
        if ($userId && UserOwn::where('id', $userId)->exists()) {
            if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
                Session::forget('userId');
                Session::forget('status');
                Session::forget('token');
                Session::save();
            } else {
                $username = UserOwn::where('id', $userId)->value('name');
                UserOwn::where('id', $userId)->update(['last_visit' => DB::raw('NOW()')]);
            }
        }
        $mobile = 0;
        if(self::isMobile()){
            $mobile = 1;
        }
        return view('index')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage,'typeOfSends' => $typeOfSends, 'products'=>$knifes, 'typeOfSteels'=>$steels, 'typeOfBlades'=>$blades, 'typeOfBolsters'=>$bolsters, 'typeOfHandles'=>$handles, 'typeOfHandleMaterials'=>$handleMaterials, 'spuski'=>$spuski, 'additionOfBlade'=>$additionOfBlade, 'username' => $username, 'tok'=>$unpayedId, 'mobile'=>$mobile, 'ip'=> Request2::ip()]);
    }

    /*Получение коллва заказов с непрочитанными сообщениями*/
    public function getNewChanges() 
    {
        $orders = 0;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $status = Session::has('status') ? Session::get('status') : null;
        $resData['z'] = 0;
        if ($userId && UserOwn::where('id', $userId)->exists()) {
            if ($status === CUSTOMER) {
                $ordersFirst =  DB::table('orders')
                    ->select('orders.id', DB::raw('(select count(*) from `messages` where `id_order` =  `orders`.`id` and `messages`.`created_at` >' . DB::raw('(select time_outer from outersFromOrder where `id_order` =  `orders`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))->where([
                        ['orders.id_user', '=', $userId],
                        ['orders.id_status', '<>', REFUSED]
                    ])
                    ->having('newCount','>', 0);

                $orders =  DB::table('individual_order')
                    ->select('individual_order.id', DB::raw('(select count(*) from `messages` where `id_individual` =  `individual_order`.`id` and `messages`.`created_at` >' . DB::raw('(select `time_outer` from `outersFromOrder` where `outersFromOrder`.`id_individual` = `individual_order`.`id` and `id_user`=' . $userId . ' order by `time_outer` desc limit 1)') . ' ) as newCount'))
                    ->where([
                        ['individual_order.id_user', '=', $userId],
                        ['individual_order.id_status', '<>', REFUSED]
                    ])
                    ->having('newCount','>', 0)
                    ->union($ordersFirst)
                    ->get()->count();
            $resData['z'] = 1;
            }
        }
        $resData['newCount']['newCount'] = $orders;
        $resData['success'] = 1;
        echo json_encode($resData);
    }

    private function isMobile() { 
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}
