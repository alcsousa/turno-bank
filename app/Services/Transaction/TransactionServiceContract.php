<?php

namespace App\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;

interface TransactionServiceContract
{
    public function createAccountTransaction(Account $account, array $transactionData): Transaction;
}
