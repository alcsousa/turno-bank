<?php

namespace App\Http\Controllers;

use App\Actions\Check\DepositCheckAction;
use App\Http\Requests\Check\StoreCheckRequest;
use App\Http\Resources\CheckResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CheckController extends Controller
{
    public function store(StoreCheckRequest $request, DepositCheckAction $action): JsonResponse
    {
        $check = $action->deposit($request->user(), $request->validated());

        return response()->json(new CheckResource($check), Response::HTTP_CREATED);
    }
}
