<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->is_admin) {
            return $next($request);
        }

        throw new HttpResponseException(
            response()->json(['message' => 'Forbidden: User lacks privilege'], Response::HTTP_FORBIDDEN)
        );
    }
}
