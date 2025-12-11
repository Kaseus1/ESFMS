<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestRequest extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'contact_number',
        'facility_id',
        'event_date',
        'start_time',
        'end_time',
        'purpose',
        'status',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}