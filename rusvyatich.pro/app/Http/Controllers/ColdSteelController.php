<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ColdSteelController extends Controller
{
	public function index()
    {
        $title = 'Вятич - Холодное оружие';
        $descriptionPage = 'Описание ножей не попадающих под категорию холодного оружия.';
        return view('coldSteel')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage]);
	}
}
