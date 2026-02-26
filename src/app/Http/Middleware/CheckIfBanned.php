<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->is_banned){
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Your account has been suspended by admin.');
        }
        return $next($request);
    }
}
