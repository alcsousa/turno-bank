<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    public function test_transactions_can_be_retrieved_using_pagination()
    {
        $count = 20;
        $account = Account::factory()->create();
        Transaction::factory()->count($count)->create(['account_id' => $account->id]);

        $response = $this->be($account->user)->json('GET', '/api/transactions?page=2');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'account_id',
                    'amount',
                    'description',
                    'created_at'
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'last_page',
                'from',
                'to',
                'per_page',
                'total'
            ]
        ]);
    }
}
