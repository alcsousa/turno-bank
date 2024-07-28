<?php

namespace App\Http\Controllers;

use App\Actions\Check\DepositCheckAction;
use App\Http\Requests\Check\StoreCheckRequest;
use App\Http\Resources\Check\CheckCollection;
use App\Http\Resources\Check\CheckResource;
use App\Models\Check;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckController extends Controller
{
    public function index(Request $request)
    {
        $checks = Check::with('status')
            ->where('user_id', '=', $request->user()->id)
            ->orderBy('id', 'desc')
            ->paginate();

        return response()->json(new CheckCollection($checks), Response::HTTP_OK);
    }

    public function store(StoreCheckRequest $request, DepositCheckAction $action): JsonResponse
    {
        $check = $action->deposit($request->user(), $request->validated());

        return response()->json(new CheckResource($check), Response::HTTP_CREATED);
    }
}
