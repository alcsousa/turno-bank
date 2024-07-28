<?php

namespace App\Jobs;

use App\Exceptions\ShouldNotCreateAccountForAdminUserException;
use App\Models\Account;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateAccountForCustomerUserJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user
    ) {
    }

    /**
     * @throws ShouldNotCreateAccountForAdminUserException
     */
    public function handle(): void
    {
        if ($this->user->is_admin) {
            throw new ShouldNotCreateAccountForAdminUserException();
        }

        $account = new Account();
        $account->user_id = $this->user->id;
        $account->balance = 0;
        $account->save();
    }
}
