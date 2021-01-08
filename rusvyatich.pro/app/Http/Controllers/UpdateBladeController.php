<?php

/*
*
*Контроллер изменения
*клинка
*
*для администратора
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Blade;
use App\Popularity;
use App\KoefCost;
use App\KnifePropertiesInOrder;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class UpdateBladeController extends Controller
{
    public function index($id)
    {
        $title = 'Изменение клинка';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $blade = Blade::find($id);
        $presence = KnifePropertiesInOrder::where('id_typeOfBlade', $id)->exists();
        
        return view('addBlade')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'blade'=>$blade, 'presence'=>$presence, 'koefCosts'=> KoefCost::all()]);
    }

	/*
	*
	*Изменение
	*клинка
	*
	*для администратора
	*
	*/
    public function updateBlade($id, $action, request $request)
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
  			if (KnifePropertiesInOrder::where('id_typeOfBlade', $id)->exists()) {
	  			$blade = Blade::find($id);
	  			$blade->viewable = 0;
	  			$blade->save();
				$resData['success'] = 1;
				$resData['res'] = 1;
				echo json_encode($resData);
				return;
	  		} else {
	  			Blade::destroy($id);
				$resData['success'] = 1;
				$resData['res'] = 2;
				echo json_encode($resData);
				return;
	  		}
  		} else if ($action == 1) {
			try {
				if ($request->bent == 1 && $request->free == 1) { //если и изогнут и не ХО
					$resData['success'] = 0;
					$resData['note'] = 'Не ХО и изогнут больше 5мм одновременно!';
					echo json_encode($resData);
					return;
				}
				$blade = Blade::find($id);
				$popularityClass = new Popularity();
				$popularityClass->shiftPopularityUpdate(BLADE_ID, $request->popularity, $blade->popularity);
				$blade->name = $request->name;
				$blade->price = $request->price;
				$blade->path = $request->path;
				$blade->description = $request->description;
				$blade->popularity = $request->popularity;
				$blade->viewable = $request->viewable;
				$blade->hardness = $request->hardness;
				$blade->bent = $request->bent;
				$blade->free = $request->free;
				$blade->save();
				$bladeId = $blade->id;
				$popularityClass->arrangePopularity(BLADE_ID);
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
