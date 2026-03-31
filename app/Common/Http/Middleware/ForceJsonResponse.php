<?php

namespace App\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Force the request to accept JSON responses for API routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force Accept header to application/json for all API requests
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
