<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPeran
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $peran): Response
    {
        if (! $request->user() || $request->user()->peran !== $peran) {
            return redirect('/dashboard')->with('status', 'Anda tidak memiliki izin akses.');
        }

        return $next($request);
    }


}
