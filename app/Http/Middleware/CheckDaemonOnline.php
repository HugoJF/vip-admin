<?php

namespace App\Http\Middleware;

use App\Http\Controllers\DaemonController;
use Closure;

class CheckDaemonOnline
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
        if(DaemonController::isOnline() !== true) {
            flash()->error('Our daemon server is offline, Steam servers are unreachable!');
            return redirect('/');
        }

        return $next($request);
    }
}
