<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMinutesGeneratorAuthenticated
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ((bool) $request->session()->get('minutes_generator_authenticated', false)) {
            return $next($request);
        }

        return redirect()->route('minutes-generator.login');
    }
}
