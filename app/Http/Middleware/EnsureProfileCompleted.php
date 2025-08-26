<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for admin users
        if (auth()->user() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Check if profile is completed via session flag or database
        $profileCompleted = session('viewer_profile_completed', false);
        
        if (!$profileCompleted && auth()->user()) {
            $profileCompleted = auth()->user()->hasCompletedProfile();
            if ($profileCompleted) {
                session(['viewer_profile_completed' => true]);
            }
        }

        // If profile not completed and trying to access protected routes, redirect to profile
        if (!$profileCompleted && !$request->routeIs('profile.complete')) {
            return redirect()->route('profile.complete');
        }

        return $next($request);
    }
}
