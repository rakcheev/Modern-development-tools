<?php

/*
*
* Middleware для доступа админа и главного оператора
* 
* для get
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class AdminAndMainOperator
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
            case MAIN_MASTER:
                return redirect()->route('home');
                break;
            case ADMIN:
            case MAIN_OPERATOR:
                return $next($request);
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}