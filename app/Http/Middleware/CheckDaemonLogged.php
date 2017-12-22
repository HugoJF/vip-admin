<?php

namespace App\Http\Middleware;

use App\Http\Controllers\DaemonController;
use Closure;

class CheckDaemonLogged
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (DaemonController::isLoggedIn() !== true) {
            flash()->error('Our daemon server is not logged to Steam servers.');

            return redirect('/');
        }

        return $next($request);
    }
}
