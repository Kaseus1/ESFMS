<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WalletTransaction;

class WalletSystemSeeder extends Seeder
{
    public function run()
    {
        // Optional: seed initial credits for all users
        $users = User::all();

        foreach ($users as $user) {
            $amount = 500; // initial credit

            $user->wallet_balance += $amount;
            $user->save();

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'topup',
                'amount' => $amount,
                'reference' => 'INITIAL-SEED-'.$user->id,
                'description' => 'Initial wallet credit',
                'reservation_id' => null,
            ]);
        }
    }
}
