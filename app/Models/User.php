<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'wallet_balance',
        'status',
        'contact_number',
        'organization',
        'purpose',
        'admin_notes',
        'status_updated_at',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet_balance' => 'decimal:2',
        'status_updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Scope a query to only include guest users.
     */
    public function scopeGuests(Builder $query): Builder
    {
        return $query->where('role', 'guest');
    }

    /**
     * Scope a query to only include pending users.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved users.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected users.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the user who approved this guest.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all wallet transactions for this user
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all reservations for this user
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Check if user has sufficient balance
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->wallet_balance >= $amount;
    }

    /**
     * Add credits to wallet
     */
    public function addCredits(float $amount, string $reference = null, string $description = null): WalletTransaction
    {
        $this->increment('wallet_balance', $amount);

        return $this->walletTransactions()->create([
            'type' => 'topup',
            'amount' => $amount,
            'reference' => $reference,
            'description' => $description ?? 'Credits added to wallet',
        ]);
    }

    /**
     * Deduct credits from wallet
     */
    public function deductCredits(float $amount, int $reservationId = null, string $reference = null, string $description = null): WalletTransaction
    {
        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('Insufficient wallet balance');
        }

        $this->decrement('wallet_balance', $amount);

        return $this->walletTransactions()->create([
            'type' => 'deduction',
            'amount' => $amount,
            'reference' => $reference,
            'description' => $description ?? 'Payment for reservation',
            'reservation_id' => $reservationId,
        ]);
    }

    /**
     * Refund credits to wallet
     */
    public function refundCredits(float $amount, int $reservationId = null, string $reference = null, string $description = null): WalletTransaction
    {
        $this->increment('wallet_balance', $amount);

        return $this->walletTransactions()->create([
            'type' => 'refund',
            'amount' => $amount,
            'reference' => $reference,
            'description' => $description ?? 'Refund for cancelled reservation',
            'reservation_id' => $reservationId,
        ]);
    }

    /**
     * Get formatted wallet balance
     */
    public function getFormattedBalanceAttribute(): string
    {
        return '₱' . number_format($this->wallet_balance, 2);
    }
}