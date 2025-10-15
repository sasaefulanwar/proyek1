<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $adminId = $request->session()->get('admin_id');
        $role = $request->session()->get('admin_role');

        if (! $adminId || ! in_array($role, ['admin', 'admin_apotek'])) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
