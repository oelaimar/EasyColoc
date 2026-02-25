<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSingleColocation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->current_colocation_id && $request->is('colocations/create')) {
            return redirect()->route('dashboard')->with('error', 'You are already in a colocation.');
        }
        return $next($request);
    }
}
