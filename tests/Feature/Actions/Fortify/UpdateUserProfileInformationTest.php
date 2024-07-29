<?php

namespace Tests\Feature\Actions\Fortify;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Tests\TestCase;

class UpdateUserProfileInformationTest extends TestCase
{
    public function test_it_updates_user_profile_information()
    {
        $user = User::factory()->create([
            'email' => 'old@test.com',
        ]);

        $action = new UpdateUserProfileInformation();
        $action->update($user, [
            'name' => 'John Doe New',
            'email' => 'new@test.com',
        ]);

        // Assert
        $user->refresh();
        $this->assertEquals('John Doe New', $user->name);
        $this->assertEquals('new@test.com', $user->email);
    }
}
