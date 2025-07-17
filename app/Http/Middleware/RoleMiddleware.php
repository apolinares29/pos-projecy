<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!session('authenticated') || session('role') !== $role) {
            return redirect('/dashboard/' . session('role'));
        }
        return $next($request);
    }
} 