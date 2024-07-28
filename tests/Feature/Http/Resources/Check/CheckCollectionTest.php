<?php

namespace Tests\Feature\Http\Resources\Check;

use App\Http\Resources\Check\CheckCollection;
use App\Models\Check;
use App\Models\User;
use Tests\TestCase;

class CheckCollectionTest extends TestCase
{
    public function test_it_transforms_a_check_model_collection_into_the_expected_json_resource_collection()
    {
        // Given
        $count = 20;
        $user = User::factory()->create();
        Check::factory()->count($count)->create(['user_id' => $user->id]);
        $paginated = Check::with('status')->where('user_id', $user->id)->paginate();

        // When we create an instance of CheckCollection
        $checkCollection = new CheckCollection($paginated);

        // Then we assert the result
        $result = $checkCollection->toArray(request());

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
