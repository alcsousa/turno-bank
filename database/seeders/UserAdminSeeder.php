<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => env('USER_ADMIN_EMAIL', 'admin@test.com')
        ], [
            'name' => 'Admin',
            'password' => Hash::make(env('USER_ADMIN_PASSWORD')),
            'is_admin' => true
        ]);
    }
}
