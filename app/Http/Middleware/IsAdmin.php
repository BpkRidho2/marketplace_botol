<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah yang mengakses sudah login sebagai admin
        // menggunakan guard 'admin' (bukan guard 'web' yang untuk user biasa)
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}