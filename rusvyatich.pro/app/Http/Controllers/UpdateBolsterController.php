<?php

/*
*
*Контроллер изменения больстера
*для администратора
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\Bolster;
use App\Popularity;
use App\KnifePropertiesInOrder;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class UpdateBolsterController extends Controller
{
    public function index($id)
    {
        $title = 'Изменение больстера';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $bolster = Bolster::find($id);
        $presence = KnifePropertiesInOrder::where('id_typeOfBolster', $id)->exists();
        return view('addBolster')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'bolster'=>$bolster, 'presence'=>$presence]);
    }

	/*
	*
	*Изменение
	*больстера
	*
	*для администратора
	*
	*/
    public function updateBolster($id, $action, request $request)
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

  		if ($action == 0) {
  			if (KnifePropertiesInOrder::where('id_typeOfBolster', $id)->exists()) {
	  			$bolster = Bolster::find($id);
	  			$bolster->viewable = 0;
	  			$bolster->save();
				$resData['success'] = 1;
				$resData['res'] = 1;
				echo json_encode($resData);
				return;
	  		} else {
	  			$imgName = Bolster::where('id', $id)->value('texture');	
		  		if (file_exists(base_path('public/img/patternsConstruct') . '/' . $imgName)) {
	               	unlink(base_path('public/img/patternsConstruct') . '/' . $imgName);
				}	
	  			Bolster::destroy($id);
				$resData['success'] = 1;
				$resData['res'] = 2;
				echo json_encode($resData);
				return;
	  		}
  		} else if ($action == 1) {
			try {
				$bolster = Bolster::find($id);
				$popularityClass = new Popularity();
				$popularityClass->shiftPopularityUpdate(BOLSTER_ID, $request->popularity, $bolster->popularity);
				$bolster->name = $request->name;
				$bolster->price = $request->price;
				$bolster->path = $request->path;
				$bolster->width = $request->width;
				$bolster->color = $request->color;
				$bolster->restricted = $request->restrict;
				$bolster->description = $request->description;
				$bolster->popularity = $request->popularity;
				$bolster->viewable = $request->viewable;
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
			$resData['success'] = 1;
			echo json_encode($resData);
			return;
		}
		$resData['success'] = 0;
		echo json_encode($resData);
    }
}
