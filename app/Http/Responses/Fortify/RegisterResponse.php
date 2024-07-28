<?php

namespace App\Http\Responses\Fortify;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Fortify\Fortify;

class RegisterResponse implements \Laravel\Fortify\Contracts\RegisterResponse
{
    public function toResponse($request)
    {
        $user = $request->user();

        return $request->wantsJson()
            ? new JsonResponse(new UserResource($user), Response::HTTP_CREATED)
            : redirect()->intended(Fortify::redirects('register'));
    }
}
