<?php

namespace Tests\Feature\Http\Resources\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    public function test_it_transforms_a_user_model_into_a_user_resource()
    {
        $user = User::factory()->create();

        $resource = new UserResource($user);

        $this->assertEquals(json_encode(
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin
            ]
        ), $resource->toJson());
    }
}
