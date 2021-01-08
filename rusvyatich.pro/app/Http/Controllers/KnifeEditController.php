<?php

/*
*
*Контроллер страницы 
*редактирования ножа
*
*для работников
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Knife;
use App\KnifeImage;
use App\KnifeSerial;
use App\KnifeSerialImage;
use App\Steel;
use App\UserOwn;
use App\ProductStatus;
use App\ProductsInOrder;
use App\ResizeImage;
use Session;

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

ignore_user_abort(true);
set_time_limit(0);

class KnifeEditController extends Controller
{
    public function index($id)
    {
        $title = 'Нож № ' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $knife = Knife::find($id);
        if (!$knife) {
            return redirect()->route('knifesPage');
        }
        $steels = Steel::all();
        $photos = KnifeImage::where('id_knife', $id)->get();
        switch ($knife->id_status) {
            case IN_REFUSED:
                $statuses = ProductStatus::where('id', IN_REFUSED)->orWhere('id', AVAILABLE)->get();
                break;

            case AVAILABLE:
                $statuses = ProductStatus::where('id', AVAILABLE)->orWhere('id', NOT_AVAILABLE)->get();
                break;

            case NOT_AVAILABLE:
                $statuses = ProductStatus::where('id', NOT_AVAILABLE)->orWhere('id', AVAILABLE)->get();
                break;
            
            default: 
                $statuses = ProductStatus::where('id', $knife->id_status)->get(); 
                break;
        }
        return view('knifeEdit')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'knife'=>$knife, 'statuses'=>$statuses, 'steels'=>$steels, 'photos'=>$photos]);
	}

    public function serial($id)
    {
        $title = 'Серия ножа № ' . $id;
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            return redirect('/auth');
        }

        $knife = KnifeSerial::find($id);
        if (!$knife) {
            return redirect()->route('knifesPage');
        }
        $steels = Steel::all();
        $photos = KnifeSerialImage::where('id_knife', $id)->get();
        return view('knifeEdit')->with(['title'=>$title, 'toUser'=>"/home/user", 'ordersLink'=>"/home", 'knifesLink'=>"/home/knifes", 'changeConstructLink'=>"/home/changeConstruct", 'statisticLink'=>"/home/statistic ", 'workersLink'=>'/home/workers', 'knife'=>$knife, 'steels'=>$steels, 'photos'=>$photos, 'serial'=>true]);
    }

    /*Изменеие ножа ajax*/
    public function updateKnife($id, request $request) 
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
        $drop1 = $request->drop1;
        $drop2 = $request->drop2;
        $drop3 = $request->drop3;
        $drop4 = $request->drop4;
        $drop5 = $request->drop5;
        $drop6 = $request->drop6;

        $drops = [$drop1, $drop2, $drop3, $drop4, $drop5, $drop6];
        $photos = ['photos'=>[$image1, $image2, $image3, $image4, $image5, $image6 ]];
        $countImages = KnifeImage::where('id_knife', $id)->count();
        $dropCount = count(array_keys($drops, 1));
        if ($countImages <= $dropCount && $countImages !== 0){
            $resData['success'] = 0;
            $resData['note'] = 'Нельзя удалить все фото';
            echo json_encode($resData);
            return;
        }

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
            $knife = Knife::find($id);
            $knife->name = $request->name;
            $knife->id_steel = $request->steel;
            $knife->blade_length = $request->blade_length;
            $knife->blade_width = $request->blade_width;
            $knife->blade_thickness = $request->blade_thickness;
            $knife->handle_length = $request->handle_length;
            $knife->handle = $request->handle;
            $knife->price = $request->price;
            $knife->description = $request->description;
            switch ($knife->id_status) {
                case IN_REFUSED:
                    $possible = array(IN_REFUSED, AVAILABLE);
                    if (in_array($request->status, $possible)) $knife->id_status = $request->status;
                    break;

                case AVAILABLE:
                    $possible = array(AVAILABLE, NOT_AVAILABLE);
                    if (in_array($request->status, $possible)) $knife->id_status = $request->status;
                    break;

                case NOT_AVAILABLE:
                    $possible = array(NOT_AVAILABLE, AVAILABLE);
                    if (in_array($request->status, $possible)) $knife->id_status = $request->status;
                    break;
            }
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
                    $imageName = $id . '('.$i.')' . '.' . $ext; 
                    $photo->move(base_path('public/img/imgStorage'), $imageName);
                    ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStorageMin/'), 500);
                    ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStoragePhone/'), 800);
                    $KnifeImage = KnifeImage::where([
                        ['id_knife', $id],
                        ['number', $i]
                    ])->first();
                    if ($KnifeImage) {
                        $KnifeImage->id_knife = $id;
                        $KnifeImage->number = $i;
                        $KnifeImage->image = $imageName;
                        $KnifeImage->save();
                    } else {
                        $KnifeImage = new KnifeImage();
                        $KnifeImage->id_knife = $id;
                        $KnifeImage->number = $i;
                        $KnifeImage->image = $imageName;
                        $KnifeImage->save();
                    }
                }
            }
            $i = 0;
            foreach ($drops as $drop) {
                $i++;
                if ($drop == 1) {
                    $KnifeImage = KnifeImage::where([
                        ['id_knife', $id],
                        ['number', $i]
                    ])->first();
                    if ($KnifeImage) {
                        if (file_exists(base_path('public/img/imgStorage') . '/' . $KnifeImage->image)) {
                            unlink(base_path('public/img/imgStorage') . '/' . $KnifeImage->image);
                        }   
                        if (file_exists(base_path('public/img/imgStorageMin') . '/' . $KnifeImage->image)) {
                            unlink(base_path('public/img/imgStorageMin') . '/' . $KnifeImage->image);
                        }   
                        if (file_exists(base_path('public/img/imgStoragePhone') . '/' . $KnifeImage->image)) {
                            unlink(base_path('public/img/imgStoragePhone') . '/' . $KnifeImage->image);
                        } 
                        $KnifeImage->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            $resData['success'] = 0;
            $resData['message'] = 'Неизвестная ошибка';
            echo json_encode($resData);
            return;
        }
        $resData['success'] = 1;
        $resData['mess'] = $knifeId;
        echo json_encode($resData);
    }


    /*Изменеие серийного ножа ajax*/
    public function updateSerialKnife($id, request $request) 
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
        $drop1 = $request->drop1;
        $drop2 = $request->drop2;
        $drop3 = $request->drop3;
        $drop4 = $request->drop4;
        $drop5 = $request->drop5;
        $drop6 = $request->drop6;

        $drops = [$drop1, $drop2, $drop3, $drop4, $drop5, $drop6];
        $photos = ['photos'=>[$image1, $image2, $image3, $image4, $image5, $image6 ]];
        $countImages = KnifeSerialImage::where('id_knife', $id)->count();
        $dropCount = count(array_keys($drops, 1));
        if ($countImages <= $dropCount && $countImages !== 0){
            $resData['success'] = 0;
            $resData['note'] = 'Нельзя удалить все фото';
            echo json_encode($resData);
            return;
        }

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
            $knife = KnifeSerial::find($id);
            $knife->name = $request->name;
            $knife->id_steel = $request->steel;
            $knife->blade_length = $request->blade_length;
            $knife->blade_width = $request->blade_width;
            $knife->blade_thickness = $request->blade_thickness;
            $knife->handle_length = $request->handle_length;
            $knife->handle = $request->handle;
            $knife->price = $request->price;
            $knife->description = $request->description;
            $knife->count = $request->count;
            $knife->viewable = $request->viewable;
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
                    $imageName = $id . 'Serial('.$i.')' . '.' . $ext; 
                    $photo->move(base_path('public/img/imgStorage'), $imageName);
                    ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStorageMin/'), 500);
                    ResizeImage::resizeImage($ext, base_path('public/img/imgStorage/') . $imageName, $imageName,  base_path('public/img/imgStoragePhone/'), 800);
                    $KnifeImage = KnifeSerialImage::where([
                        ['id_knife', $id],
                        ['number', $i]
                    ])->first();
                    if ($KnifeImage) {
                        $KnifeImage->id_knife = $id;
                        $KnifeImage->number = $i;
                        $KnifeImage->image = $imageName;
                        $KnifeImage->save();
                    } else {
                        $KnifeImage = new KnifeSerialImage();
                        $KnifeImage->id_knife = $id;
                        $KnifeImage->number = $i;
                        $KnifeImage->image = $imageName;
                        $KnifeImage->save();
                    }
                }
            }
            $i = 0;
            foreach ($drops as $drop) {
                $i++;
                if ($drop == 1) {
                    $KnifeImage = KnifeSerialImage::where([
                        ['id_knife', $id],
                        ['number', $i]
                    ])->first();
                    if ($KnifeImage) {
                        if (file_exists(base_path('public/img/imgStorage') . '/' . $KnifeImage->image)) {
                            unlink(base_path('public/img/imgStorage') . '/' . $KnifeImage->image);
                        }   
                        if (file_exists(base_path('public/img/imgStorageMin') . '/' . $KnifeImage->image)) {
                            unlink(base_path('public/img/imgStorageMin') . '/' . $KnifeImage->image);
                        }   
                        if (file_exists(base_path('public/img/imgStoragePhone') . '/' . $KnifeImage->image)) {
                            unlink(base_path('public/img/imgStoragePhone') . '/' . $KnifeImage->image);
                        } 
                        $KnifeImage->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            $resData['success'] = 0;
            $resData['message'] = 'Неизвестная ошибка';
            echo json_encode($resData);
            return;
        }
        $resData['success'] = 1;
        $resData['mess'] = $knifeId;
        echo json_encode($resData);
    }

    public function dropKnife($id, request $request) 
    {   
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $resData['success'] = 0;
            echo json_encode($resData);
            return;
        }

        if (!ProductsInOrder::where('id_product', $id)->exists()) {
            try {
                $knife = Knife::find($id);
                $KnifeImages = KnifeImage::where([
                    ['id_knife', $id]
                ])->get();
                foreach ($KnifeImages as $KnifeImage) {
                    if (file_exists(base_path('public/img/imgStorage') . '/' . $KnifeImage->image)) {
                        unlink(base_path('public/img/imgStorage') . '/' . $KnifeImage->image);
                    }   
                    if (file_exists(base_path('public/img/imgStorageMin') . '/' . $KnifeImage->image)) {
                        unlink(base_path('public/img/imgStorageMin') . '/' . $KnifeImage->image);
                    }   
                    if (file_exists(base_path('public/img/imgStoragePhone') . '/' . $KnifeImage->image)) {
                        unlink(base_path('public/img/imgStoragePhone') . '/' . $KnifeImage->image);
                    } 
                    $KnifeImage->delete();
                }
                Knife::destroy($id);
                $resData['success'] = 1;
                $resData['mess'] = 'Нож удален';
                echo json_encode($resData);
                return;
            } catch (\Exception $e) {
                $resData['success'] = 0;
                $resData['mess'] = 'Ошибка удаления ножа';
                $resData['res'] = 0;
                echo json_encode($resData);
                return;
            }
        }  
        $resData['success'] = 0;
        $resData['mess'] = 'Нож в заказе';
        $resData['res'] = KNIFE_IN_ORDER;
        echo json_encode($resData);
        return;
    }
}
