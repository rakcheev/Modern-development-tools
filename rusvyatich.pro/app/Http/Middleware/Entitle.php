<?php

/*
*
*Middleware для перехода на домашнюю страницу
*
*для разных типов пользователей
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class Entitle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $status = Session::has('status') ? Session::get('status') : null;
        switch ($status) {
            case CUSTOMER:  
            case MASTER:
                return redirect()->route('customerHome');
                break;
            case ADMIN:    
            case MAIN_MASTER:      
            case OPERATOR:
            case MAIN_OPERATOR:
                return redirect()->route('adminHome');
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}
