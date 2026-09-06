<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuruLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('guru_kelas_id')) {
            return redirect()->route('guru.login')->withErrors(['kelas_id' => 'Silakan login sebagai guru terlebih dahulu.']);
        }

        return $next($request);
    }
}
