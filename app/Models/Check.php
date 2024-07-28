<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Check extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'image_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'description' => 'string',
        'image_path' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(CheckStatus::class, 'check_status_id', 'id');
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage' . $this->image_path);
    }
}
