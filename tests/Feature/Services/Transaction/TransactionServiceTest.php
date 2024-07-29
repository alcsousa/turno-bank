<?php

namespace Tests\Feature\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\Transaction\TransactionService;
use App\Services\Transaction\TransactionServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    protected TransactionServiceContract $transactionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionService = app(TransactionService::class);
    }

    public function test_it_can_create_an_account_transaction()
    {
        $account = Account::factory()->create();
        $transactionData = [
            'amount' => 1000,
            'description' => 'Bills'
        ];

        $transaction = $this->transactionService->createAccountTransaction($account, $transactionData);

        $this->assertEquals($account->id, $transaction->account_id);
        $this->assertEquals($transactionData['amount'], $transaction->amount);
        $this->assertEquals($transactionData['description'], $transaction->description);
    }

    public function test_it_retrieves_paginated_account_transactions()
    {
        $count = 5;
        $account = Account::factory()->create();
        $factoryTransactions = Transaction::factory($count)->create(['account_id' => $account]);

        $paginatedTransactions = $this->transactionService->retrievePaginatedAccountTransactions($account);

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedTransactions);
        $this->assertSame($count, $paginatedTransactions->total());

        $paginatedTransactionsData = collect($paginatedTransactions->toArray()['data']);

        foreach ($factoryTransactions as $transaction) {
            $filtered = $paginatedTransactionsData->where('id', '=', $transaction->id)->first();
            $this->assertEquals($transaction->id, $filtered['id']);
            $this->assertEquals($transaction->account_id, $filtered['account_id']);
            $this->assertEquals($transaction->amount, $filtered['amount']);
            $this->assertEquals($transaction->description, $filtered['description']);
        }
    }
}
