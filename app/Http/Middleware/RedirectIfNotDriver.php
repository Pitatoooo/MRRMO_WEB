<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotDriver
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('driver')->check()) {
            return redirect()->route('driver.login.form'); // 👈 correct redirect
        }

        return $next($request);
    }
}
