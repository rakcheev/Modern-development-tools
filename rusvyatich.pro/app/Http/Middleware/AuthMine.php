<?php

/*
*
* Middleware доступно только пользователю
* 
* 
*
*/

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Hash;
use App\UserOwn;
use Illuminate\Support\Facades\DB;
use Closure;
use Session;
use Cookie;

class AuthMine
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
        if (!$userId || !$status  || !$token || !Hash::check($token, UserOwn::where('id', $userId)->value('tok'))) {
            $rememberme = Cookie::get('rememberme', NULL);
            $login= Cookie::get('login', 0);
            $user = UserOwn::select(['id', 'status', 'phone', 'remember_me', 'tok'])->where('phone', $login);
            if ($login && $rememberme && $user->count()) {
                $user = $user->first();
                if (Hash::check($rememberme, $user->remember_me) && $user->account_status_id != DELETED) {
                    $token = str_random(10);
                    Session::put('userId', $user->id);
                    Session::put('token', $token);
                    Session::put('status', $user->status);
                    Session::save();
                    $user->tok = Hash::make($token);
                    $user->save();
                }
            }
        } else {
            $diff =  abs(DB::table('users')
            ->select(DB::raw('TIMESTAMPDIFF(SECOND, NOW(), `last_tok_update`) as razn'))->where('id', $userId)->value('razn'));
            if ( $diff > 60) {
                $token = str_random(8);
                UserOwn::where('id', $userId)->update(['tok' => Hash::make($token), 'last_tok_update' => DB::raw('NOW()'), 'last_visit' => DB::raw('NOW()')]);
                Session::put('token', $token);
                Session::save();
            } else {
                UserOwn::where('id', $userId)->update(['last_visit' => DB::raw('NOW()')]);
            }
        }
        return $next($request);
    }
}