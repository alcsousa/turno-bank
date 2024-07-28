<?php

namespace App\Http\Responses\Fortify;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Fortify\Fortify;

class LoginResponse implements \Laravel\Fortify\Contracts\LoginResponse
{
    public function toResponse($request)
    {
        $user = $request->user();

        return $request->wantsJson()
            ? new JsonResponse(new UserResource($user), Response::HTTP_OK)
            : redirect()->intended(Fortify::redirects('login'));
    }
}
