<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckControllerTest extends TestCase
{
    private array $checkJsonStructure = [
        'id',
        'amount',
        'description',
        'image_path',
        'is_approved',
        'reviewed_at'
    ];

    public function test_a_check_can_be_stored_and_returns_http_created()
    {
        $user = User::factory()->create();
        $this->be($user);

        Storage::fake();
        $fakeFile = UploadedFile::fake()->image('test.jpg');
        $payload = [
            'amount' => '100.00',
            'description' => 'Gift',
            'image' => $fakeFile
        ];

        $response = $this->json('POST', '/api/checks', $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure($this->checkJsonStructure);
        $response->assertJsonFragment($payload);

        Storage::disk()->assertExists('checks/' . $fakeFile->hashName());
    }
}
