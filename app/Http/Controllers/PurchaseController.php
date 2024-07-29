<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountDoesNotHaveEnoughFundsException;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Services\Purchase\PurchaseServiceContract;
use Illuminate\Http\Response;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseServiceContract $service
    ) {
    }

    public function store(StorePurchaseRequest $request)
    {
        try {
            $transaction = $this->service->createPurchaseTransactionForUser($request->user(), $request->validated());

            return response()->json(new TransactionResource($transaction), Response::HTTP_CREATED);
        } catch (AccountDoesNotHaveEnoughFundsException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
