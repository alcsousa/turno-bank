<?php

namespace Tests\Feature\Http\Responses\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginResponseTest extends TestCase
{
    public function test_it_returns_user_data_in_response_when_user_logs_in()
    {
        $user = User::factory([
            'password' => Hash::make('password'),
        ])->create();

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk();
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
