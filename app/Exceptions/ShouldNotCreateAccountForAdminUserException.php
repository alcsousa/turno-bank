<?php

namespace App\Exceptions;

use Exception;

class ShouldNotCreateAccountForAdminUserException extends Exception
{
    protected $message = 'Should not create account for admin user';
}
