<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is an admin
        if (auth()->check() && auth()->user()->is_admin) { // Adjust 'is_admin' to your actual column name
            return $next($request);
        }

        // Check if logged in but NOT an admin, throw a 403 Forbidden
        if (auth()->check()) {
            abort(403, 'Unauthorized action.');
        }

        // If not an admin, redirect them back to the login page
        return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
    }
}
