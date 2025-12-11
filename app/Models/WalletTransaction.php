<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'reference',
        'description',
        'reservation_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reservation associated with this transaction
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Get type badge class for display
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            'topup' => 'badge-success',
            'deduction' => 'badge-danger',
            'refund' => 'badge-info',
            default => 'badge-secondary',
        };
    }

    /**
     * Get type label for display
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'topup' => 'Top Up',
            'deduction' => 'Payment',
            'refund' => 'Refund',
            default => ucfirst($this->type),
        };
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}