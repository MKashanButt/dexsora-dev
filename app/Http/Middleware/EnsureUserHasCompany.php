<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasCompany
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->company_id) {
            return redirect()->route('company.setup');
        }

        return $next($request);
    }
}
