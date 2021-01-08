<?php

/*
*
*Контроллер изменения
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
use App\KnifePropertiesInOrder;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class UpdateSteelController extends Controller
{
    public function index($id)
    {
        $title = 'Изменение стали';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/');
        }
        $token = str_random(8);
        Session::put('token', $token);
        Session::save();
        UserOwn::where('id', $userId)->update(['tok' => Hash::make($token), 'last_visit' => DB::raw('NOW()')]);
        $steel = Steel::find($id);
        $presence = KnifePropertiesInOrder::where('id_typeOfSteel', $id)->exists();
        return view('addSteel')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'steel'=>$steel, 'presence'=>$presence]);
    }

	/*
	*
	*Изменение
	*стали
	*
	*для администратора
	*
	*/
    public function updateSteel($id, $action, request $request)
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
  		if ($action == 0) {
  			if (KnifePropertiesInOrder::where('id_typeOfSteel', $id)->exists()) {
	  			$steel = Steel::find($id);
	  			$steel->viewable = 0;
	  			$steel->save();
				$resData['success'] = 1;
				$resData['res'] = 1;
				echo json_encode($resData);
				return;
	  		} else {
	  			$imgName = Steel::where('id', $id)->value('texture');
		  		if (file_exists(base_path('public/img/patternsConstruct') . '/' . $imgName)) {
	               	unlink(base_path('public/img/patternsConstruct') . '/' . $imgName);
				}	
	  			Steel::destroy($id);
				$resData['success'] = 1;
				$resData['res'] = 2;
				echo json_encode($resData);
				return;
	  		}
  		} else if ($action == 1) {
			try {
				$steel = Steel::find($id);
				$popularityClass = new Popularity();
				$popularityClass->shiftPopularityUpdate(STEEL_ID, $request->popularity, $steel->popularity);
				$steel->name = $request->name;
				$steel->price = $request->price;
				$steel->description = $request->description;
				$steel->damask = $request->damask;
				$steel->popularity = $request->popularity;
				$steel->color = $request->color;
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
			$resData['success'] = 1;
			$resData['sddds'] = $request->viewable;
			echo json_encode($resData);
			return;
		}
		$resData['success'] = 0;
		echo json_encode($resData);
    }
}
