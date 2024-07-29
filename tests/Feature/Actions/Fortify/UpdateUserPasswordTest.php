<?php

namespace Tests\Feature\Actions\Fortify;

use App\Actions\Fortify\UpdateUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateUserPasswordTest extends TestCase
{
    public function test_it_updates_the_user_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $this->be($user);

        $action = new UpdateUserPassword();
        $action->update($user, [
            'current_password' => 'password',
            'password' => 'new-password',
        ]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_it_fails_if_current_password_does_not_match()
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        $this->expectException(ValidationException::class);

        $action = new UpdateUserPassword();
        $action->update($user, [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
        ]);
    }
}
