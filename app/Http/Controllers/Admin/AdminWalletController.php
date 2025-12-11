<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWalletController extends Controller
{
    /**
     * Display wallet management dashboard
     */
    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $role = $request->get('role');
        $balanceFilter = $request->get('balance_filter');

        // Build query
        $query = User::withCount('walletTransactions');

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($role) {
            $query->where('role', $role);
        }

        // Apply balance filter
        if ($balanceFilter === 'zero') {
            $query->where('wallet_balance', 0);
        } elseif ($balanceFilter === 'positive') {
            $query->where('wallet_balance', '>', 0);
        }

        // Get users with pagination
        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        // Calculate statistics
        $totalBalance = User::sum('wallet_balance');
        $totalTopups = WalletTransaction::where('type', 'topup')->sum('amount');
        $totalSpent = WalletTransaction::where('type', 'deduction')->sum('amount');
        $totalRefunds = WalletTransaction::where('type', 'refund')->sum('amount');

        return view('admin.wallet.index', compact(
            'users',
            'totalBalance',
            'totalTopups',
            'totalSpent',
            'totalRefunds'
        ));
    }

    /**
     * Show top-up form
     */
    public function topupForm(Request $request)
    {
        $users = User::whereIn('role', ['student', 'faculty', 'guest'])
            ->orderBy('name')
            ->get();

        return view('admin.wallet.topup', compact('users'));
    }

    /**
     * Process top-up
     */
    public function topup(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1|max:100000',
            'reference' => 'nullable|string|max:1000',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $amount = $validated['amount'];
        $reference = $validated['reference'] ?? 'Admin top-up';

        DB::beginTransaction();

        try {
            // Increase wallet balance
            $user->increment('wallet_balance', $amount);

            // Create transaction record
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'topup',
                'amount' => $amount,
                'reference' => $reference,
            ]);

            DB::commit();

            return redirect()->route('admin.wallet.index')
                ->with('success', "Successfully added ₱" . number_format($amount, 2) . " to {$user->name}'s wallet.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to process top-up. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display all transactions
     */
    public function transactions(Request $request)
    {
        $type = $request->get('type');

        $query = WalletTransaction::with('user');

        // Apply type filter
        if ($type) {
            $query->where('type', $type);
        }

        $transactions = $query->latest()->paginate(30)->withQueryString();

        return view('admin.wallet.transactions', compact('transactions'));
    }

    /**
     * Display user-specific transactions
     */
    public function userTransactions(User $user)
    {
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(30);

        return view('admin.wallet.user-transactions', compact('user', 'transactions'));
    }

    /**
     * Refund a reservation
     */
    public function refundReservation(Request $request, Reservation $reservation)
    {
        // Validate reservation can be refunded
        if ($reservation->cost <= 0) {
            return back()->withErrors(['error' => 'This reservation has no cost to refund.']);
        }

        if ($reservation->status === 'cancelled') {
            return back()->withErrors(['error' => 'This reservation has already been cancelled and refunded.']);
        }

        $request->validate([
            'refund_reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $user = $reservation->user;

            // Refund to wallet
            $user->increment('wallet_balance', $reservation->cost);

            // Create refund transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'refund',
                'amount' => $reservation->cost,
                'reference' => 'Admin refund for reservation #' . $reservation->id . 
                              ($request->refund_reason ? ' - ' . $request->refund_reason : ''),
            ]);

            // Update reservation status
            $reservation->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->refund_reason ?? 'Cancelled by admin with refund',
                'cancelled_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 
                'Reservation refunded successfully. ₱' . number_format($reservation->cost, 2) . 
                ' returned to ' . $user->name . '\'s wallet.'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to process refund. Please try again.']);
        }
    }

    /**
     * Adjust wallet balance (manual adjustment)
     */
    public function adjustBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:add,deduct',
            'reason' => 'required|string|max:500',
        ]);

        $amount = abs($validated['amount']);
        $type = $validated['type'];
        $reason = $validated['reason'];

        DB::beginTransaction();

        try {
            if ($type === 'add') {
                $user->increment('wallet_balance', $amount);
                
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'topup',
                    'amount' => $amount,
                    'reference' => 'Manual adjustment: ' . $reason,
                ]);

                $message = "Added ₱" . number_format($amount, 2) . " to {$user->name}'s wallet.";
                
            } else {
                // Check if user has sufficient balance
                if ($user->wallet_balance < $amount) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Insufficient balance for deduction.']);
                }

                $user->decrement('wallet_balance', $amount);
                
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'deduction',
                    'amount' => $amount,
                    'reference' => 'Manual adjustment: ' . $reason,
                ]);

                $message = "Deducted ₱" . number_format($amount, 2) . " from {$user->name}'s wallet.";
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to adjust balance. Please try again.']);
        }
    }
}