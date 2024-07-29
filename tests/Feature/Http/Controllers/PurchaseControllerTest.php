<?php

namespace Tests\Feature\Http\Controllers;

use App\Exceptions\AccountDoesNotHaveEnoughFundsException;
use App\Models\Account;
use Illuminate\Http\Response;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    public function test_it_creates_purchase_and_returns_transaction_with_http_created()
    {
        $account = Account::factory()->create(['balance' => 1000]);
        $payload = [
            'amount' => 100,
            'description' => 'Phone bill'
        ];

        $response = $this->be($account->user)->json('POST', '/api/purchases', $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('account_id', $account->id);
        $response->assertJsonFragment([
            'account_id' => $account->id,
            'amount' => number_format($payload['amount'] * -1, 2),
            'description' => $payload['description']
        ]);
    }

    public function test_it_gets_expected_exception_when_tries_to_purchase_with_no_balance()
    {
        $account = Account::factory()->create(['balance' => 0]);
        $payload = [
            'amount' => 100,
            'description' => 'Phone bill'
        ];

        $response = $this->be($account->user)->json('POST', '/api/purchases', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', (new AccountDoesNotHaveEnoughFundsException())->getMessage());
    }
}
