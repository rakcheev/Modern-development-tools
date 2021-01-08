<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
	public function index()
    {
        $title = 'Вятич - примеры работ';
        $descriptionPage = 'Примеры ножей кузницы Вятич';
        return view('album')->with(['title'=>$title, 'descriptionPage'=>$descriptionPage]);
	}
}
