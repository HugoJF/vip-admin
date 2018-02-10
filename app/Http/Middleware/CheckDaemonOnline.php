<?php

namespace App\Http\Middleware;

use App\Classes\Daemon;
use Closure;

class CheckDaemonOnline
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
        if (Daemon::isOnline() !== true) {
            flash()->error('Our daemon server is offline, Steam servers are unreachable!');

            return redirect('/');
        }

        return $next($request);
    }
}
