<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Knife;
use App\KnifeImage;
use App\KnifeSerial;
use App\KnifeSerialImage;
use App\Steel;
use App\UserOwn;
use App\Order;
use App\TypeOfSend;
use Session;


class KnifeController extends Controller
{

	/*
	*
	* Страница
	* просмотра
	* ножа покупателем
	*
	*/
    public function index($id)
    {

        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        $knife = Knife::select(['knifes.*', 'typeOfSteel.name as steel'])->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')->where('knifes.id',$id)->first();
        if (!$knife) return redirect()->route('shop');
        if ($knife->id_status !== AVAILABLE) {
	        $byer = UserOwn::select('id')
	        ->join('orders','orders.id_user', '=', 'users.id')
	        ->join('products_in_order', 'products_in_order.id_order', '=', 'orders.id')
	        ->where([
	        	['users.id', '=', $userId],
	        	['orders.id_status', '<>', REFUSED]
	        ])->count();
	        if ($byer == 0) return redirect()->route('shop');
        }
        $KnifeImages = KnifeImage::where('id_knife', $id)->orderBy('number', 'asc')->get();
        $steels = Steel::all();
        $typeOfSends = TypeOfSend::where('viewable', 1)->get();
        $title = 'Нож '. $knife->steel. '. Название: ' . $knife->name;
        $descriptionPage = $knife->description;
        $typeOfSends = TypeOfSend::where('viewable', 1)->get();
        $username = 'Войти';
        if ($userId && UserOwn::where('id', $userId)->exists()) {
            if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
                Session::forget('userId');
                Session::forget('status');
                Session::forget('token');
                Session::save();
            } else {
                $username = UserOwn::where('id', $userId)->value('name');
                UserOwn::where('id', $userId)->update(['last_visit' => DB::raw('NOW()')]);
            }
        }
        return view('product')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage,'typeOfSends' => $typeOfSends, 'knife'=>$knife, 'KnifeImages' => $KnifeImages , 'username' => $username, 'steels'=>$steels, 'typeOfSends' => $typeOfSends]);
    }

	/*
	*
	* Страница
	* просмотра
	* ножа покупателем
	*
	*/
    public function serial($id)
    {

        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        $knife = KnifeSerial::select(['serial_knifes.*', 'typeOfSteel.name as steel'])->join('typeOfSteel', 'serial_knifes.id_steel', '=', 'typeOfSteel.id')->where('serial_knifes.id',$id)->first();
        if (!$knife || $knife->viewable !== 1) return redirect()->route('shop');
        $KnifeImages = KnifeSerialImage::where('id_knife', $id)->orderBy('number', 'asc')->get();
        $steels = Steel::all();
        $typeOfSends = TypeOfSend::where('viewable', 1)->get();
        $title = 'Нож '. $knife->steel. '. Название: ' . $knife->name;
        $descriptionPage = $knife->description;
        $typeOfSends = TypeOfSend::where('viewable', 1)->get();
        $username = 'Войти';
        if ($userId && UserOwn::where('id', $userId)->exists()) {
            if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
                Session::forget('userId');
                Session::forget('status');
                Session::forget('token');
                Session::save();
            } else {
                $username = UserOwn::where('id', $userId)->value('name');
                UserOwn::where('id', $userId)->update(['last_visit' => DB::raw('NOW()')]);
            }
        }
        return view('productSerial')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage,'typeOfSends' => $typeOfSends, 'knife'=>$knife, 'KnifeImages' => $KnifeImages , 'username' => $username, 'steels'=>$steels, 'typeOfSends' => $typeOfSends]);
    }



	/*
	*
	* Получение
	* ножа
	*
	*
	*/
    public function getKnife($id, request $request)
    {
        $knife = Knife::select(['id', 'name', 'steel', 'blade_length', 'blade_width', 'blade_thickness', 'handle_length', 'price', 'handle', 'description'])->where('id', $id)->get()->first();
        $knifeImages = KnifeImage::where('id_knife', $id)->get();
		$resData['success'] = 1;
		$resData['knife'] = $knife;
		$resData['knifeImages'] = $knifeImages;
		echo json_encode($resData);
		return;
	}

    public function getKnifesByParameters(request $request)
    {	 
    	$postData = array();
	    $array2 = explode('&', $request->postData);
	    foreach($array2 as $str) {
	        list($key, $value) = explode('=', $str);
	        $postData[strval($key)] = $value;
	    }
		$steels = $request->steels;
    	if ($postData['sortCost'] == 1) {
    		$sort = 'asc';
    	} else {
    		$sort = 'desc';
    	}
    	if ($steels) {
	        $knifesIndividual = DB::table('knifes')->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')->select(['knifes.id', 'knifes.name', 'knifes.id_steel','knifes.id_steel', 'knifes.blade_length', 'knifes.blade_width', 'knifes.blade_thickness', 'knifes.handle_length', 'knifes.price', 'knifes.description', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images` where `knife_images`.`id_knife` =  `knifes`.`id` order by `knife_images`.`number` asc limit 1) as  image'), DB::raw("'individual' as source")])->where([
				['knifes.blade_thickness', '>=', $postData['minBladeButt']],
				['knifes.blade_thickness', '<=', $postData['maxBladeButt']],
				['knifes.blade_width', '>=', $postData['minBladeWidth']],
				['knifes.blade_width', '<=', $postData['maxBladeWidth']],
				['knifes.blade_length', '>=', $postData['minBladeLength']],
				['knifes.blade_length', '<=', $postData['maxBladeLength']],
				['knifes.price', '>=', $postData['minCost']],
				['knifes.price', '<=', $postData['maxCost']],
				['knifes.id_status','=', 1]
			])
			->where(function($query) use ($steels){
				
					foreach($steels as $steel) {
					    $query->orWhere('id_steel', '=', $steel);
					}
				
			});

	        $knifes = DB::table('serial_knifes')->join('typeOfSteel', 'serial_knifes.id_steel', '=', 'typeOfSteel.id')->select(['serial_knifes.id', 'serial_knifes.name', 'serial_knifes.id_steel','serial_knifes.id_steel', 'serial_knifes.blade_length', 'serial_knifes.blade_width', 'serial_knifes.blade_thickness', 'serial_knifes.handle_length', 'serial_knifes.price', 'serial_knifes.description', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images_serial` where `knife_images_serial`.`id_knife` =  `serial_knifes`.`id` order by `knife_images_serial`.`number` asc limit 1) as  image'), DB::raw("'serial' as source")])->where([
				['serial_knifes.blade_thickness', '>=', $postData['minBladeButt']],
				['serial_knifes.blade_thickness', '<=', $postData['maxBladeButt']],
				['serial_knifes.blade_width', '>=', $postData['minBladeWidth']],
				['serial_knifes.blade_width', '<=', $postData['maxBladeWidth']],
				['serial_knifes.blade_length', '>=', $postData['minBladeLength']],
				['serial_knifes.blade_length', '<=', $postData['maxBladeLength']],
				['serial_knifes.price', '>=', $postData['minCost']],
				['serial_knifes.price', '<=', $postData['maxCost']],
				['serial_knifes.viewable','=', 1]
			])
			->where(function($query) use ($steels){
				
					foreach($steels as $steel) {
					    $query->orWhere('id_steel', '=', $steel);
					}
				
			})
			->union($knifesIndividual)
			->orderBy('price', $sort)
			->get();
			if(!$knifes->isEmpty()) {
	            Session::put('minBladeButt', $postData['minBladeButt']);
	            Session::put('maxBladeButt', $postData['maxBladeButt']);
	            Session::put('minBladeWidth', $postData['minBladeWidth']);
	            Session::put('maxBladeWidth', $postData['maxBladeWidth']);
	            Session::put('minBladeLength', $postData['minBladeLength']);
	            Session::put('maxBladeLength', $postData['maxBladeLength']);
	            Session::put('steelsSet', $steels);
	            Session::put('costSortSet', $postData['sortCost']);
	            Session::put('minCost', $postData['minCost']);
	            Session::put('maxCost', $postData['maxCost']);
	            
	        } else {
	            Session::forget('minBladeButt');
	            Session::forget('maxBladeButt');
	            Session::forget('minBladeWidth');
	            Session::forget('maxBladeWidth');
	            Session::forget('minBladeLength');
	            Session::forget('maxBladeLength');
	            Session::forget('steelsSet');
	            Session::forget('costSortSet');
	            Session::forget('minCost');
	            Session::forget('maxCost');
	        }
	            Session::save();
		} else {
        	$knifes = array();
		}
		$resData['success'] = 1;
		$resData['knifes'] = $knifes;
		echo json_encode($resData);
		return;
	}
}
