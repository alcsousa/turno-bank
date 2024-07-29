<?php

namespace App\Models;

use App\Exceptions\InvalidCheckStatusTransitionException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Check extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
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

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * @throws InvalidCheckStatusTransitionException
     */
    public function markAsAccepted(): void
    {
        CheckStatus::ensureValidStatusTransition($this);
        $this->check_status_id = CheckStatus::ACCEPTED;
        $this->save();
    }

    /**
     * @throws InvalidCheckStatusTransitionException
     */
    public function markAsRejected(): void
    {
        CheckStatus::ensureValidStatusTransition($this);
        $this->check_status_id = CheckStatus::REJECTED;
        $this->save();
    }
}
