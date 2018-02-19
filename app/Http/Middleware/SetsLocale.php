<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetsLocale
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
        if (Auth::check()) {
            $lang = Auth::user()->lang;
            $supported_lang = ['en', 'pt_BR'];

            if (in_array($lang, $supported_lang)) {
                App::setLocale($lang);
            }
        }

        return $next($request);
    }
}
