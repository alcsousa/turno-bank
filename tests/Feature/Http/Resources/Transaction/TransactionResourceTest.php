<?php

namespace Tests\Feature\Http\Resources\Transaction;

use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use Tests\TestCase;

class TransactionResourceTest extends TestCase
{
    public function test_it_transforms_a_transaction_model_into_the_expected_json_resource()
    {
        $transaction = Transaction::factory()->create();

        $resource = new TransactionResource($transaction);

        $this->assertEquals(json_encode([
            'id' => $transaction->id,
            'account_id' => $transaction->account_id,
            'amount' => $transaction->amount,
            'description' => $transaction->description,
            'created_at' => $transaction->created_at
        ]), $resource->toJson());
    }
}
