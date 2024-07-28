<?php

namespace Tests\Feature\Http\Resources\Check;

use App\Http\Resources\Check\CheckStatusResource;
use App\Models\CheckStatus;
use Tests\TestCase;

class CheckStatusResourceTest extends TestCase
{
    public function test_it_transforms_a_check_status_model_into_the_expected_json_resource()
    {
        // Given that the status already exists in the db bc it was seeded
        $checkStatus = CheckStatus::first();

        $resource = new CheckStatusResource($checkStatus);

        $this->assertEquals(json_encode(
            [
                'id' => $checkStatus->id,
                'name' => $checkStatus->name
            ]
        ), $resource->toJson());
    }
}
