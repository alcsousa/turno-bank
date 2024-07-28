<?php

namespace Database\Seeders;

use App\Models\CheckStatus;
use Illuminate\Database\Seeder;

class CheckStatusesTableSeeder extends Seeder
{
    public function run(): void
    {
        CheckStatus::updateOrCreate(
            ['id' => CheckStatus::PENDING],
            ['name' => CheckStatus::$labels[CheckStatus::PENDING]]
        );

        CheckStatus::updateOrCreate(
            ['id' => CheckStatus::ACCEPTED],
            ['name' => CheckStatus::$labels[CheckStatus::ACCEPTED]]
        );

        CheckStatus::updateOrCreate(
            ['id' => CheckStatus::REJECTED],
            ['name' => CheckStatus::$labels[CheckStatus::REJECTED]]
        );
    }
}
