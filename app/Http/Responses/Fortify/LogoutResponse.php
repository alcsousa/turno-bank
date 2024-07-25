<?php

namespace App\Http\Responses\Fortify;


use Illuminate\Support\Facades\Cookie;
use Laravel\Fortify\Fortify;

class LogoutResponse implements \Laravel\Fortify\Contracts\LogoutResponse
{
    public function toResponse($request)
    {
        Cookie::expire('laravel_session');
        Cookie::expire('XSRF-TOKEN');

        return $request->wantsJson()
            ? response()->json([], 204)
            : redirect(Fortify::redirects('logout', '/'));
    }
}
