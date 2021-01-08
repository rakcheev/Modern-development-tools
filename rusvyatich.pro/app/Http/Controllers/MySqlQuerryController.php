<?php

/*
*
*Контроллер добавления
*клинка
*
*для администратора
*
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Spusk;

ignore_user_abort(true);
set_time_limit(0);

class MySqlQuerryController extends Controller
{
    public function sqlQuerry()
    {
        Schema::create('device_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->timestamp('date')->useCurrent();
            $table->text('orientation');
            $table->text('user_agent');
            $table->text('battery');
        });
		return redirect('/');

    }
}
