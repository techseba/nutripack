<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        // cache key per user for small TTL
        $cacheKey = "user:{$user->id}:active_subscription";
        $hasActive = Cache::remember($cacheKey, 30, function () use ($user) {
            return \App\Models\Subscriber::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('expires_date', '>=', now())
                ->exists();
        });

        // If user has active subscription and is requesting homepage route, redirect
        if ($hasActive && $request->routeIs('home')) {
            return redirect()->route('subscription');
        }

        return $next($request);
    }
}
