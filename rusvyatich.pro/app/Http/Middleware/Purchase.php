<?php

/*
*
* Middleware для ограничения заказов
* сотрудниками компании
*
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class Purchase
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
                return $next($request);
                break;
            case MASTER:
            case ADMIN:    
            case MAIN_MASTER:      
            case OPERATOR:
            case MAIN_OPERATOR:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вы сотрудник компании']);
                break;
        }
        return $next($request);
    }
}
