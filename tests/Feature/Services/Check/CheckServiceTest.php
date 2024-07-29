<?php

namespace Tests\Feature\Services\Check;

use App\Exceptions\InvalidCheckStatusTransitionException;
use App\Models\Account;
use App\Models\Check;
use App\Models\CheckStatus;
use App\Models\User;
use App\Services\Check\CheckServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckServiceTest extends TestCase
{
    protected CheckServiceContract $checkService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checkService = $this->app->make(CheckServiceContract::class);
    }

    public function test_it_retrieves_paginated_checks_by_user_id()
    {
        $count = 5;
        $user = User::factory()->customer()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $checks = Check::factory($count)->pending()->create(['account_id' => $account->id]);
        // creates checks from another users to make sure it's filtered correctly
        Check::factory(3)->create();

        $paginatedChecks = $this->checkService->retrievePaginatedChecksByUserIdAndStatusName($user->id, 'pending');

        $this->assertFactoryChecksAgainstPaginatedChecks($checks, $paginatedChecks);
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedChecks);
        $this->assertSame($count, $paginatedChecks->total());
    }

    private function assertFactoryChecksAgainstPaginatedChecks(
        Collection $factoryChecks,
        LengthAwarePaginator $paginatedChecks
    ): void
    {
        $paginatedChecksData = collect($paginatedChecks->toArray()['data']);

        foreach ($factoryChecks as $check) {
            $filtered = $paginatedChecksData->where('id', '=', $check->id)->first();
            $this->assertEquals($check->id, $filtered['id']);
            $this->assertEquals($check->amount, $filtered['amount']);
            $this->assertEquals($check->description, $filtered['description']);
            $this->assertEquals($check->image_path, $filtered['image_path']);
            $this->assertEquals($check->status->id, $filtered['status']['id']);
            $this->assertEquals($check->status->name, $filtered['status']['name']);
            $this->assertEquals($check->account->id, $filtered['account']['id']);
            $this->assertEquals($check->account->balance, $filtered['account']['balance']);
            $this->assertEquals($check->account->user->id, $filtered['account']['user']['id']);
            $this->assertEquals($check->account->user->name, $filtered['account']['user']['name']);
            $this->assertEquals($check->account->user->email, $filtered['account']['user']['email']);
        }
    }

    public function test_it_retrieves_paginated_checks_by_status_name()
    {
        $count = 5;
        $pendingChecks = Check::factory($count)->pending()->create();
        // creates checks with another status to make sure it's filtering correctly
        Check::factory($count)->rejected()->create();

        $paginatedChecks = $this->checkService->retrievePaginatedChecksByStatusName('pending');

        $this->assertFactoryChecksAgainstPaginatedChecks($pendingChecks, $paginatedChecks);
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedChecks);
        $this->assertSame($count, $paginatedChecks->total());
    }

    public function test_it_stores_the_user_check_data_and_image()
    {
        Storage::fake();
        $fakeFile = UploadedFile::fake()->image('test.jpg');
        $user = User::factory()->customer()->create();
        Account::factory()->create(['user_id' => $user->id]);
        $checkData = [
            'amount' => '100.00',
            'description' => 'Gift',
            'image' => $fakeFile
        ];

        $check = $this->checkService->storeUserCheck($user, $checkData);

        $this->assertEquals($user->id, $check->account->user->id);
        $this->assertEquals($checkData['amount'], $check->amount);
        $this->assertEquals($checkData['description'], $check->description);
        $this->assertEquals('checks/' . $fakeFile->hashName(), $check->image_path);
        Storage::disk()->assertExists('checks/' . $fakeFile->hashName());
    }

    public function test_transaction_is_created_and_account_balance_updated_when_check_is_accepted()
    {
        $initialBalance = 1000;
        $checkAmount = 500;
        $account = Account::factory()->create(['balance' => $initialBalance]);
        $check = Check::factory()->pending()->create(['account_id' => $account->id, 'amount' => $checkAmount]);

        $this->checkService->evaluateCheck($check, true);

        $this->assertEquals(($initialBalance + $checkAmount), $account->fresh()->balance);
        $this->assertEquals(CheckStatus::ACCEPTED, $check->fresh()->status->id);
        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'amount' => $check->amount,
            'description' => $check->description
        ]);
    }

    public function test_transaction_is_not_created_and_account_balance_not_updated_when_check_is_rejected()
    {
        $initialBalance = 1000;
        $checkAmount = 500;
        $account = Account::factory()->create(['balance' => $initialBalance]);
        $check = Check::factory()->pending()->create(['account_id' => $account->id, 'amount' => $checkAmount]);

        $this->checkService->evaluateCheck($check, false);

        $this->assertEquals($initialBalance, $account->fresh()->balance);
        $this->assertEquals(CheckStatus::REJECTED, $check->fresh()->status->id);
        $this->assertDatabaseMissing('transactions', [
            'account_id' => $account->id,
            'amount' => $check->amount,
            'description' => $check->description
        ]);
    }

    public function test_it_throws_exception_for_invalid_status_changes()
    {
        $account = Account::factory()->create();
        $check = Check::factory()->accepted()->create(['account_id' => $account->id]);

        $this->expectException(InvalidCheckStatusTransitionException::class);

        $this->checkService->evaluateCheck($check, true);
    }
}
