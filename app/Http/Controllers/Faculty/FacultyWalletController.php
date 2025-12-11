<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyWalletController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type');

        $query = WalletTransaction::where('user_id', $user->id);

        if ($type) {
            $query->where('type', $type);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'balance' => $user->wallet_balance,
            'total_topups' => WalletTransaction::where('user_id', $user->id)->where('type', 'topup')->sum('amount'),
            'total_spent' => WalletTransaction::where('user_id', $user->id)->where('type', 'deduction')->sum('amount'),
            'total_refunds' => WalletTransaction::where('user_id', $user->id)->where('type', 'refund')->sum('amount'),
        ];

        return view('faculty.wallet.index', compact('user', 'transactions', 'stats'));
    }
}