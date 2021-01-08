<?php

/*
*
*Контроллер добавления
*больстера
*
*для администратора
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Bolster;
use App\Popularity;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class AddBolsterController extends Controller
{	
    public function index()
    {
        $title = 'Добавление больстера';
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
        return view('addBolster')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers']);
    }

	/*
	*
	*Добавление
	*больстера
	*
	*для администратора
	*
	*/
    public function addBolster(request $request)
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
	        if ($widthImage !== 930 || $heightImage !== 400) { //проверяем разрешение
	            $resData['success'] = 3;
	            $resData['message'] = 'неверное разрешение картинки';
	            echo json_encode($resData);
	            return;
	        }
        }

		try {
			$popularityClass = new Popularity();
			$popularityClass->shiftPopularityInsert(BOLSTER_ID, $request->popularity);
			$bolster = new Bolster();
			$bolster->name = $request->name;
			$bolster->price = $request->price;
			$bolster->path = $request->path;
			$bolster->width = $request->width;
			$bolster->color = $request->color;
			$bolster->restricted = $request->restrict;
			$bolster->description = $request->description;
			$bolster->popularity = $request->popularity;
			$bolster->save();
			$bolsterId = $bolster->id;
			$popularityClass->arrangePopularity(BOLSTER_ID);
		} catch (\Exception $e){
			$resData['success'] = 0;
			$resData['message'] = 'Неизвестная ошибка';
			echo json_encode($resData);
			return;
		}
	    try {
		    if ($imageMain) {
		            $ext = \File::extension($imageMain->getClientOriginalName());
		            $imageNameMain = 'bolster' . $bolsterId . '.' . $ext;
		            $imageMain->move(base_path('public/img/patternsConstruct'), $imageNameMain);
		            $bolster->texture = $imageNameMain;
		            $bolster->save();
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
