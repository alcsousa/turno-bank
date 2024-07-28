<?php

namespace App\Actions\Check;

use App\Models\Check;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DepositCheckAction
{
    public function deposit(User $user, array $checkData): Check
    {
        $imagePath = Storage::putFile('/checks', $checkData['image']);

        return Check::create([
            'account_id' => $user->account->id,
            'amount' => $checkData['amount'],
            'description' => $checkData['description'],
            'image_path' => $imagePath
        ])->load(['status', 'account.user'])->refresh();
    }
}
