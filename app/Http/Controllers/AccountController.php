<?php

namespace App\Http\Controllers;

use App\Http\Resources\Account\AccountResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function getUserAccount(Request $request): JsonResponse
    {
        return response()->json(new AccountResource($request->user()->account), Response::HTTP_OK);
    }
}
