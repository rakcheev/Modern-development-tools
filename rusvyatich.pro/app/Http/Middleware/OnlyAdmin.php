<?php

/*
*
* Middleware доступно только админу
* 
* 
*
*/

namespace App\Http\Middleware;

use Closure;
use Session;

class OnlyAdmin
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
        if ($status !== ADMIN) {
            return redirect()->route('home');
        }
        if (is_null($status)) return redirect('/auth');
        return $next($request);
    }
}