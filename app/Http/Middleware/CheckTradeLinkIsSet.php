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
            flash()->error(__('messages.middleware-tradelink-missing'));

            return redirect()->route('users.settings');
        }

        return $next($request);
    }
}
