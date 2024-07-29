<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexCheckByStatusRequest;
use App\Http\Resources\Check\CheckCollection;
use App\Http\Resources\Check\CheckResource;
use App\Models\Check;
use App\Services\Check\CheckServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CheckControlController extends Controller
{
    public function __construct(
        private readonly CheckServiceContract $service
    ) {
    }

    public function indexByStatus(IndexCheckByStatusRequest $request): JsonResponse
    {
        $checks = $this->service->retrievePaginatedChecksByStatusName($request->get('status'));

        return response()->json(new CheckCollection($checks), Response::HTTP_OK);
    }

    public function show(Check $check): JsonResponse
    {
        return response()->json(new CheckResource($check), Response::HTTP_OK);
    }
}
