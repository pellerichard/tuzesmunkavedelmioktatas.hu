<?php

namespace App\Http\Middleware;

use Closure;
use App\ModuleConst;

class IsNotLoggedIn
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
        return $next($request);

        if(!$request->session()->has('account')) {
            return $next($request);
        }
        $role = $request->session()->get('role');

        /* return response('Nincs ehhez az oldalhoz jogosultságod.
        <br>
        Egyedi hibakód: <b>#1302</b>
        <br>
        (Erre a hibakódra hivatkozva jelezheted felénk a problémát.)',401); */

        $route = '';
        switch($role) {
            case ModuleConst::PERMISSION_ADMIN:
                $route = '/admin/cegek';
                break;
            case ModuleConst::PERMISSION_COMPANY_OWNER:
                $route = '/';
                break;
            case ModuleConst::PERMISSION_NORMAL:
                $route = '/teszt';
                break;
            default:
                $route = '/bejelentkezes';
                break;
        }
        return redirect($route);
    }
}
