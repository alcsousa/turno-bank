<?php

namespace App\Exceptions;

use Exception;

class AccountDoesNotHaveEnoughFundsException extends Exception
{
    protected $message = 'Account does not have enough funds';
}
