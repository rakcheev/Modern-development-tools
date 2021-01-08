<?php

/*
*
* Middleware для доступа админа и главного мастера 
* 
* для get
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class AdminAndMainMaster
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
                return redirect()->route('home');
                break;
            case MAIN_OPERATOR:
                if (\Route::currentRouteName() == 'addWorker') {
                    return $next($request);
                } else {
                    return redirect()->route('home');
                }
                break;
            case ADMIN:
            case MAIN_MASTER:
                return $next($request);
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}