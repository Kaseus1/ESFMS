<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'image', 'type', 'location',
        'capacity', 'max_capacity', 'opening_time', 'closing_time',
        'is_public', 'guest_accessible', 'buffer_time', 'hourly_rate',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'guest_accessible' => 'boolean',
        'capacity' => 'integer',
        'max_capacity' => 'integer',
        'buffer_time' => 'integer',
        'hourly_rate' => 'decimal:2',
    ];

    protected $appends = ['image_url'];

    const TYPES = [
        'classroom' => 'Classroom',
        'conference_room' => 'Conference Room',
        'auditorium' => 'Auditorium',
        'laboratory' => 'Laboratory',
        'sports_facility' => 'Sports Facility',
        'other' => 'Other',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function approvedReservations()
    {
        return $this->reservations()->where('status', 'approved');
    }

    public function upcomingReservations()
    {
        return $this->approvedReservations()
                    ->where('start_time', '>=', now())
                    ->orderBy('start_time', 'asc');
    }

    public function activeReservation()
    {
        $bufferMinutes = $this->buffer_time ?? 0;

        return $this->approvedReservations()
                    ->where('start_time', '<=', now())
                    ->whereRaw("DATE_ADD(end_time, INTERVAL ? MINUTE) >= ?", [$bufferMinutes, now()])
                    ->first();
    }

    /**
     * Check if facility is currently available (NOW)
     */
    public function isAvailable()
    {
        if (!$this->is_public) {
            return false;
        }

        $now = now();
        
        $activeReservation = $this->approvedReservations()
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->exists();

        if ($activeReservation) {
            Log::info("Facility #{$this->id} ({$this->name}) is BOOKED", [
                'current_time' => $now->toDateTimeString(),
                'active_reservations' => $this->approvedReservations()
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->get(['id', 'start_time', 'end_time', 'status'])
                    ->toArray()
            ]);
        }
        
        return !$activeReservation;
    }

    public function isAvailableAt($startTime, $endTime, $excludeReservationId = null)
    {
        if (!$this->is_public) {
            return false;
        }

        $bufferMinutes = $this->buffer_time ?? 0;
        $bufferedStart = Carbon::parse($startTime)->subMinutes($bufferMinutes);
        $bufferedEnd = Carbon::parse($endTime)->addMinutes($bufferMinutes);

        $query = $this->approvedReservations()
            ->where(function ($q) use ($bufferedStart, $bufferedEnd) {
                $q->whereBetween('start_time', [$bufferedStart, $bufferedEnd])
                  ->orWhereBetween('end_time', [$bufferedStart, $bufferedEnd])
                  ->orWhere(function($sub) use ($bufferedStart, $bufferedEnd) {
                      $sub->where('start_time', '<=', $bufferedStart)
                          ->where('end_time', '>=', $bufferedEnd);
                  });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }

    public function isWithinOperatingHours($startTime, $endTime)
    {
        if (!$this->opening_time || !$this->closing_time) {
            return true;
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        
        $openingTime = Carbon::parse($this->opening_time);
        $closingTime = Carbon::parse($this->closing_time);

        $openingTime->setDate($start->year, $start->month, $start->day);
        $closingTime->setDate($end->year, $end->month, $end->day);

        return $start->format('H:i') >= $openingTime->format('H:i') 
            && $end->format('H:i') <= $closingTime->format('H:i');
    }

    public function getNextReservation()
    {
        return $this->upcomingReservations()->first();
    }

    public function getTypeLabel()
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        
        return null;
    }

    public function getDefaultImageUrl()
    {
        $typeImages = [
            'classroom' => 'facility-placeholders/classroom.svg',
            'conference_room' => 'facility-placeholders/conference.svg',
            'auditorium' => 'facility-placeholders/auditorium.svg',
            'laboratory' => 'facility-placeholders/laboratory.svg',
            'sports_facility' => 'facility-placeholders/sports.svg',
            'other' => 'facility-placeholders/default.svg',
        ];

        $imagePath = $typeImages[$this->type] ?? 'facility-placeholders/default.svg';
        
        return asset('images/' . $imagePath);
    }

    public function hasImage()
    {
        return !empty($this->image);
    }

    /**
     * Get formatted hourly rate
     */
    public function getFormattedHourlyRate()
    {
        return '₱' . number_format($this->hourly_rate, 2);
    }

    /**
     * Calculate cost for duration in hours
     */
    public function calculateCost($hours)
    {
        return $this->hourly_rate * $hours;
    }

    /**
     * Calculate cost between two times
     */
    public function calculateCostBetween($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $hours = $start->diffInMinutes($end) / 60;
        
        return $this->calculateCost($hours);
    }

    /**
     * Get formatted cost for duration
     */
    public function getFormattedCost($hours)
    {
        return '₱' . number_format($this->calculateCost($hours), 2);
    }

    /**
     * Get availability status badge HTML
     */
    public function getStatusBadge()
    {
        $isAvailable = $this->isAvailable();
        
        if ($isAvailable) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>';
        }
        
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Booked</span>';
    }

    public function getTypeBadge()
    {
        return '<span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">' . 
                e($this->getTypeLabel()) . '</span>';
    }

    public function getFormattedCapacity()
    {
        if ($this->max_capacity && $this->capacity) {
            return "{$this->capacity} - {$this->max_capacity} people";
        }
        
        if ($this->capacity) {
            return "{$this->capacity} people";
        }
        
        return 'Not specified';
    }

    public function getOperatingHours()
    {
        if (!$this->opening_time || !$this->closing_time) {
            return '24/7';
        }

        $opening = Carbon::parse($this->opening_time)->format('g:i A');
        $closing = Carbon::parse($this->closing_time)->format('g:i A');
        
        return "{$opening} - {$closing}";
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for available facilities (no active bookings NOW)
     */
    public function scopeAvailable($query)
    {
        $now = now();
        
        return $query->where('is_public', true)
            ->whereDoesntHave('reservations', function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            });
    }

    /**
     * Scope for booked facilities (has active bookings NOW)
     */
    public function scopeBooked($query)
    {
        $now = now();
        
        return $query->whereHas('reservations', function($q) use ($now) {
            $q->where('status', 'approved')
              ->where('start_time', '<=', $now)
              ->where('end_time', '>=', $now);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    public function getTotalReservationsAttribute()
    {
        return $this->reservations()->count();
    }

    public function getUtilizationRate($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->endOfMonth();

        $totalMinutes = $startDate->diffInMinutes($endDate);
        
        $bookedMinutes = $this->approvedReservations()
            ->where('start_time', '>=', $startDate)
            ->where('end_time', '<=', $endDate)
            ->get()
            ->sum(function($reservation) {
                return Carbon::parse($reservation->start_time)
                    ->diffInMinutes(Carbon::parse($reservation->end_time));
            });

        return $totalMinutes > 0 ? round(($bookedMinutes / $totalMinutes) * 100, 2) : 0;
    }
}
