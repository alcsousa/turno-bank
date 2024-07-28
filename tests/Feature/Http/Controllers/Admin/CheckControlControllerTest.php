<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Check;
use App\Models\CheckStatus;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckControlControllerTest extends TestCase
{
    public function test_it_allows_valid_statuses_as_query_param()
    {
        // Given the allowed statuses and a logged admin user
        $allowedStatuses = array_map(
            fn ($status) => strtolower($status),
            CheckStatus::$labels
        );
        $adminUser = User::factory()->admin()->create();
        $this->be($adminUser);

        foreach ($allowedStatuses as $status) {
            $response = $this->json('GET', "/api/admin/checks?status=$status");

            $response->assertStatus(Response::HTTP_OK);
        }
    }

    public function test_it_validates_against_invalid_status_as_query_param()
    {
        $adminUser = User::factory()->admin()->create();

        $response = $this->be($adminUser)->json('GET', "/api/admin/checks?status=invalid-example");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The selected status is invalid.');
    }

    public function test_it_lists_pending_checks_for_the_admin()
    {
        // Given existing pending checks and a logged admin user
        $count = 5;
        $pendingChecks = Check::factory($count)->pending()->create();
        $adminUser = User::factory()->admin()->create();
        $this->be($adminUser);

        $response = $this->json('GET', '/api/admin/checks?status=pending');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($count, 'data');
        $responseChecks = collect($response->json('data'));

        foreach ($pendingChecks as $check) {
            $filtered = $responseChecks->where('id', '=', $check->id)->first();
            $this->assertEquals($check->id, $filtered['id']);
            $this->assertEquals($check->amount, $filtered['amount']);
            $this->assertEquals($check->description, $filtered['description']);
            $this->assertEquals($check->image_url, $filtered['image_url']);
            $this->assertEquals($check->created_at->toDateTimeString(), $filtered['created_at']);
            $this->assertEquals($check->status->id, $filtered['status']['id']);
            $this->assertEquals($check->status->name, $filtered['status']['name']);
        }
    }

    public function test_it_forbids_non_admin_users_from_listing_checks_and_return_http_forbidden()
    {
        // Given existing pending checks and a logged customer user
        Check::factory(5)->pending()->create();
        $customerUser = User::factory()->customer()->create();
        $this->be($customerUser);

        $response = $this->json('GET', '/api/admin/checks?status=pending');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
