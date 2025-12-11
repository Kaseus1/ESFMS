<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;

class GuestWalletController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(10);

        $stats = [
            'balance' => $user->wallet_balance,
            'total_topups' => WalletTransaction::where('user_id', $user->id)->where('type', 'topup')->sum('amount'),
            'total_spent' => WalletTransaction::where('user_id', $user->id)->where('type', 'deduction')->sum('amount'),
            'total_refunds' => WalletTransaction::where('user_id', $user->id)->where('type', 'refund')->sum('amount'),
        ];

        return view('guest.wallet.index', compact('user', 'transactions', 'stats'));
    }
}