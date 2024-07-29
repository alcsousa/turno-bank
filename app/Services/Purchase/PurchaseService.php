<?php

namespace App\Services\Purchase;

use App\Exceptions\AccountDoesNotHaveEnoughFundsException;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Account\AccountServiceContract;
use App\Services\Transaction\TransactionServiceContract;
use Illuminate\Support\Facades\DB;

readonly class PurchaseService implements PurchaseServiceContract
{
    public function __construct(
        private TransactionServiceContract $transactionService,
        private AccountServiceContract $accountService
    ) {
    }

    /**
     * @throws AccountDoesNotHaveEnoughFundsException
     */
    public function createPurchaseTransactionForUser(User $user, array $purchaseDetails): Transaction
    {
        $this->ensureAccountHasEnoughFundsForPurchaseTransaction($user->account, $purchaseDetails['amount']);

        return DB::transaction(function () use ($user, $purchaseDetails) {
            $amountAsExpense = $purchaseDetails['amount'] * -1;

            $transaction = $this->transactionService->createAccountTransaction($user->account, [
                'amount' => $amountAsExpense,
                'description' => $purchaseDetails['description']
            ]);

            $this->accountService->addAmountToCurrentBalance($user->account, $amountAsExpense);

            return $transaction;
        });
    }

    /**
     * @throws AccountDoesNotHaveEnoughFundsException
     */
    public function ensureAccountHasEnoughFundsForPurchaseTransaction(Account $account, $purchaseAmount): void
    {
        if (($account->balance - $purchaseAmount) < 0) {
            throw new AccountDoesNotHaveEnoughFundsException();
        }
    }
}
