<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexCheckByStatusRequest;
use App\Http\Resources\Check\CheckCollection;
use App\Http\Resources\Check\CheckResource;
use App\Models\Check;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CheckControlController extends Controller
{
    public function indexByStatus(IndexCheckByStatusRequest $request): JsonResponse
    {
        $status = $request->get('status');
        $checks = Check::with('status')->whereHas('status', function ($query) use ($status) {
            $query->where('name', ucfirst($status));
        })->orderBy('id', 'desc')->paginate();

        return response()->json(new CheckCollection($checks), Response::HTTP_OK);
    }

    public function show(Check $check): JsonResponse
    {
        return response()->json(new CheckResource($check), Response::HTTP_OK);
    }
}
