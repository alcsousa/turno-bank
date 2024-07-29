<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'amount',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'description' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
