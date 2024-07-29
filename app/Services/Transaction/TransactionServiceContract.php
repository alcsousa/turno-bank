<?php

namespace App\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionServiceContract
{
    public function createAccountTransaction(Account $account, array $transactionData): Transaction;
    public function retrievePaginatedAccountTransactions(Account $account): LengthAwarePaginator;
}
