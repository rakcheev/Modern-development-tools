<?php

/*
*
*Контроллер добавления
*ручки
*
*для администратора
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Handle;
use App\HandleHeight;
use App\Popularity;
use App\KoefCost;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class AddHandleController extends Controller
{
    public function index()
    {
        $title = 'Добавление рукояти';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        $token = str_random(8);
        Session::put('token', $token);
        Session::save();
        UserOwn::where('id', $userId)->update(['tok' => Hash::make($token), 'last_visit' => DB::raw('NOW()')]);
        return view('addHandle')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'handleHeights'=>HandleHeight::all(), 'koefCosts'=> KoefCost::all()]);
    }

	/*
	*
	*Добавление
	*рукояти
	*
	*для администратора
	*
	*/
    public function addHandle(request $request)
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
			$popularityClass = new Popularity();
			$popularityClass->shiftPopularityInsert(HANDLE_ID, $request->popularity);
			$handle = new handle();
			$handle->name = $request->name;
			$handle->price = $request->price;
			$handle->path = $request->path;
			$handle->pathFultang = $request->pathFultang;
			$handle->pathKlepka = $request->pathKlepka;
			$handle->pathFixBlade = $request->pathFixBlade;
			$handle->pathFixBladeFultang = $request->pathFixBladeFultang;
			$handle->description = $request->description;
			$handle->popularity = $request->popularity;
			$handle->restricted = $request->restrict;
			$handle->hardness = $request->hardness;
			$handle->viewable = $request->viewable;
			$handle->heightHandle = $request->heightPxHandle;
			$handle->save();
			$handleId = $handle->id;
			$popularityClass->arrangePopularity(HANDLE_ID);
		} catch (\Exception $e){
			$resData['success'] = 0;
			$resData['message'] = 'Неизвестная ошибка';
			echo json_encode($resData);
			return;
		}
		$resData = array();
		$resData['success'] = 1;
		echo json_encode($resData);
    }
}
