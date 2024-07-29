<?php

namespace Tests\Feature\Actions\Fortify;

use App\Actions\Fortify\CreateNewUser;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    public function test_it_dispatches_the_user_registered_event_as_expected()
    {
        Event::fake();
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'my-very-strong-pass'
        ];

        (new CreateNewUser())->create($userData);

        Event::assertDispatched(UserRegistered::class);
    }

    public function test_it_creates_an_account_with_zero_balance_when_a_new_user_is_registered()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'my-very-strong-pass'
        ];

        $user = (new CreateNewUser())->create($userData);

        $this->assertEquals(false, $user->refresh()->isAdmin());
        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'balance' => 0
        ]);
    }
}
