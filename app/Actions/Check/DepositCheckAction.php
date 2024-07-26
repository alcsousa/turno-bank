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
            'user_id' => $user->id,
            'amount' => $checkData['amount'],
            'description' => $checkData['description'],
            'image_path' => $imagePath
        ]);
    }
}
