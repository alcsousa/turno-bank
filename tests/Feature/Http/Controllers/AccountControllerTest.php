<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    public function test_it_retrieves_logged_user_account_data_and_http_ok()
    {
        $account = Account::factory()->create(['balance' => '1000.99']);

        $response = $this->be($account->user)->json('GET', '/api/accounts/user');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'id' => $account->id,
            'balance' => $account->balance
        ]);
    }

    public function test_it_denies_access_to_admin_users()
    {
        $user = User::factory()->admin()->create();

        $response = $this->be($user)->json('GET', '/api/accounts/user');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
