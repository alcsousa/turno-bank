<?php

namespace Tests\Feature\Http\Resources\Check;

use App\Http\Resources\Check\CheckResource;
use App\Models\Check;
use Tests\TestCase;

class CheckResourceTest extends TestCase
{
    public function test_it_transforms_a_check_model_into_the_expected_json_resource()
    {
        $check = Check::factory()->create();

        $resource = new CheckResource($check);

        $this->assertEquals(json_encode(
            [
                'id' => $check->id,
                'amount' => $check->amount,
                'description' => $check->description,
                'image_url' => $check->image_url,
                'created_at' => $check->created_at->toDateTimeString(),
                'status' => [
                    'id' => $check->status->id,
                    'name' => $check->status->name
                ]
            ]
        ), $resource->toJson());
    }
}
