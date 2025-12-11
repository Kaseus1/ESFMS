<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;

class LargeReservationSeeder extends Seeder
{
    public function run()
    {
        // 1. CHANGE THIS: Set the total records to 10,000
        $totalRecords = 10000;
        
        // Use the corresponding Factory to generate the data
        $data = Reservation::factory()
                          ->count($totalRecords)
                          ->make() // Generates Eloquent models in memory
                          ->toArray(); // Converts the collection of models into a single PHP array

        // 2. CHANGE THIS: Use an efficient chunk size like 500 or 1000
        // (You had it set to 1, which defeats the purpose of bulk inserting)
        $chunkSize = 500; 
        
        // Loop through the data in chunks and insert each batch
        foreach (array_chunk($data, $chunkSize) as $chunk) {
            DB::table('reservations')->insert($chunk);
        }

        $this->command->info("Successfully seeded {$totalRecords} reservations.");
    }
}