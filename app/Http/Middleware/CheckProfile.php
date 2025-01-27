<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfile
{
    public function handle(Request $request, Closure $next, ...$profiles)
    {
        $user = $request->user();

        if ($user && in_array($user->profile, $profiles)) {
            return $next($request);
        }

        return redirect('/');
    }
}
