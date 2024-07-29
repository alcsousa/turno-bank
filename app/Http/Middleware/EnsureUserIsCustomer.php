<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->isCustomer()) {
            return $next($request);
        }

        throw new HttpResponseException(
            response()->json(['message' => 'Forbidden: Customers only'], Response::HTTP_FORBIDDEN)
        );
    }
}
