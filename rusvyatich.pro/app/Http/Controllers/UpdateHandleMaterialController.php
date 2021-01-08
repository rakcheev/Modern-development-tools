<?php

/*
*
*Контроллер изменения
*для администратора
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UserOwn;
use App\HandleMaterial;
use App\Popularity;
use App\KnifePropertiesInOrder;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class UpdateHandleMaterialController extends Controller
{   
    public function index($id)
    {
        $title = 'Изменение метариала рукояти';
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
        $handleMaterial = HandleMaterial::find($id);
         $presence = KnifePropertiesInOrder::where('id_typeOfHandleMaterial', $id)->exists();
        return view('addHandleMaterial')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic", 'workersLink'=>'/home/workers', 'handleMaterial'=>$handleMaterial, 'presence'=>$presence]);
    }

	/*
	*
	*Изменение
	*материала рукояти
	*
	*для администратора
	*
	*/
    public function updateHandleMaterial($id, $action, request $request)
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

  		if ($action == 0) {
  			try {
	  			if (KnifePropertiesInOrder::where('id_typeOfHandleMaterial', $id)->exists()) {
		  			$handleMaterial = HandleMaterial::find($id);
		  			$handleMaterial->viewable = 0;
		  			$handleMaterial->save();
					$resData['success'] = 1;
					$resData['res'] = 1;
					echo json_encode($resData);
					return;
		  		} else {
		  			$imgName = HandleMaterial::where('id', $id)->value('texture');
		  			if (file_exists(base_path('public/img/patternsConstruct') . '/' . $imgName)) {
	                	unlink(base_path('public/img/patternsConstruct') . '/' . $imgName);
					}	
		  			HandleMaterial::destroy($id);
					$resData['success'] = 1;
					$resData['res'] = 2;
					echo json_encode($resData);
					return;
		  		}	
  			} catch (\Exception $e) {
  				$resData['message'] = 'Неизвестная ошибка';
	            echo json_encode($resData);
	            return;
  				
  			}
  		} else if ($action == 1) {
			try {
				$handleMaterial = HandleMaterial::find($id);
				$popularityClass = new Popularity();
				$popularityClass->shiftPopularityUpdate(HANDLE_MATERIAL_ID, $request->popularity, $handleMaterial->popularity);
				$handleMaterial->name = $request->name;
				$handleMaterial->price = $request->price;
				$handleMaterial->color = $request->color;
				$handleMaterial->description = $request->description;
				$handleMaterial->popularity = $request->popularity;
				$handleMaterial->color = $request->color;
				$handleMaterial->nabor = $request->nabor;
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
			$resData['success'] = 1;
			echo json_encode($resData);
			return;
		}
		$resData['success'] = 0;
		echo json_encode($resData);
    }
}
