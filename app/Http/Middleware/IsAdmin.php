<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        if (!Auth::user()->isAdmin()) {
            flash()->error(__('messages.middleware-not-allowed-to-see', ['url' => $request->fullUrl()]));

            return redirect('/');
        }

        return $next($request);
    }
}
