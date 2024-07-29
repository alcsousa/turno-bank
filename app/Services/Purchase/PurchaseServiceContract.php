<?php

namespace App\Services\Purchase;

use App\Models\Transaction;
use App\Models\User;

interface PurchaseServiceContract
{
    public function createPurchaseTransactionForUser(User $user, array $purchaseDetails): Transaction;
}
