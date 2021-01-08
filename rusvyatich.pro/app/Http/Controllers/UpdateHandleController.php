<?php

/*
*
*Контроллер изменения рукояти
*для администратора
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Handle;
use App\HandleHeight;
use App\KoefCost;
use App\Popularity;
use App\KnifePropertiesInOrder;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class UpdateHandleController extends Controller
{   
    public function index($id)
    {
        $title = 'Изменить рукоять';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $handle = Handle::find($id);
        $presence = KnifePropertiesInOrder::where('id_typeOfHandle', $id)->exists();
        return view('addHandle')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'handle'=>$handle, 'presence'=>$presence, 'handleHeights'=>HandleHeight::all(), 'koefCosts'=> KoefCost::all()]);
    }

	/*
	*
	*Изменение рукояти
	*
	*для администратора
	*
	*/
    public function updateHandle($id, $action, request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;

        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
	        $resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
  		
  		if ($action == 0) {
  			if (KnifePropertiesInOrder::where('id_typeOfHandle', $id)->exists()) {
	  			$handle = Handle::find($id);
	  			$handle->viewable = 0;
	  			$handle->save();
				$resData['success'] = 1;
				$resData['res'] = 1;
				echo json_encode($resData);
				return;
	  		} else {
	  			Handle::destroy($id);
				$resData['success'] = 1;
				$resData['res'] = 2;
				echo json_encode($resData);
				return;
	  		}
  		} else if ($action == 1) {
			try {
				$handle = Handle::find($id);
				$popularityClass = new Popularity();
				$popularityClass->shiftPopularityUpdate(HANDLE_ID, $request->popularity, $handle->popularity);
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
			$resData['success'] = 1;
			echo json_encode($resData);
			return;
		}
		$resData['success'] = 0;
		echo json_encode($resData);
    }
}
