<?php

/*
*
*Контроллер добавления
*материала ручки
*
*для администратора
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\HandleMaterial;
use App\Popularity;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class AddHandleMaterialController extends Controller
{   
    public function index()
    {
        $title = 'Добавление метариала рукояти';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        return view('addHandleMaterial')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers']);
    }

	/*
	*
	*Добавление
	*материала рукояти
	*
	*для администратора
	*
	*/
    public function addHandleMaterial(request $request)
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
	        if ($widthImage !== 1245 || $heightImage !== 525) { //проверяем разрешение
	            $resData['success'] = 3;
	            $resData['message'] = 'неверное разрешение картинки';
	            echo json_encode($resData);
	            return;
	        }
        }

		try {
			$popularityClass = new Popularity();
			$popularityClass->shiftPopularityInsert(HANDLE_MATERIAL_ID, $request->popularity);
			$handleMaterial = new HandleMaterial();
			$handleMaterial->name = $request->name;
			$handleMaterial->price = $request->price;
			$handleMaterial->color = $request->color;
			$handleMaterial->nabor = $request->nabor;
			$handleMaterial->description = $request->description;
			$handleMaterial->popularity = $request->popularity;
			$handleMaterial->viewable = $request->viewable;
			$handleMaterial->save();
			$handleMaterialId = $handleMaterial->id;
			$popularityClass->arrangePopularity(HANDLE_MATERIAL_ID);
		} catch (\Exception $e){
			$resData['success'] = 0;
			$resData['message'] = 'Неизвестная ошибка';
			echo json_encode($resData);
			return;
		}
	    try {
		    if ($imageMain) {
		            $ext = \File::extension($imageMain->getClientOriginalName());
		            $imageNameMain = 'handle' . $handleMaterialId . '.' . $ext;
		            $imageMain->move(base_path('public/img/patternsConstruct'), $imageNameMain);
		            $handleMaterial->texture = $imageNameMain;
		            $handleMaterial->save();
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
