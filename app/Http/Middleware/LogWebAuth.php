<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogWebAuth
{
    /**
     * Log auth/session info for web requests to debug sidebar redirects.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        Log::info('web_auth_debug', [
            'method' => $request->method(),
            'path' => $request->path(),
            'full_url' => $request->fullUrl(),
            'session_id' => $request->session()->getId(),
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
