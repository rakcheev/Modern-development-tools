<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use Session;
use Closure;
use Cookie;


class OnlyAuth
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
        $userId = Session::has('userId') ? Session::get('userId') : null;
        $token = Session::has('token') ? Session::get('token') : null;
        if (!$userId || !$status  || !$token || !UserOwn::where('id', $userId)->exists() || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            Session::flush();
            if ($request->isMethod('get')) {
                return redirect('/auth');
            }
            if ($request->isMethod('post')) {
                return response()->json(['success'=>0, 'res'=>UNAUTH_ERROR, 'message'=>'Вы не авторизованы']);
            }
        }
        return $next($request);
    }
}
