<?php

/*
*
*Контроллер ajax запросов для отрисовки svg изображения ножа
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Steel;
use App\Blade;
use App\Bolster;
use App\Handle;
use App\HandleMaterial;
use App\AdditionOfBlade;
use App\Spusk;

ignore_user_abort(true);
set_time_limit(0);

class ConstructorAppController extends Controller
{
    
    /*
    *
    *Получение данных о частях svg рисунка ножа
    *
    *return json
    */
    public function getPath(request $request)
    {   
        $id = $request->id;
        $part = $request->part;
        if ($part == PATH_BLADE_GET_ID) {
            $path = Blade::select(['typeOfBlade.id', 'typeOfBlade.path', 'typeOfBlade.bent', 'typeOfBlade.free', 'hardness.k as hardness'])
            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
            {
                $join->on('typeOfBlade.hardness', '=', 'hardness.id');
            })
            ->where('typeOfBlade.id', $id)->get()->first();
        }
        if ($part == PATH_BOLSTER_GET_ID) {
            $path = Bolster::select(['id', 'path', 'width', 'texture', 'color', 'restricted', 'price', 'simmetrical'])->where('id', $id)->get()->first();
        }
        if ($part == PATH_HANDLE_GET_ID) {
            $path = Handle::select(['typeOfHandle.id', 'typeOfHandle.path', 'typeOfHandle.pathFultang', 'typeOfHandle.pathKlepka','typeOfHandle.pathFixBlade','typeOfHandle.pathFixBladeFultang', 'typeOfHandle.restricted', 'typeOfHandle.heightHandle', 'hardness.k as hardness'])
            ->join(DB::raw('(SELECT * FROM `hardness`) hardness'), function($join)
            {
                $join->on('typeOfHandle.hardness', '=', 'hardness.id');
            })
            ->where('typeOfHandle.id', $id)->get()->first();
        }
        echo json_encode($path);
    }

    /*
    *
    *Получение данных о цветах частей svg рисунка ножа
    *
    *return json
    */
    public function getTexture(request $request)
    {   
        $id = $request->id;
        $part = $request->part;
        if ($part == COLOR_STEEL_ID) {
            $color = Steel::select(['id', 'texture', 'color', 'price', 'damask'])->where('id', $id)->get()->first();
        }
        if ($part == COLOR_HANDLE_ID) {
            $color = HandleMaterial::select(['id' ,'texture', 'color', 'price', 'nabor'])->where('id', $id)->get()->first();
        }
        echo json_encode($color);
    }

    /*
    *
    *Получение описаний частей ножа в конструкторе
    *
    *return json
    */
    public function getDescription(request $request)
    {   
        $id = $request->id;
        $stage = $request->stage;
        if ($stage == STEEL_ID) {
            $desc = Steel::select(['id', 'description', 'name'])->where('id', $id)->get()->first();
        }
        if ($stage == BLADE_ID) {
            $desc = Blade::select(['id', 'description', 'name'])->where('id', $id)->get()->first();
        }
        if ($stage == BOLSTER_ID) {
            $desc = Bolster::select(['id', 'description', 'image_description', 'name'])->where('id', $id)->get()->first();
        }
        if ($stage == HANDLE_ID) {
            $desc = Handle::select(['id', 'description', 'name'])->where('id', $id)->get()->first();
        }
        if ($stage == HANDLE_MATERIAL_ID) {
            $desc = HandleMaterial::select(['id', 'description', 'name'])->where('id', $id)->get()->first();
        }
        if ($stage == ADDITION_ID) {
            $desc = AdditionOfBlade::select(['id', 'description', 'image_description','name'])->where('id', $id)->get()->first();
        }
        if ($stage == SPUSK_ID) {
            $desc = Spusk::select(['id', 'description', 'image_description','name'])->where('id', $id)->get()->first();
        }
        echo json_encode($desc);
    }


    /*
    *
    *Получение данных о цветах частей svg рисунка ножа
    *
    *return json
    */
    public function sortConstruct(request $request)
    { 
        switch ($request->type) {
            case 1:
                $steels = Steel::where('viewable', 1)->orderBy('price')->orderBy('popularity')->get();
                $blades = Blade::where('viewable', 1)->orderBy('hardness')->orderBy('popularity')->get();
                $bolsters = Bolster::where('viewable', 1)->orderBy('price')->orderBy('popularity')->get();
                $handles = Handle::where('viewable', 1)->orderBy('hardness')->orderBy('popularity')->get();
                $handleMaterials = HandleMaterial::where('viewable', 1)->orderBy('price')->orderBy('popularity')->get();
                break;
            case 2:
                $steels = Steel::where('viewable', 1)->orderBy('price', 'DESC')->orderBy('popularity')->get();
                $blades = Blade::where('viewable', 1)->orderBy('hardness', 'DESC')->orderBy('popularity')->get();
                $bolsters = Bolster::where('viewable', 1)->orderBy('price', 'DESC')->orderBy('popularity')->get();
                $handles = Handle::where('viewable', 1)->orderBy('hardness', 'DESC')->orderBy('popularity')->get();
                $handleMaterials = HandleMaterial::where('viewable', 1)->orderBy('price', 'DESC')->orderBy('popularity')->get();
                break;
            case 3:
                $steels = Steel::where('viewable', 1)->orderBy('popularity')->get();
                $blades = Blade::where('viewable', 1)->orderBy('popularity')->get();
                $bolsters = Bolster::where('viewable', 1)->orderBy('popularity')->get();
                $handles = Handle::where('viewable', 1)->orderBy('popularity')->get();
                $handleMaterials = HandleMaterial::where('viewable', 1)->orderBy('popularity')->get();
                break;
             
             default:
                $steels = Steel::where('viewable', 1)->orderBy('price')->orderBy('popularity')->get();
                $blades = Blade::where('viewable', 1)->orderBy('hardness')->orderBy('popularity')->get();
                $bolsters = Bolster::where('viewable', 1)->orderBy('price')->orderBy('popularity')->get();
                $handles = Handle::where('viewable', 1)->orderBy('hardness')->orderBy('popularity')->get();
                $handleMaterials = HandleMaterial::where('viewable', 1)->orderBy('price')->orderBy('popularity')->get();
                break;
         } 
        $resData['success'] = 1;
        $resData['steels'] = $steels;
        $resData['blades'] = $blades;
        $resData['bolsters'] = $bolsters;
        $resData['handles'] = $handles;
        $resData['handleMaterials'] = $handleMaterials;
        echo json_encode($resData);
        return;
    }

}
