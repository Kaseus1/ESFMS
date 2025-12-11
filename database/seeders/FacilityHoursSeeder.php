<?php

// File: database/seeders/FacilityHoursSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilityHoursSeeder extends Seeder
{
    public function run()
    {
        // Define operating hours for different facility types
        $facilityHours = [
            // Academic Facilities
            'auditorium' => ['opening_time' => '07:00:00', 'closing_time' => '22:00:00'],
            'conference_room' => ['opening_time' => '08:00:00', 'closing_time' => '18:00:00'],
            'classroom' => ['opening_time' => '07:30:00', 'closing_time' => '21:00:00'],
            'laboratory' => ['opening_time' => '08:00:00', 'closing_time' => '17:00:00'],
            
            // Sports & Recreation  
            'sports_facility' => ['opening_time' => '06:00:00', 'closing_time' => '23:00:00'],
            'pool' => ['opening_time' => '06:00:00', 'closing_time' => '22:00:00'],
            'gymnasium' => ['opening_time' => '06:00:00', 'closing_time' => '23:00:00'],
            
            // Common Areas
            'library' => ['opening_time' => '07:00:00', 'closing_time' => '22:00:00'],
            'cafeteria' => ['opening_time' => '07:00:00', 'closing_time' => '20:00:00'],
            
            // Default hours for other facilities
            'other' => ['opening_time' => '08:00:00', 'closing_time' => '18:00:00']
        ];

        $facilities = Facility::all();
        
        foreach ($facilities as $facility) {
            $type = strtolower($facility->type);
            $hours = $facilityHours[$type] ?? $facilityHours['other'];
            
            // Only update if hours are not already set
            if (!$facility->opening_time || !$facility->closing_time) {
                $facility->update($hours);
                $this->command->info("Updated hours for: {$facility->name} ({$facility->type})");
            }
        }
    }
}

// Alternative: Quick Tinker script for immediate use
/*
php artisan tinker

// Example: Update specific facilities
Facility::where('name', 'like', '%Pool%')->update(['opening_time' => '06:00:00', 'closing_time' => '22:00:00']);
Facility::where('name', 'like', '%Gym%')->update(['opening_time' => '06:00:00', 'closing_time' => '23:00:00']);
Facility::where('name', 'like', '%Auditorium%')->update(['opening_time' => '07:00:00', 'closing_time' => '22:00:00']);
Facility::where('name', 'like', '%Conference%')->update(['opening_time' => '08:00:00', 'closing_time' => '18:00:00']);

// Update all remaining facilities
Facility::whereNull('opening_time')->orWhereNull('closing_time')->update([
    'opening_time' => '08:00:00', 
    'closing_time' => '18:00:00'
]);
*/