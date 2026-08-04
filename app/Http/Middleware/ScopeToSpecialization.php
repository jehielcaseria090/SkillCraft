<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ScopeToSpecialization
{
    public function handle(Request $request, Closure $next)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($adminId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Attach to the request so controllers can read it without
        // re-querying the DB on every action.
        $request->attributes->set('cms_user', $user);
        $request->attributes->set('cms_scoped_strand', $user->scopedStrandName());

        return $next($request);
    }
}
