<?php

namespace App\Http\Middleware;

use App\Classes\Daemon;
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
        if (Daemon::isLoggedIn() !== true) {
            flash()->error('Our daemon server is not logged to Steam servers.');

            return redirect('/');
        }

        return $next($request);
    }
}
