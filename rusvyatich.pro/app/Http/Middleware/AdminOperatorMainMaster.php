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

class AdminOperatorMainMaster
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
                return redirect('/home');
                break;
            case OPERATOR:
            case MAIN_OPERATOR:
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