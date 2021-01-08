<?php

/*
*
*Контроллер страницы листа всех ножей
*для работников
*
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Knife;
use App\StatusOrder;
use App\ProductStatus;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class EditKnifesController extends Controller
{
    public function index()
    {
        $title = 'Редактирование ножей';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        $products = DB::table('knifes')->select(['knifes.*', DB::raw('(select image from `knife_images` where `id_knife` =  `knifes`.`id` order by `knife_images`.`number` asc limit 1) as  image')])->get();
        $statusKnifes = ProductStatus::all();

        return view('editKnifes')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"#", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'serialLink'=>'/home/knifes/serial', 'individLink'=>'#', 'products'=>$products, 'statusKnifes'=>$statusKnifes] );
    }

    public function serial()
    {
        $title = 'Редактирование ножей';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        $products = DB::table('serial_knifes')->select(['serial_knifes.*', DB::raw('(select image from `knife_images_serial` where `id_knife` =  `serial_knifes`.`id` order by `knife_images_serial`.`number` asc limit 1) as  image')])->get();

        return view('editKnifes')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"#", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'serialLink'=>'#', 'individLink'=>'/home/knifes/individual', 'products'=>$products, 'serial'=>true] );
    }
}
