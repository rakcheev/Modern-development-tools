<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeOfSend;

class TypeSendController extends Controller
{
    /*
    *
    *Получение описаний частей ножа в конструкторе
    *
    *return json
    */
    public function getDescription(request $request)
    {
        echo json_encode(TypeOfSend::where('id', $request->id)->get()->first());
    }
}
