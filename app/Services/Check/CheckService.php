<?php

namespace App\Services\Check;

use App\Exceptions\InvalidCheckStatusTransitionException;
use App\Models\Check;
use App\Models\User;
use App\Services\Transaction\TransactionServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckService implements CheckServiceContract
{
    public function retrievePaginatedChecksByUserId(int $userId): LengthAwarePaginator
    {
        return Check::with(['status', 'account.user'])
            ->whereHas('account', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function retrievePaginatedChecksByStatusName(string $status): LengthAwarePaginator
    {
        return Check::with(['status', 'account.user'])
            ->whereHas('status', function ($query) use ($status) {
                $query->where('name', ucfirst($status));
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function storeUserCheck(User $user, array $checkData): Check
    {
        $imagePath = Storage::putFile('/checks', $checkData['image']);

        return Check::create([
            'account_id' => $user->account->id,
            'amount' => $checkData['amount'],
            'description' => $checkData['description'],
            'image_path' => $imagePath
        ])->load(['status', 'account.user'])->refresh();
    }

    /**
     * @throws InvalidCheckStatusTransitionException
     */
    public function evaluateCheck(Check $check, bool $isAccepted): void
    {
        if ($isAccepted) {
            DB::transaction(function () use ($check) {
                app(TransactionServiceContract::class)
                    ->createAccountTransaction($check->account, [
                        'amount' => $check->amount,
                        'description' => $check->description
                    ]);

                $check->account->balance = $check->account->balance + $check->amount;
                $check->account->save();

                $check->markAsAccepted();
            });
        } else {
            $check->markAsRejected();
        }
    }
}
