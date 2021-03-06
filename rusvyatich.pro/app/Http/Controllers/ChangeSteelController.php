<?php

/*
*
*Контроллер листа стали 
*для работников
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Steel;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class ChangeSteelController extends Controller
{
    public function index()
    {
        $title = 'Сталь';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $properties = Steel::orderBy('popularity')->get();
        return view('properties')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"#", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'addPropertyLink'=>'/home/changeConstruct/addSteel', 'steelsLink'=>'#','bladesLink'=>'/home/changeConstruct/blades', 'bolstersLink'=>'/home/changeConstruct/bolsters', 'handlesLink'=>'/home/changeConstruct/handles', 'handlesMaterialLink'=>'/home/changeConstruct/handleMaterials', 'sizesLink'=>'/home/changeConstruct/sizes', 'properties'=>$properties, 'pageType'=> 'steels', 'controllerLink'=>'/home/changeConstruct/steels/']);
	}
}
