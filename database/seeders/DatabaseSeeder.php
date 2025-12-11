<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Use firstOrCreate to prevent UniqueConstraintViolationException
        // The first argument is the criteria to find the user.
        // The second argument is the data to be set/updated if the user is created.
        
        User::firstOrCreate(
            ['email' => 'admin@esfms.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'faculty@esfms.test'],
            [
                'name' => 'Faculty User',
                'password' => Hash::make('password'),
                'role' => 'faculty',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@esfms.test'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        User::firstOrCreate(
            ['email' => 'student@esfms.test'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );
        
        // Call other seeders if you have them
        // $this->call([...]);
    }
}