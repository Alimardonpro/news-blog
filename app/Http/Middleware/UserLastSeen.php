<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        // Agar foydalanuvchi tizimga kirgan bo'lsa
        if (Auth::check()) {
            // Uning oxirgi marta ko'rilgan vaqtini hozirgi vaqtga yangilaymiz
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['last_seen' => now()]);
        }

        return $next($request);
    }
}