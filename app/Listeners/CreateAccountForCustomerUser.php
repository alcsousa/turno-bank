<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\CreateAccountForCustomerUserJob;

class CreateAccountForCustomerUser
{
    public function handle(UserRegistered $event): void
    {
        CreateAccountForCustomerUserJob::dispatch($event->user);
    }
}
