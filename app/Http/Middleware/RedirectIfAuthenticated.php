<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Cek apakah pengguna sudah login dan sudah berada di halaman yang sesuai
                if ($guard === 'pengguna' && !$request->is('pengguna/dashboard')) {
                    return redirect()->route('pengguna.dashboard');
                } elseif ($guard === 'pengelola' && !$request->is('pengelola/dashboard')) {
                    return redirect()->route('pengelola.dashboard');
                }

                // Pengguna sudah login, maka redirect ke halaman dashboard sesuai dengan guard
                if ($guard === 'pengguna') {
                    return redirect()->route('pengguna.dashboard');
                } elseif ($guard === 'pengelola') {
                    return redirect()->route('pengelola.dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
