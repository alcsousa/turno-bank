<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Account\AccountServiceContract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateAccountForCustomerUserJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user
    ) {
    }

    public function handle(AccountServiceContract $service): void
    {
        $service->createNewAccount($this->user);
    }
}
