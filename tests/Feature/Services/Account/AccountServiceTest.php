<?php

namespace Tests\Feature\Services\Account;

use App\Exceptions\ShouldNotCreateAccountForAdminUserException;
use App\Models\User;
use App\Services\Account\AccountServiceContract;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    protected AccountServiceContract $accountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountService = $this->app->make(AccountServiceContract::class);
    }

    public function test_it_creates_new_account_with_zero_balance()
    {
        $user = User::factory()->customer()->create();

        $account = $this->accountService->createNewAccount($user);

        $this->assertEquals($user->id, $account->user->id);
        $this->assertEquals(0, $account->balance);
    }

    public function test_it_throws_exception_if_user_is_admin()
    {
        $user = User::factory()->admin()->create();
        $this->expectException(ShouldNotCreateAccountForAdminUserException::class);

        $this->accountService->createNewAccount($user);

        $this->assertDatabaseMissing('accounts', [
            'user_id' => $user->id
        ]);
    }
}
