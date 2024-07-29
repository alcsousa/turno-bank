<?php

namespace Tests\Feature\Services\Transaction;

use App\Models\Account;
use App\Services\Transaction\TransactionService;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    public function test_it_can_create_an_account_transaction()
    {
        $account = Account::factory()->create();
        $transactionData = [
            'amount' => 1000,
            'description' => 'Bills'
        ];

        $transaction = app(TransactionService::class)
            ->createAccountTransaction($account, $transactionData);

        $this->assertEquals($account->id, $transaction->account_id);
        $this->assertEquals($transactionData['amount'], $transaction->amount);
        $this->assertEquals($transactionData['description'], $transaction->description);
    }
}
