<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MinutesGeneratorAuthController extends Controller
{
    public function showLogin(Request $request): RedirectResponse|View
    {
        if ((bool) $request->session()->get('minutes_generator_authenticated', false)) {
            return redirect()->route('minutes-generator.index');
        }

        return view('minutes-generator.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = config('services.minutes_generator.auth_username');
        $password = config('services.minutes_generator.auth_password');

        if (blank($username) || blank($password)) {
            abort(500, 'Minutes Generator authentication is not configured.');
        }

        if (
            hash_equals((string) $username, $credentials['username']) &&
            hash_equals((string) $password, $credentials['password'])
        ) {
            $request->session()->regenerate();
            $request->session()->put('minutes_generator_authenticated', true);

            return redirect()->intended(route('minutes-generator.index'));
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Las credenciales no son válidas.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('minutes_generator_authenticated');
        $request->session()->regenerateToken();

        return redirect()->route('minutes-generator.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
