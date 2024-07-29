<?php

namespace App\Http\Controllers;

use App\Http\Resources\Transaction\TransactionCollection;
use App\Services\Transaction\TransactionServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionServiceContract $service
    ) {
    }

    public function index(Request $request)
    {
        $transactions = $this->service->retrievePaginatedAccountTransactions($request->user()->account);

        return response()->json(new TransactionCollection($transactions), Response::HTTP_OK);
    }
}
