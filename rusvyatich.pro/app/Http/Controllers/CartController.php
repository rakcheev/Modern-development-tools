<?php

/*
*
*Контроллер корзины
*
*
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Knife;
use App\KnifeSerial;
use App\Cart;
use Session;

ignore_user_abort(true);
set_time_limit(0);

class CartController extends Controller
{
    /*
    *Добавление в корзину
    *
    * return json
    */
	public function addToCart(request $request)
	{	
		$itemId = $request->id;
		$resData = array();
	    $rsProduct = Knife::find($itemId);
	   	$oldCart = Session::has('cart') ? Session::get('cart') : null;
	    $cart = new Cart($oldCart);
	    $try = $cart->add($rsProduct, $rsProduct->id);
	    if ($try) {
		   	$request->session()->put('cart', $cart);
		    $resData['quantity'] = count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
		    $resData['sum'] = $request->session()->get('cart')->totalPrice;
		    $resData['success'] = 1;
		    $resData['cart']=session('cart');
		} else {
			$resData['success'] = 1;
			$resData['cart']=count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
		    $resData['quantity'] = count($request->session()->get('cart')->items);
		    $resData['sum'] = $request->session()->get('cart')->totalPrice;
		}
	    echo json_encode($resData);
	}

	/*
    *Добавление в корзину серийного
    *
    * return json
    */
	public function addToCartSerial(request $request)
	{	
		$itemId = $request->id;
		$resData = array();
	    $rsProduct = KnifeSerial::find($itemId);
	   	$oldCart = Session::has('cart') ? Session::get('cart') : null;
	    $cart = new Cart($oldCart);
    	$try = $cart->addSerial($rsProduct, $rsProduct->id, $request->count);
	    if ($try) {
		   	$request->session()->put('cart', $cart);
		    $resData['quantity'] = count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
		    $resData['sum'] = $request->session()->get('cart')->totalPrice;
		    $resData['success'] = 1;
		    $resData['cart']=session('cart');
		} else {
			$resData['success'] = 1;
			$resData['cart']=session('cart');
		    $resData['quantity'] = count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
		    $resData['sum'] = $request->session()->get('cart')->totalPrice;
		}
	    
	    echo json_encode($resData);
	}


    /*
    * Поиск в корзине по id и проверка не куплен ли уже данный продукт
    * 
    * return json(bool)
    */
	public function searchInCart(request $request)
	{
		$itemId = $request->id;
		$resData = array();
		$bool = false;
		$boolSec = false;
		if (isset($request->session()->get('cart')->items)){
			$bool = in_array($itemId,  $request->session()->get('cart')->items);
		}
		$resData['bool'] = $bool;
		if (Knife::where('id', $itemId)->value('id_status') !== AVAILABLE) $boolSec = true;
		$resData['buyed'] = $boolSec;
		echo json_encode($resData);

	}

	/*
	*Находит содержание корзины
	*
	*return json
	*/
	public function checkCart(request $request)
	{
		$resData = array();
		if (isset($request->session()->get('cart')->items) || isset($request->session()->get('cart')->itemsSerial)) {
			$resData['success'] = 1;
			$resData['sum'] = $request->session()->get('cart')->totalPrice;
			$resData['quantity'] = count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
			$resData['cart'] = session('cart');
		} else {
			$resData['success'] = 1;
			$resData['quantity'] = 0;
			$resData['cart'] = session('cart');
		}
		echo json_encode($resData);
	}

	/*
	*Получает все обекты ножей из корзины
	* 
	*return json
	*/
	public function getKnifesForCart(request $request)
	{
		$knifes = [];
		$knifesSerial = [];
		try {
		
			if (isset($request->session()->get('cart')->items)) {
	        	$ids = $request->session()->get('cart')->items;
	        	$knifes = DB::table('knifes')->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')->select(['knifes.id', 'knifes.name', 'knifes.id_steel','knifes.id_steel', 'knifes.blade_length', 'knifes.blade_width', 'knifes.blade_thickness', 'knifes.handle_length', 'knifes.price', 'knifes.description', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images` where `knife_images`.`id_knife` =  `knifes`.`id` order by `knife_images`.`number` asc limit 1) as  image')])->whereIn('knifes.id', $ids)->get();
	        	if ($knifes) {
	        		foreach ($knifes as $value) {
	        			$value->type = 'individual';
	        		}
	        	}
	        }
	        if (isset($request->session()->get('cart')->itemsSerial)) {
	        	$preArraySerial = $request->session()->get('cart')->itemsSerial;
	        	
	        	foreach ($preArraySerial as $value) {
	        		$idsSerial[] = $value['id'];
	        	}

	        	$knifesSerial = DB::table('serial_knifes')->join('typeOfSteel', 'serial_knifes.id_steel', '=', 'typeOfSteel.id')->select(['serial_knifes.id', 'serial_knifes.name', 'serial_knifes.id_steel','serial_knifes.id_steel', 'serial_knifes.blade_length', 'serial_knifes.blade_width', 'serial_knifes.blade_thickness', 'serial_knifes.handle_length', 'serial_knifes.price', 'serial_knifes.description', 'typeOfSteel.name as steel', DB::raw('(select `image` from `knife_images_serial` where `knife_images_serial`.`id_knife` =  `serial_knifes`.`id` order by `knife_images_serial`.`number` asc limit 1) as  image')])->whereIn('serial_knifes.id', $idsSerial)->get();
	        	
	        	if($knifesSerial) {
		        	foreach ($knifesSerial as $key => $value) {
		        		$j = 0;
		        		foreach ($preArraySerial as $key1 => $value1) {
		        		 	if($value1['id'] == $value->id) {
		        		 		$j = $key1;
		        		 		break;
		        		 	}
		        		}
		        		$value->count = $preArraySerial[$j]['count'];
		        		$value->type = 'serial';
		        	}
	        	}
        	}
		} catch (\Exception $e) {
			 $resData['error'] = 'ошибка';
		}
        $resData['success'] = 1;
        $resData['items'] = $knifes;
        $resData['itemsSerial'] = $knifesSerial;
    	echo json_encode($resData);
	}


	/*
	*Удаление продукта из корзины
	*
	*return json
	*/
	public function removeFromCart(request $request)
	{
		$resData = array();
        $id = $request->id;
	    $rsProduct = Knife::find($id);
	    if ($rsProduct) {
		   	$oldCart = Session::has('cart') ? Session::get('cart') : null;
		    $cart = new Cart($oldCart);
		    $try = $cart->remove($rsProduct, $rsProduct->id);
		    if ($try) {
		    	try {
				   	$request->session()->put('cart', $cart);
				    $resData['quantity'] = count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
				    $resData['sum'] = $request->session()->get('cart')->totalPrice;
				    $resData['success'] = 1;
				    $resData['cart'] = session('cart');
		    	} catch (\Exception $e) {
					$resData['success'] = 0;
					$resData['cart'] = session('cart');	
		    		echo json_encode($resData);
		    		return;
		    	}
			} else {
				$resData['success'] = 0;
				$resData['cart'] = session('cart');
			}
		} else {
			$resData['success'] = 0;
			$resData['cart'] = session('cart');
		}
	    echo json_encode($resData);
	}

	/*
	*Удаление серийного продукта из корзины
	*
	*return json
	*/
	public function removeFromCartSerial(request $request)
	{
        $id = $request->id;
	    $rsProduct = KnifeSerial::find($id);
	    if ($rsProduct) {
		   	$oldCart = Session::has('cart') ? Session::get('cart') : null;
		    $cart = new Cart($oldCart);
		    $try = $cart->removeSerial($rsProduct, $rsProduct->id);
		    if ($try) {
		    	try {
				   	$request->session()->put('cart', $cart);
				    $resData['quantity'] = count($request->session()->get('cart')->items) + count($request->session()->get('cart')->itemsSerial);
				    $resData['sum'] = $request->session()->get('cart')->totalPrice;
				    $resData['success'] = 1;
				    $resData['cart'] = session('cart');
		    	} catch (\Exception $e) {
					$resData['success'] = 0;
					$resData['cart'] = session('cart');	
		    		echo json_encode($resData);
		    		return;
		    	}
			} else {
				$resData['success'] = 0;
				$resData['cart'] = session('cart');
			}

		} else {
			$resData['success'] = 0;
			$resData['cart'] = session('cart');
		}
	    echo json_encode($resData);
	}

	/*
	*Удаление всех ножей из корзины
	*
	*return json
	*/
	public function cleanCart(request $request)
	{	
		$resData = array();
		Session::forget('cart');
		$resData['cartf'] = Session::get('cart');
		if (Session::has('cart')) {
			Session::save();
		    $resData['success'] = 0;
		    $resData['message'] = 'Ошибка очистки корзины'; 
			echo json_encode($resData);
			return;
		}
		Session::save();
		$resData['success'] = 1;  
		echo json_encode($resData);
	}
}

