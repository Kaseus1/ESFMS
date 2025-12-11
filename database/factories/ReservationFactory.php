<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        // Define the structure of the fake data fields
        return [
            // Ensure the keys here match your database column names exactly
            'guest_name' => $this->faker->name(),
            'check_in' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'created_at' => now(), // Required for bulk insert
            'updated_at' => now(), // Required for bulk insert
        ];
    }
}