<?php

/*
*
* Middleware post запросов только для админа
* 
*
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class OnlyAdminPost
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
        if ($status === ADMIN) {
            return $next($request);
        } else {
            return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Не в вашей компетенции']);
        }
    }
}