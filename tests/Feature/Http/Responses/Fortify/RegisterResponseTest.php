<?php

namespace Tests\Feature\Http\Responses\Fortify;

use Tests\TestCase;

class RegisterResponseTest extends TestCase
{
    public function test_it_adds_the_user_data_to_the_response()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'my-very-secret-password'
        ];

        $response = $this->json('POST', '/register', $userData);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'name',
            'email'
        ]);
        $response->assertJson([
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }
}
