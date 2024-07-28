<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HardcodedAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('USER_ADMIN_EMAIL')],
            [
                'is_admin' => true,
                'name' => 'Admin',
                'password' => Hash::make(env('USER_ADMIN_PASSWORD'))
            ]
        );
    }
}
