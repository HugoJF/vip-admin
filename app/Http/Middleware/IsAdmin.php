<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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

        $allowedId = ['76561198026414330', '76561198175503989', '76561198033283983'];

        if (!in_array(Auth::user()->steamid, $allowedId)) {
            flash()->error('You are not allowed to see this page!');

            return redirect('/');
        }
        return $next($request);
    }
}
