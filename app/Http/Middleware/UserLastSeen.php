<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cacheKey = 'user_last_seen_' . $userId;

            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, now()->addMinutes(3));
                DB::table('users')
                    ->where('id', $userId)
                    ->update(['last_seen' => now()]);
            }
        }

        return $next($request);
    }
}