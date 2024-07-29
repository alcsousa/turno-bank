<?php

namespace Tests\Feature\Actions\Fortify;

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetUserPasswordTest extends TestCase
{
    public function test_it_resets_the_user_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $action = new ResetUserPassword();
        $action->reset($user, [
            'password' => 'new-password',
        ]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
