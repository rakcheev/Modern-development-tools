<?php

/*
*
* Middleware для доступа к просмотру мастеров
* 
* 
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class WorkersPageAccess
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
            case OPERATOR:
                    return redirect()->route('home');
                break;
            case ADMIN:      
                    return $next($request);
                    break;
            case MAIN_MASTER:   
                if (\Route::currentRouteName() == 'workersAdminHome') {
                    return redirect()->route('workersMasterHome');
                } else {
                    return $next($request);
                }
                break;
            case MAIN_OPERATOR:
                if (\Route::currentRouteName() == 'workersMasterHome') {
                    return redirect()->route('workersAdminHome');
                } else {
                    return $next($request);
                }
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}
