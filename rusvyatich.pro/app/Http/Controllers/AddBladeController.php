<?php

/*
*
*Контроллер добавления
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
use Session;

ignore_user_abort(true);
set_time_limit(0);

class AddBladeController extends Controller
{
    
    public function index()
    {
        $title = 'Добавление клинка';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        $token = str_random(8);
        UserOwn::where('id', $userId)->update(['tok' => Hash::make($token), 'last_visit' => DB::raw('NOW()')]);
        	Session::put('token', $token);
            Session::save();
        return view('addBlade')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'koefCosts'=> KoefCost::all()]);
    }

	/*
	*
	*Добавление
	*клинка
	*
	*для администратора
	*
	*/
    public function addBlade(request $request)
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
			if ($request->bent == 1 && $request->free == 1) { //если и изогнут и не ХО
				$resData['success'] = 0;
				$resData['note'] = 'Не ХО и изогнут больше 5мм одновременно!';
				echo json_encode($resData);
				return;
			}
			$popularityClass = new Popularity();
			$popularityClass->shiftPopularityInsert(BLADE_ID, $request->popularity);
			$blade = new Blade();
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
		$resData = array();
		$resData['success'] = 1;
		echo json_encode($resData);
    }
}