<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamsImportToken
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('services.minutes_generator.teams_import_enabled')) {
            return response()->json([
                'message' => 'Teams transcript import is disabled.',
            ], 503);
        }

        $expectedToken = config('services.minutes_generator.teams_import_token');
        $providedToken = $request->bearerToken();

        if (blank($expectedToken) || blank($providedToken) || ! hash_equals((string) $expectedToken, $providedToken)) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }
}
