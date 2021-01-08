<?php

/*
*
* Middleware для сообщений
* 
* для post
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class ForAuthorize
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
            case OPERATOR:
            case ADMIN:
            case MAIN_MASTER:
            case MAIN_OPERATOR:
                return $next($request);
                break;
            default:
                return response()->json(['success'=>0, 'res'=>UNAUTH_ERROR, 'message'=>'Вне вашей компетенции']);
                break;
        }
    }
}