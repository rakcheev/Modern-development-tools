<?php


/*
*
* Middleware для установления мастера заказу
* 
* для главного мастера
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class ChangeMasterAccess
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
        if ($status === MAIN_MASTER) {
            return $next($request);
        } else {
            return response()->json(['success'=>0, 'res'=>ACCESS_ERROR, 'message'=>'Вы не можете устанавливать мастера']);
        }
    }
}
