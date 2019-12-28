<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class ValidateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        /**
         * @Validáció
         * @session account (int)
         * @session role (int)
         * @session keepalive (unixtime)
         */

        /** @Ha van felhasználója a sessionbe. */
        if(Session::has('account')) {
            /** @Lekérdezés hogy lejárt-e a sessionje a felhasználókat. */
            if(Session::has('keepalive')) {
                /** @Ha lejárt a @session életideje. */
                if(time() > Session::get('keepalive')) {
                    /** @Töröljük az összes @session-t, miszerint lejárt az életideje. */
                    $this->destructor($request);
                }
            } else {
                /** @Töröljük az összes @session-t, miszerint nem találtunk egy adott kulcsot. */
                $this->destructor($request);
            }
        } else {
            /** @Töröljük az összes @session-t, biztonsági okokból, nehogy valamelyik megragadjon a rendszerben. */
            $this->destructor($request);
        }
        return $next($request);
    }

    public function destructor($request) {
        Session::forget('account');
        Session::forget('role');
        Session::forget('keepalive');
    }
}
