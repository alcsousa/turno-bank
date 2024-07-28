<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Account;
use App\Models\Check;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckControllerTest extends TestCase
{
    public function test_a_check_can_be_stored_and_returns_http_created()
    {
        // Given a valid customer user with an existing account and payload
        Storage::fake();
        $fakeFile = UploadedFile::fake()->image('test.jpg');
        $user = User::factory()->customer()->create();
        Account::factory()->create(['user_id' => $user->id]);
        $payload = [
            'amount' => '100.00',
            'description' => 'Gift',
            'image' => $fakeFile
        ];

        // When we hit the endpoint with an authenticated user
        $response = $this->be($user)->json('POST', '/api/checks', $payload);

        // Then we check that the data is OK and the file exists
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment($payload);
        $response->assertJsonStructure([
            'id',
            'amount',
            'description',
            'image_url',
            'status' => [
                'id',
                'name'
            ]
        ]);
        Storage::disk()->assertExists('checks/' . $fakeFile->hashName());
    }

    public function test_checks_can_be_retrieved_from_same_logged_user_and_returns_http_ok()
    {
        // Given that we have existing checks from a logged user
        $count = 5;
        $user = User::factory()->customer()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $this->be($user);
        $existingChecks = Check::factory($count)->create(['account_id' => $account->id]);
        // Checks from another user to make sure it's filtered correctly
        Check::factory(2)->create();

        // When we hit the endpoint
        $response = $this->json('GET', '/api/checks');
        $responseChecks = collect($response->json('data'));

        // Then we assert the response is as expected
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($count, 'data');

        foreach ($existingChecks as $check) {
            $filtered = $responseChecks->where('id', '=', $check->id)->first();
            $this->assertEquals($check->id, $filtered['id']);
            $this->assertEquals($check->amount, $filtered['amount']);
            $this->assertEquals($check->description, $filtered['description']);
            $this->assertEquals($check->image_url, $filtered['image_url']);
            $this->assertEquals($check->created_at->toDateTimeString(), $filtered['created_at']);
            $this->assertEquals($check->status->id, $filtered['status']['id']);
            $this->assertEquals($check->status->name, $filtered['status']['name']);

            $this->assertEquals($check->account->id, $filtered['account']['id']);
            $this->assertEquals($check->account->balance, $filtered['account']['balance']);

            $this->assertEquals($check->account->user->id, $filtered['account']['user']['id']);
            $this->assertEquals($check->account->user->name, $filtered['account']['user']['name']);
            $this->assertEquals($check->account->user->email, $filtered['account']['user']['email']);
        }
    }

    public function test_checks_can_be_retrieved_using_pagination()
    {
        // Given that we have existing checks from a logged user
        $count = 20;
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $this->be($user);
        Check::factory($count)->create(['account_id' => $account->id]);

        // When we hit the endpoint requesting items from page #2
        $response = $this->json('GET', '/api/checks?page=2');

        // Then we assert the response is as expected
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'amount',
                    'description',
                    'image_url',
                    'status' => [
                        'id',
                        'name'
                    ],
                    'account' => [
                        'id',
                        'balance',
                        'user' => [
                            'id',
                            'name',
                            'email'
                        ]
                    ]
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
