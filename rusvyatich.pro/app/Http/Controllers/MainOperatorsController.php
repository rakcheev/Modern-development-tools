<?php

/*
*
*Контроллер страницы
*работников(главных мастеров)
*
*для администратора
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class MainOperatorsController extends Controller
{
    public function index()
    {
        $title = 'Главные оперторы';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        $users = UserOwn::where('status', MAIN_OPERATOR)->get();
        return view('workers')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'workersLink'=>'#', 'operatorsLink'=>'/home/workers/operators', 'mastersLink'=>'/home/workers/masters', 'mastersMainLink'=>'/home/workers/mainMasters', 'operatorsMainLink'=>'#', 'statisticLink'=>"/home/statistic", 'users'=>$users, 'userType'=>MAIN_MASTER]);
    }
}
