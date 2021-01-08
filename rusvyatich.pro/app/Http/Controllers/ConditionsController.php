<?php

/*
*
* Получение страницы
* условий использования
*
*
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConditionsController extends Controller
{
	public function index()
    {
        $title = 'Вятич - условия использования';
        $descriptionPage = 'Условия использования кузницы Вятич';
        return view('conditions')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage]);
	}
}