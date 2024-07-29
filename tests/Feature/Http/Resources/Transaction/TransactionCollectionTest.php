<?php

namespace Tests\Feature\Http\Resources\Transaction;

use App\Http\Resources\Transaction\TransactionCollection;
use App\Models\Account;
use App\Models\Transaction;
use App\Services\Transaction\TransactionServiceContract;
use Tests\TestCase;

class TransactionCollectionTest extends TestCase
{
    public function test_it_transforms_a_transaction_model_collection_into_the_expected_json_resource_collection()
    {
        $count = 20;
        $account = Account::factory()->create();
        Transaction::factory()->count($count)->create(['account_id' => $account->id]);
        $paginated = app(TransactionServiceContract::class)->retrievePaginatedAccountTransactions($account);

        $transactionCollection = new TransactionCollection($paginated);

        $result = $transactionCollection->toArray(request());

        $this->assertCount(15, $result['data']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('links', $result);
        $this->assertArrayHasKey('first', $result['links']);
        $this->assertArrayHasKey('last', $result['links']);
        $this->assertArrayHasKey('prev', $result['links']);
        $this->assertArrayHasKey('next', $result['links']);
        $this->assertEquals(env('APP_URL') . '?page=1', $result['links']['first']);
        $this->assertEquals(env('APP_URL') . '?page=2', $result['links']['last']);
        $this->assertEquals(null, $result['links']['prev']);
        $this->assertEquals(env('APP_URL') . '?page=2', $result['links']['next']);
        $this->assertArrayHasKey('meta', $result);
        $this->assertArrayHasKey('current_page', $result['meta']);
        $this->assertArrayHasKey('last_page', $result['meta']);
        $this->assertArrayHasKey('from', $result['meta']);
        $this->assertArrayHasKey('to', $result['meta']);
        $this->assertArrayHasKey('per_page', $result['meta']);
        $this->assertArrayHasKey('total', $result['meta']);
        $this->assertEquals(1, $result['meta']['current_page']);
        $this->assertEquals(2, $result['meta']['last_page']);
        $this->assertEquals(1, $result['meta']['from']);
        $this->assertEquals(15, $result['meta']['to']);
        $this->assertEquals(15, $result['meta']['per_page']);
        $this->assertEquals(20, $result['meta']['total']);
    }
}
