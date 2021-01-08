<?php

/*
*
*Middleware для домашних страниц пользователей
*
*для разных типов пользователей
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class ViewListOrders
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
                if (\Route::currentRouteName() != 'customerHome') {
                    return redirect()->route('customerHome');
                } else {
                    return $next($request);
                }
                break;
            case ADMIN:        
            case OPERATOR:
            case MAIN_OPERATOR:
                if (\Route::currentRouteName() == 'customerHome') {
                    return redirect()->route('adminHome');
                } else {
                    return $next($request);
                }
                break;
            case MAIN_MASTER:  
                return $next($request);
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}
