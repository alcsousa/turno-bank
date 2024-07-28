<?php

namespace Tests\Feature\Jobs;

use App\Exceptions\ShouldNotCreateAccountForAdminUserException;
use App\Jobs\CreateAccountForCustomerUserJob;
use App\Models\User;
use Tests\TestCase;

class CreateAccountForCustomerUserJobTest extends TestCase
{
    public function test_job_creates_account_for_account_user()
    {
        $user = User::factory()->customer()->create();

        CreateAccountForCustomerUserJob::dispatch($user);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'balance' => 0
        ]);
    }

    public function test_job_doesnt_create_account_for_admin_user()
    {
        $user = User::factory()->admin()->create();
        $this->expectException(ShouldNotCreateAccountForAdminUserException::class);

        CreateAccountForCustomerUserJob::dispatch($user);

        $this->assertDatabaseMissing('accounts', [
            'user_id' => $user->id
        ]);
    }
}
