<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckTradeLinkIsSet
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
        $user = Auth::user();
        if (!$user->tradelink || $user->tradelink == '') {
            flash()->error('You must give us your trade link to continue!');

            return redirect('/settings');
        }

        return $next($request);
    }
}
