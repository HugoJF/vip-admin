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
            flash()->error(__('messages.middleware-daemon-not-logged'));

            return redirect('/');
        }

        return $next($request);
    }
}
