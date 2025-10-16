<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Access denied. Admin role required.');
        }
        
        if ($role === 'learner' && !$user->isLearner()) {
            abort(403, 'Access denied. Learner role required.');
        }

        return $next($request);
    }
}
