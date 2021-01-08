<?php

/*
*
* Middleware для доступа к просмотру свойств конструктора 
* 
* для разных типов пользователей
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class KnifeProperties
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
                return $next($request);
            default:
                return redirect('/auth');
                break;
        }
    }
}
