<?php


/*
*
* Middleware для редиреката главному мастеру и админу
* 
* для разных типов пользователей
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class WorkersRedirect
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
            case ADMIN:
            case MAIN_OPERATOR:    
                return redirect()->route('workersAdminHome');
                break;
            case MAIN_MASTER:      
                return redirect()->route('workersMasterHome');
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}