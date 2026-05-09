<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil role user yang login
        $userRole = Auth::user()->role;

        // Cek apakah role user termasuk dalam roles yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Redirect berdasarkan role
            if ($userRole === 'admin') {
                return redirect()->route('admin.index');
            } elseif ($userRole === 'user') {
                return redirect()->route('home');
            }
            
            return redirect('/');
        }

        return $next($request);
    }
}