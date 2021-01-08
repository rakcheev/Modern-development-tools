<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Knife;
use App\KnifeSerial;
use App\UserOwn;
use App\TypeOfSend;
use App\Steel;
use Session;

class ShopController extends Controller
{
    public function index()
    {
        $status = Session::has('status') ? Session::get('status') : null;
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        $title = 'Вятич - магазин кованых ножей.';
        $descriptionPage = 'Ножи ручной работы на заказ. Купить кованые ножи. Отправка по всей России.';
        $knifes = Knife::select(['knifes.*', 'typeOfSteel.name as steel'])->join('typeOfSteel', 'knifes.id_steel', '=', 'typeOfSteel.id')->where('id_status', 1)->take(30)->get();
        $typeOfSends = TypeOfSend::where('viewable', 1)->get();
        $unpayedId =  Session::has('orderUnpayed') ? Session::get('orderUnpayed') : null;
        $steels = Steel::orderBy('popularity')->get();

        $minCostIndividual = Knife::select(['price'])->where('id_status', 1);
        $minCost = intval(KnifeSerial::select(['price'])->where('viewable', 1)->union($minCostIndividual)->get()->min('price'));
        
        $maxCostIndividual = Knife::select(['price'])->where('id_status', 1);
        $maxCost = intval(KnifeSerial::select(['price'])->where('viewable', 1)->union($maxCostIndividual)->get()->max('price'));

        $minBladeLengthIndividual = Knife::select(['blade_length'])->where('id_status', 1);
        $minBladeLength = intval(KnifeSerial::select(['blade_length'])->where('viewable', 1)->union($minBladeLengthIndividual)->get()->min('blade_length'));

        $maxBladeLengthIndividual = Knife::select(['blade_length'])->where('id_status', 1);
        $maxBladeLength = intval(KnifeSerial::select(['blade_length'])->where('viewable', 1)->union($maxBladeLengthIndividual)->get()->max('blade_length'));

        $minBladeWidthIndividual = Knife::select(['blade_width'])->where('id_status', 1);
        $minBladeWidth = intval(KnifeSerial::select(['blade_width'])->where('viewable', 1)->union($minBladeWidthIndividual)->get()->min('blade_width'));

        $maxBladeWidthIndividual = Knife::select(['blade_width'])->where('id_status', 1);
        $maxBladeWidth = intval(KnifeSerial::select(['blade_width'])->where('viewable', 1)->union($maxBladeWidthIndividual)->get()->max('blade_width'));

        $minBladeButtIndividual = Knife::select(['blade_thickness'])->where('id_status', 1);
        $minBladeButt = KnifeSerial::select(['blade_thickness'])->where('viewable', 1)->union($minBladeButtIndividual)->get()->min('blade_thickness');

        $maxBladeButtIndividual = Knife::select(['blade_thickness'])->where('id_status', 1);
        $maxBladeButt = KnifeSerial::select(['blade_thickness'])->where('viewable', 1)->union($maxBladeButtIndividual)->get()->max('blade_thickness');

        $minCostSet = Session::has('minCost') ? Session::get('minCost') : $minCost;
        $minCostSet = ($minCostSet < $minCost) ? $minCost : $minCostSet;

        $maxCostSet = Session::has('maxCost') ? Session::get('maxCost') : $maxCost;
        $maxCostSet = ($maxCostSet > $maxCost) ? $maxCost : $maxCostSet;

        $minBladeLengthSet = Session::has('minBladeLength') ? Session::get('minBladeLength') : $minBladeLength;
        $minBladeLengthSet = ($minBladeLengthSet < $minBladeLength) ? $minBladeLength : $minBladeLengthSet;

        $maxBladeLengthSet = Session::has('maxBladeLength') ? Session::get('maxBladeLength') : $maxBladeLength;
        $maxBladeLengthSet = ($maxBladeLengthSet > $maxBladeLength) ? $maxBladeLength : $maxBladeLengthSet;

        $minBladeWidthSet = Session::has('minBladeWidth') ? Session::get('minBladeWidth') : $minBladeWidth;
        $minBladeWidthSet = ($minBladeWidthSet < $minBladeWidth) ? $minBladeWidth : $minBladeWidthSet;

        $maxBladeWidthSet = Session::has('maxBladeWidth') ? Session::get('maxBladeWidth') : $maxBladeWidth;
        $maxBladeWidthSet = ($maxBladeWidthSet > $maxBladeWidth) ? $maxBladeWidth : $maxBladeWidthSet;

        $minBladeButtSet = Session::has('minBladeButt') ? Session::get('minBladeButt') : $minBladeButt;
        $minBladeButtSet = ($minBladeButtSet < $minBladeButt) ? $minBladeButt : $minBladeButtSet;

        $maxBladeButtSet = Session::has('maxBladeButt') ? Session::get('maxBladeButt') : $maxBladeButt;;
        $maxBladeButtSet = ($maxBladeButtSet > $maxBladeButt) ? $maxBladeButt : $maxBladeButtSet;

        $costSortSet = Session::has('costSortSet') ? Session::get('costSortSet') : 1;
        $steelsSet = Session::has('steelsSet') ? Session::get('steelsSet') : Steel::pluck('id')->toArray();
        if ($unpayedId) {
            $orderUnpayed = Order::select('id_status', 'id_payed')->where('id', $unpayedId)->first();
            if (($orderUnpayed && ($orderUnpayed->id_status === REFUSED || $orderUnpayed->id_payed === PAYED)) || !$orderUnpayed) {
                Session::forget('accessToken');
                Session::forget('orderUnpayed');
                Session::save();
            }
        }
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
        return view('shop')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage,'typeOfSends' => $typeOfSends, 'products'=>$knifes, 'username' => $username, 'tok'=>$unpayedId, 'steels'=>$steels, 'minCost'=>$minCost, 'maxCost'=>$maxCost, 'minBladeLength'=>$minBladeLength, 'maxBladeLength'=>$maxBladeLength, 'minBladeWidth'=>$minBladeWidth, 'maxBladeWidth'=>$maxBladeWidth, 'minBladeButt'=>$minBladeButt, 'maxBladeButt'=>$maxBladeButt,'minCostSet'=>$minCostSet, 'maxCostSet'=>$maxCostSet, 'minBladeLengthSet'=>$minBladeLengthSet, 'maxBladeLengthSet'=>$maxBladeLengthSet, 'minBladeWidthSet'=>$minBladeWidthSet, 'maxBladeWidthSet'=>$maxBladeWidthSet, 'minBladeButtSet'=>$minBladeButtSet, 'maxBladeButtSet'=>$maxBladeButtSet, 'costSortSet' => $costSortSet, 'steelsSet' => $steelsSet]);
    }
}
