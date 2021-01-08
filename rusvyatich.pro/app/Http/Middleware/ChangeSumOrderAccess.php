<?php

/*
*
* Middleware для установления суммы заказу
* 
* для главного мастера и оператора
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class ChangeSumOrderAccess
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
            case MAIN_MASTER:
            case OPERATOR:
            case MAIN_OPERATOR:
                return $next($request);
                break;
            default:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вы не можете устанавливать сумму заказа']);
                break;
        }
    }
}