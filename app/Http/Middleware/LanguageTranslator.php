<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class LanguageTranslator
{
    public function handle(Request $request, Closure $next)
    {
        if(session()->has('locale')) {
            \App::setLocale(session()->get('locale'));
        }
    	$response = $next($request);

        return $response;
    }
}
