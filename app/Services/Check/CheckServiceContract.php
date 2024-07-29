<?php

namespace App\Services\Check;

use App\Models\Check;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CheckServiceContract
{
    public function retrievePaginatedChecksByUserIdAndStatusName(int $userId, string $status): LengthAwarePaginator;
    public function retrievePaginatedChecksByStatusName(string $status): LengthAwarePaginator;
    public function storeUserCheck(User $user, array $checkData): Check;
    public function evaluateCheck(Check $check, bool $isAccepted): void;
}
