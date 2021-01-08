<?php

/*
*
* Middleware для доступа мастером, заказчиком
* 
* для post
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class MasterAndMainCustomerPost
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
            case MAIN_MASTER:
            case MASTER:
                return $next($request);
                break;
            case ADMIN:
            case OPERATOR:
            case MAIN_OPERATOR:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вне вашей компетенции']);
                break;
            default:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вне вашей компетенции']);
                break;
        }
    }
}