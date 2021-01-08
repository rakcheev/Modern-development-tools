<?php

/*
*
* Middleware для доступа к заказу 
* из корзины/конструктра
* для разных типов пользователей
*
*/

namespace App\Http\Middleware;

use App\Order;
use App\UserOwn;
use Closure;
use Session;

class ViewOrderCartConstruct
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
        $orderId = $request->route('id');
        if (!Order::where('id', $orderId)->exists()) return redirect()->route('home');
        switch ($status) {
            case CUSTOMER: 
                return redirect()->route('customerHome');
                break;
            case MASTER:
                if (!Order::where([
                    ['id', '=', $orderId],
                    ['id_master', '=', $userId]
                ])->exists()) {
                    return redirect()->route('home');
                }
                return $next($request);
                break;
            case ADMIN: 
            case MAIN_OPERATOR:
                return $next($request);
                break;
            case MAIN_MASTER:   
                /*if (Order::where([
                    ['id', '=', $orderId],
                    ['id_status', '=', ENTERED]
                ])->exists()) {
                    return redirect()->route('home');
                }*/
                return $next($request);
                break;
            case OPERATOR:
                if (UserOwn::where('id', Order::where([
                    ['id', '=', $orderId]
                ])->value('id_user'))->value('id_operator') !== $userId) {
                    return redirect()->route('home');
                }
                return $next($request);
                break;
            default:
                return redirect('/auth');
                break;
        }
    }
}
