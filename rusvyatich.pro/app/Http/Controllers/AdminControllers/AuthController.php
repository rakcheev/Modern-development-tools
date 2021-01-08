<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Usirs;

class AuthController extends Controller
{

    protected $username = 'phone';
	public function index(){
		$header = "Вход в личный кабинет"

		return view('auth').with(['header'->$header]);
	}

}
