<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AcceptedTerms
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
        if (Auth::user()->accepted != true) {
            flash()->error(__('messages.middleware-must-accept'));

            return redirect()->route('home');
        }

        return $next($request);
    }
}
