<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $languageApi = $request->server('HTTP_ACCEPT_LANGUAGE');

        Log::error('$languageApi');
        Log::error($languageApi);

        $language = config('constants.'.$languageApi);
        app()->setLocale($language);
        return $next($request);
    }
}
