<?php

namespace App\Exceptions;

use Exception;

class InvalidCheckStatusTransitionException extends Exception
{
    protected $message = 'Invalid check status transition';
}
