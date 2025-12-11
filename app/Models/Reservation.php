<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_id',
        'event_name',
        'purpose',
        'notes',
        'start_time',
        'end_time',
        'participants',
        'status',
        'cost',
        'payment_status',

        // Recurring fields
        'is_recurring',
        'recurrence_type',
        'recurrence_end_date',
        'recurrence_days',

        // Event type
        'event_type',

        // Setup requirements
        'requires_setup',
        'setup_requirements',

        // Equipment
        'requires_equipment',
        'equipment_needed',

        // Cancellation fields
        'cancellation_reason',
        'cancelled_at',
        
        // Admin notes
        'admin_notes',
    ];

    protected $casts = [
        'start_time'           => 'datetime',
        'end_time'             => 'datetime',
        'is_recurring'         => 'boolean',
        'recurrence_days'      => 'array',
        'recurrence_end_date'  => 'date',
        'requires_setup'       => 'boolean',
        'requires_equipment'   => 'boolean',
        'cancelled_at'         => 'datetime',
        'cost'                 => 'decimal:2',
        'payment_status'       => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Calculate cost based on hours and facility rate
     */
    public function calculateCost(): float
    {
        if (!$this->facility) {
            return 0;
        }
        
        $hours = $this->start_time->diffInHours($this->end_time);
        if ($hours < 1) $hours = 1;
        
        return $hours * ($this->facility->hourly_rate ?? 0);
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute(): int
    {
        $hours = $this->start_time->diffInHours($this->end_time);
        return $hours < 1 ? 1 : $hours;
    }

    /**
     * Get formatted cost
     */
    public function getFormattedCostAttribute(): string
    {
        return '₱' . number_format($this->cost ?? 0, 2);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeDeleted()
    {
        if ($this->status !== 'cancelled') {
            return false;
        }

        if (!$this->cancelled_at) {
            return true;
        }

        return $this->cancelled_at->diffInDays(now()) >= 7;
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}