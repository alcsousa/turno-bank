<?php

namespace App\Http\Controllers;

use App\Http\Requests\Check\StoreCheckRequest;
use App\Http\Resources\Check\CheckCollection;
use App\Http\Resources\Check\CheckResource;
use App\Services\Check\CheckServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckController extends Controller
{
    public function __construct(
        private readonly CheckServiceContract $service
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $paginatedChecks = $this->service->retrievePaginatedChecksByUserId($request->user()->id);

        return response()->json(new CheckCollection($paginatedChecks), Response::HTTP_OK);
    }

    public function store(StoreCheckRequest $request): JsonResponse
    {
        $check = $this->service->storeUserCheck($request->user(), $request->validated());

        return response()->json(new CheckResource($check), Response::HTTP_CREATED);
    }
}
