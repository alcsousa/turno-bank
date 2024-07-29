<?php

namespace App\Services\Account;

use App\Exceptions\ShouldNotCreateAccountForAdminUserException;
use App\Models\Account;
use App\Models\User;

class AccountService implements AccountServiceContract
{
    public function createNewAccount(User $user): Account
    {
        if ($user->isAdmin()) {
            throw new ShouldNotCreateAccountForAdminUserException();
        }

        $account = new Account();
        $account->user_id = $user->id;
        $account->balance = 0;
        $account->save();

        return $account;
    }

    public function addAmountToCurrentBalance(Account $account, float $amount): void
    {
        $account->balance += $amount;
        $account->save();
    }
}
