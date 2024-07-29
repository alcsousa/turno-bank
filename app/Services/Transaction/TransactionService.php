<?php

namespace App\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;

class TransactionService implements TransactionServiceContract
{
    public function createAccountTransaction(
        Account $account,
        array $transactionData
    ): Transaction {
        return Transaction::create([
            'account_id' => $account->id,
            'amount' => $transactionData['amount'],
            'description' => $transactionData['description']
        ]);
    }
}
