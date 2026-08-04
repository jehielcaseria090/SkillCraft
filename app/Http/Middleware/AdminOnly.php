<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (session('admin_role') !== 'admin') {
            abort(403, 'Only administrators can access this page.');
        }
        return $next($request);
    }
}
