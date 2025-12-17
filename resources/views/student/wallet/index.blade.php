@extends('layouts.student')

@section('title', 'My Wallet')

@push('head-scripts')
<style>
    :root {
        --color-royal-blue: #002366;
        --color-green: #10B981;
        --color-red: #EF4444;
        --color-gray: #6B7280;
    }

    /* Balance Card */
    .balance-card {
        background: linear-gradient(135deg, var(--color-royal-blue), #001A4A);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px -5px rgba(0, 35, 102, 0.3);
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .balance-card::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px; background: rgba(255,255,255,0.05);
        border-radius: 50%; pointer-events: none;
    }

    .balance-label { font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem; }
    .balance-amount { font-size: 2.5rem; font-weight: 800; line-height: 1; }
    
    /* Transaction List */
    .section-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;
    }
    .section-title { font-size: 1.25rem; font-weight: 700; color: #172030; }

    .transaction-card {
        background: white; border-radius: 16px; padding: 1.25rem;
        border: 1px solid #E2E8F0; margin-bottom: 1rem;
        display: flex; justify-content: space-between; align-items: center;
        transition: transform 0.2s;
    }
    .transaction-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }

    .t-icon-box {
        width: 45px; height: 45px; border-radius: 12px; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
        margin-right: 1rem;
    }
    
    .t-icon-topup { background: #ECFDF5; color: var(--color-green); }
    .t-icon-payment { background: #FEF2F2; color: var(--color-red); }
    .t-icon-refund { background: #EFF6FF; color: #3B82F6; }

    .t-details h4 { font-weight: 700; color: #172030; font-size: 0.95rem; }
    .t-details p { font-size: 0.8rem; color: #64748B; margin-top: 0.2rem; }
    
    .t-amount { font-weight: 800; font-size: 1rem; text-align: right; }
    .t-amount.positive { color: var(--color-green); }
    .t-amount.negative { color: var(--color-red); }
    
    .empty-state { text-align: center; padding: 4rem 1rem; color: #94A3B8; }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

    @media (max-width: 640px) {
        .balance-amount { font-size: 2rem; }
        .transaction-card { padding: 1rem; }
    }
</style>
@endpush

@section('content')

<div class="balance-card">
    <div class="relative z-10">
        <div class="flex justify-between items-start">
            <div>
                <div class="balance-label">Current Balance</div>
                <div class="balance-amount">₱{{ number_format(Auth::user()->wallet_balance, 2) }}</div>
                <div class="mt-4 inline-flex items-center gap-2 text-xs bg-white/10 px-3 py-1.5 rounded-lg border border-white/10">
                    <i class="fa-solid fa-shield-halved"></i> Secure Wallet
                </div>
            </div>
            <div class="hidden sm:block">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-white/10">
            <p class="text-sm opacity-80">
                <i class="fa-solid fa-circle-info mr-1"></i> 
                To top-up your wallet, please visit the CPAC Finance Office and provide your Student ID. You may also contact <span class="font-bold text-white">+63 (33) 320-0870</span>.
            </p>
        </div>
    </div>
</div>

<div>
    <div class="section-header">
        <h2 class="section-title">Transaction History</h2>
        <button class="text-sm text-gray-500 font-semibold flex items-center gap-1 hover:text-gray-700">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </div>

    @if(isset($transactions) && $transactions->count() > 0)
        @foreach($transactions as $transaction)
            <div class="transaction-card">
                <div class="flex items-center">
                    <div class="t-icon-box 
                        {{ $transaction->type == 'topup' ? 't-icon-topup' : ($transaction->type == 'refund' ? 't-icon-refund' : 't-icon-payment') }}">
                        <i class="fa-solid 
                            {{ $transaction->type == 'topup' ? 'fa-arrow-down' : ($transaction->type == 'refund' ? 'fa-rotate-left' : 'fa-arrow-up') }}">
                        </i>
                    </div>
                    
                    <div class="t-details">
                        <h4>
                            @if($transaction->type == 'topup') Wallet Top-up
                            @elseif($transaction->type == 'payment') Payment for Reservation
                            @elseif($transaction->type == 'refund') Refund Processed
                            @else {{ ucfirst($transaction->type) }}
                            @endif
                        </h4>
                        <p>{{ $transaction->created_at->format('M d, Y • h:i A') }}</p>
                        @if($transaction->reference)
                            <p class="text-xs text-gray-400 mt-0.5">Ref: {{ $transaction->reference }}</p>
                        @endif
                    </div>
                </div>

                <div class="t-amount {{ $transaction->type == 'payment' ? 'negative' : 'positive' }}">
                    {{ $transaction->type == 'payment' ? '-' : '+' }}₱{{ number_format($transaction->amount, 2) }}
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p class="font-semibold text-gray-600">No transactions yet</p>
            <p class="text-sm mt-1">Your wallet history will appear here.</p>
        </div>
    @endif
</div>

@endsection