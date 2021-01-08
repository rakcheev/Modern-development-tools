<?php

/*
*
* Middleware для изменения статуса заказа
* 
* для работников
*
*/
namespace App\Http\Middleware;

use App\Order;
use App\IndividualOrder;
use Closure;
use Session;

class ChangeStatusOrderAccess
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
        switch($status) {
            case MASTER:
            case MAIN_MASTER:
            case ADMIN:
            case OPERATOR:
            case MAIN_OPERATOR:
                return $next($request);
                break;
            default:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Не в вашей компетенции']);
                break;
        }
    }
}