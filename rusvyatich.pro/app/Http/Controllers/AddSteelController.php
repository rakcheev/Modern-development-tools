<?php

/*
*
*Контроллер добавления
*стали
*
*для администратора
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Steel;
use App\Popularity;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class AddSteelController extends Controller
{
    public function index()
    {
        $title = 'Добавление стали';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        
        return view('addSteel')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers']);
    }

	/*
	*
	*Добавление
	*стали
	*
	*для администратора
	*
	*/
    public function addSteel(request $request)
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;

        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
	        $resData['success'] = 0;
			echo json_encode($resData);
			return;
		}
  		
        $imageMain = $request->file('imageMain');
        if (\File::size($imageMain) > 2*1024*1024 || \File::size($imageMain) === 0) { //проверяем размер
            $resData['success'] = 2;
            $resData['message'] = 'размер картинок велик';
            echo json_encode($resData);
            return;
        }
        if ($imageMain) {
	        $data = getimagesize($imageMain);
	        $widthImage = $data[0];
			$heightImage = $data[1];
	        if ($widthImage !== 720 || $heightImage !== 310) { //проверяем разрешение
	            $resData['success'] = 3;
	            $resData['message'] = 'неверное разрешение картинки';
	            echo json_encode($resData);
	            return;
	        }
        }
        
		try {
			$popularityClass = new Popularity();
			$popularityClass->shiftPopularityInsert(STEEL_ID, $request->popularity);
			$steel = new Steel();
			$steel->name = $request->name;
			$steel->price = $request->price;
			$steel->color = $request->color;
			$steel->description = $request->description;
			$steel->damask = $request->damask;
			$steel->popularity = $request->popularity;
			$steel->viewable = $request->viewable;
			$steel->save();
			$steelId = $steel->id;
			$popularityClass->arrangePopularity(STEEL_ID);
		} catch (\Exception $e){
			$resData['success'] = 0;
			$resData['message'] = 'Неизвестная ошибка';
			echo json_encode($resData);
			return;
		}
	    try {
		    if ($imageMain) {
		            $ext = \File::extension($imageMain->getClientOriginalName());
		            $imageNameMain = 'steel' . $steelId . '.' . $ext;
		            $imageMain->move(base_path('public/img/patternsConstruct'), $imageNameMain);
		            $steel->texture = $imageNameMain;
		            $steel->save();
		    }
	    } catch (\Exception $e) {
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
