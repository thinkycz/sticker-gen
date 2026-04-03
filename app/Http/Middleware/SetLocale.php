<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Always set locale from session or detect from browser
        $locale = Session::get('locale', $request->getPreferredLanguage(['en', 'cs', 'vi']));
        
        // Update session if locale was submitted via form
        if ($request->isMethod('POST') && $request->has('locale')) {
            $locale = $request->input('locale');
            Session::put('locale', $locale);
        }
        
        // Set the locale for this request
        App::setLocale($locale);
        
        return $next($request);
    }
}
