<?php

/*
*
* Middleware для доступа админа и главного мастера 
* 
* для post
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class AdminAndMainMasterPost
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
            case MAIN_OPERATOR:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вне вашей компетенции']);
                break;
            case ADMIN:
            case MAIN_MASTER:
                return $next($request);
                break;
            default:
                return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вне вашей компетенции']);
                break;
        }
    }
}