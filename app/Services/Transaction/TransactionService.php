<?php

namespace App\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function retrievePaginatedAccountTransactions(Account $account): LengthAwarePaginator
    {
        return Transaction::where('account_id', $account->id)
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
