<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMinutesGeneratorBasicAuth
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = config('services.minutes_generator.auth_username');
        $password = config('services.minutes_generator.auth_password');

        if (blank($username) || blank($password)) {
            abort(500, 'Minutes Generator authentication is not configured.');
        }

        if (
            hash_equals((string) $username, (string) $request->getUser()) &&
            hash_equals((string) $password, (string) $request->getPassword())
        ) {
            return $next($request);
        }

        return response('Authentication required.', 401, [
            'WWW-Authenticate' => 'Basic realm="Minutes Generator"',
        ]);
    }
}
