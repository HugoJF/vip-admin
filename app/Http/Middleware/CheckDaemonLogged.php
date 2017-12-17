<?php

namespace App\Http\Middleware;

use App\Http\Controllers\DaemonController;
use Closure;

class CheckDaemonLogged
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
        if(DaemonController::isLoggedIn() !== true) {
            return redirect('/');
        }

        return $next($request);
    }
}
