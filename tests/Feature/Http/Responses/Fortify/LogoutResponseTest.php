<?php

namespace Tests\Feature\Http\Responses\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutResponseTest extends TestCase
{
    public function test_it_expires_cookies_on_logout()
    {
        $user = User::factory([
            'password' => Hash::make('password'),
        ])->create();
        $this->actingAs($user);

        $response = $this->json('POST', '/logout');

        $response->assertNoContent();
        $response->assertCookieExpired('laravel_session');
        $response->assertCookieExpired('XSRF-TOKEN');
    }
}
