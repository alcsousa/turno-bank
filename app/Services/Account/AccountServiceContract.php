<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Models\User;

interface AccountServiceContract
{
    public function createNewAccount(User $user): Account;
    public function addAmountToCurrentBalance(Account $account, float $amount): void;
}
