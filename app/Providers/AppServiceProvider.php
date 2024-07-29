<?php

namespace App\Providers;

use App\Services\Account\AccountService;
use App\Services\Account\AccountServiceContract;
use App\Services\Check\CheckService;
use App\Services\Check\CheckServiceContract;
use App\Services\Purchase\PurchaseService;
use App\Services\Purchase\PurchaseServiceContract;
use App\Services\Transaction\TransactionService;
use App\Services\Transaction\TransactionServiceContract;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CheckServiceContract::class, CheckService::class);
        $this->app->bind(AccountServiceContract::class, AccountService::class);
        $this->app->bind(TransactionServiceContract::class, TransactionService::class);
        $this->app->bind(PurchaseServiceContract::class, PurchaseService::class);
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
