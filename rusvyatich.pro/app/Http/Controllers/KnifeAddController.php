<?php

/*
*
*Контроллер страницы 
*добавления ножа
*
*для работников
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Knife;
use App\KnifeImage;
use App\KnifeSerial;
use App\KnifeSerialImage;
use App\UserOwn;
use App\ProductStatus;
use App\ResizeImage;
use App\Steel;
use Session;

ignore_user_abort(true);
set_time_limit(0);


class KnifeAddController extends Controller
{
    public function index()
    {
        $title = 'Добавление ножа';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        $steels = Steel::all();
        $statuses = ProductStatus::where('id', AVAILABLE)
        ->orWhere('id', NOT_AVAILABLE)->get();
        return view('knifeEdit')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'statuses'=>$statuses, 'steels'=>$steels]);
	}

    public function serial()
    {
        $title = 'Добавление серийного ножа';
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }
        $steels = Steel::all();
        return view('knifeEdit')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers','steels'=>$steels, 'serial'=>true]);
	}

	/*Добавление ножа ajax*/
	public function addKnife(request $request) 
	{	
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	$resData['success'] = 0;
        	echo json_encode($resData);
        	return;
        }

		$image1 = $request->file('image1');
		$image2 = $request->file('image2');
		$image3 = $request->file('image3');
		$image4 = $request->file('image4');
		$image5 = $request->file('image5');
		$image6 = $request->file('image6');
		if ($image1 == '' && $image2 == '' && $image3 == '' && $image4 == '' && $image5 == '' && $image6 == '')
		{
			$resData['success'] = 0;
			$resData['note'] = 'нет фотографий';
			echo json_encode($resData);
			return;
		}
		$photos = ['photos'=>[$image1, $image2, $image3, $image4, $image5, $image6 ]];
		foreach ($photos['photos'] as $photo) {
			if ($photo){
				if (\File::size($photo) > 2*1024*1024 || \File::size($photo) === 0) {
					$resData['success'] = 2;
					$resData['message'] = 'размер картинок велик';
					echo json_encode($resData);
					return;
				}
		        $data = getimagesize($photo);
		        $widthImage = $data[0];
				$heightImage = $data[1];
		        if ($widthImage !== 1432 || $heightImage !== 800) {
	                $resData['success'] = 0;
	                $resData['note'] = 'Разрешение картинки должно быть 1432x800';
		            echo json_encode($resData);
		            return;
		        }
			}
		}
        try {
	        $knife = new Knife();
	        $knife->name = $request->name;
	        $knife->id_steel = $request->steel;
	        $knife->blade_length = $request->blade_length;
	        $knife->blade_width = $request->blade_width;
	        $knife->blade_thickness = $request->blade_thickness;
	        $knife->handle_length = $request->handle_length;
	        $knife->id_status = $request->status;
	        $knife->handle = $request->handle;
	        $knife->price = $request->price;
	        $knife->description = $request->description;
	        $knife->save();
	        $knifeId = $knife->id;
		} catch (\Exception $e) {
			$resData['success'] = 0;
			$resData['message'] = 'Неизвестная ошибка';
			echo json_encode($resData);
			return;
		}
        try {
        	$i = 0;
			foreach ($photos['photos'] as $photo) {
				$i++;
				if ($photo) {
			        $ext = \File::extension($photo->getClientOriginalName());
			        $imageName = $knifeId . '('.$i.')' . '.' . $ext; 
					$photo->move(base_path('public/img/imgStorage'), $imageName);
					ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStorageMin/'), 500);
					ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStoragePhone/'), 800);
					$KnifeImage = new KnifeImage();
					$KnifeImage->id_knife = $knifeId;
					$KnifeImage->number = $i;
					$KnifeImage->image = $imageName;
					$KnifeImage->save();
                    $knifeImages[] = $KnifeImage->id;
                }
            }
        } catch (\Exception $e) {
            if (isset($knifeImages)){
                foreach ($knifeImages as $knifeImage) {
                    KnifeImage::destroy($knifeImage);
                }
            }
            Knife::destroy($knifeId);
            $resData['success'] = 0;
            $resData['message'] = 'Неизвестная ошибка';
            echo json_encode($resData);
            return;
        }
        $resData['success'] = 1;
        $resData['mess'] = $knifeId;
        echo json_encode($resData);
    }

	/*Добавление ножа ajax*/
	public function addSerialKnife(request $request) 
	{	
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
        	$resData['success'] = 0;
        	echo json_encode($resData);
        	return;
        }

		$image1 = $request->file('image1');
		$image2 = $request->file('image2');
		$image3 = $request->file('image3');
		$image4 = $request->file('image4');
		$image5 = $request->file('image5');
		$image6 = $request->file('image6');
		if ($image1 == '' && $image2 == '' && $image3 == '' && $image4 == '' && $image5 == '' && $image6 == '')
		{
			$resData['success'] = 0;
			$resData['note'] = 'нет фотографий';
			echo json_encode($resData);
			return;
		}
		$photos = ['photos'=>[$image1, $image2, $image3, $image4, $image5, $image6 ]];
		foreach ($photos['photos'] as $photo) {
			if ($photo){
				if (\File::size($photo) > 2*1024*1024 || \File::size($photo) === 0) {
					$resData['success'] = 2;
					$resData['message'] = 'размер картинок велик';
					echo json_encode($resData);
					return;
				}
		        $data = getimagesize($photo);
		        $widthImage = $data[0];
				$heightImage = $data[1];
		        if ($widthImage !== 1432 || $heightImage !== 800) {
	                $resData['success'] = 0;
	                $resData['note'] = 'Разрешение картинки должно быть 1432x800';
		            echo json_encode($resData);
		            return;
		        }
			}
		}
        try {
	        $knife = new KnifeSerial();
	        $knife->name = $request->name;
	        $knife->id_steel = $request->steel;
	        $knife->blade_length = $request->blade_length;
	        $knife->blade_width = $request->blade_width;
	        $knife->blade_thickness = $request->blade_thickness;
	        $knife->handle_length = $request->handle_length;
	        $knife->count = $request->count;
	        $knife->handle = $request->handle;
	        $knife->price = $request->price;
	        $knife->description = $request->description;
	        $knife->viewable = $request->viewable;
	        $knife->save();
	        $knifeId = $knife->id;
		} catch (\Exception $e) {
			$resData['success'] = 0;
			$resData['message'] = 'Неизвестная ошибка' .$e->getMessage();
			echo json_encode($resData);
			return;
		}
        try {
        	$i = 0;
			foreach ($photos['photos'] as $photo) {
				$i++;
				if ($photo) {
			        $ext = \File::extension($photo->getClientOriginalName());
			        $imageName = $knifeId . 'Serial('.$i.')' . '.' . $ext; 
					$photo->move(base_path('public/img/imgStorage'), $imageName);
					ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStorageMin/'), 500);
					ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStoragePhone/'), 800);
					$KnifeImage = new KnifeSerialImage();
					$KnifeImage->id_knife = $knifeId;
					$KnifeImage->number = $i;
					$KnifeImage->image = $imageName;
					$KnifeImage->save();
                    $knifeImages[] = $KnifeImage->id;
                }
            }
        } catch (\Exception $e) {
            if (isset($knifeImages)){
                foreach ($knifeImages as $knifeImage) {
                    KnifeSerialImage::destroy($knifeImage);
                }
            }
            KnifeSerial::destroy($knifeId);
            $resData['success'] = 0;
            $resData['message'] = 'Неизвестная ошибка' .$e->getMessage();
            echo json_encode($resData);
            return;
        }
        $resData['success'] = 1;
        $resData['mess'] = $knifeId;
        echo json_encode($resData);
    }
}
