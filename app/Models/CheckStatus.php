<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckStatus extends Model
{
    use HasFactory;

    const PENDING = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;

    public static array $labels = [
        self::PENDING => 'Pending',
        self::ACCEPTED => 'Accepted',
        self::REJECTED => 'Rejected'
    ];
}
