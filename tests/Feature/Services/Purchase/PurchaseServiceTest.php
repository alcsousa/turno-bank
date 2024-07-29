<?php

namespace Tests\Feature\Services\Purchase;

use App\Exceptions\AccountDoesNotHaveEnoughFundsException;
use App\Models\Account;
use App\Services\Purchase\PurchaseServiceContract;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PurchaseServiceTest extends TestCase
{
    protected PurchaseServiceContract $purchaseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->purchaseService = app(PurchaseServiceContract::class);
    }

    public function test_it_throws_exception_when_account_has_not_enough_funds()
    {
        $account = Account::factory()->create(['balance' => 0]);

        $this->expectException(AccountDoesNotHaveEnoughFundsException::class);

        $this->purchaseService->createPurchaseTransactionForUser($account->user, [
            'amount' => 100,
            'description' => 'Gift'
        ]);
    }

    #[DataProvider('initialBalanceAndPurchaseAmountProvider')]
    public function test_it_creates_transaction_and_updates_account_balance(
        $initialBalance,
        $purchaseAmount,
        $expectedBalance
    ) {
        $account = Account::factory()->create(['balance' => $initialBalance]);

        $this->purchaseService->createPurchaseTransactionForUser($account->user, [
            'amount' => $purchaseAmount,
            'description' => 'Gift'
        ]);

        $this->assertEquals($expectedBalance, $account->fresh()->balance);

        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'amount' => $purchaseAmount * -1
        ]);
    }

    public static function initialBalanceAndPurchaseAmountProvider(): array
    {
        return [
            [100, 100, 0],
            [1000, 500, 500],
            [2000, 1800, 200]
        ];
    }
}
