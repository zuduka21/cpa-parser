<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ExportAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->checkRole('admin') || Auth::user()['allow_export'] == true) {
            return $next($request);
        }
        return redirect('login');
    }
}
