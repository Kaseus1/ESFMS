@extends('layouts.student')

@section('title', 'My Wallet')

@section('content')
<!-- Wallet Balance Header -->
<div class="welcome-section" style="margin-bottom: 2rem;">
    <div class="welcome-content">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-wallet" style="font-size: 2.5rem;"></i>
            </div>
            <div>
                <p style="font-size: 1rem; opacity: 0.9; margin-bottom: 0.5rem;">Current Balance</p>
                <h1 style="font-size: 3rem; font-weight: 900; letter-spacing: -1px;">₱{{ number_format($stats['balance'] ?? 0, 2) }}</h1>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 4px solid #10b981;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: #6b7280; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Top-ups</p>
                <p style="font-size: 1.75rem; font-weight: 800; color: #10b981;">₱{{ number_format($stats['total_topups'] ?? 0, 2) }}</p>
            </div>
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-arrow-up" style="color: white; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 4px solid #ef4444;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: #6b7280; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Spent</p>
                <p style="font-size: 1.75rem; font-weight: 800; color: #ef4444;">₱{{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
            </div>
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-arrow-down" style="color: white; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 4px solid #3b82f6;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: #6b7280; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Refunds</p>
                <p style="font-size: 1.75rem; font-weight: 800; color: #3b82f6;">₱{{ number_format($stats['total_refunds'] ?? 0, 2) }}</p>
            </div>
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-rotate-left" style="color: white; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div style="background: white; border-radius: 20px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #172030; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-clock-rotate-left" style="color: #002366;"></i>
            Transaction History
        </h3>
        
        <!-- Filter -->
        <form method="GET" style="display: flex; gap: 0.5rem;">
            <select name="type" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; background: white; cursor: pointer;">
                <option value="">All Types</option>
                <option value="topup" {{ request('type') == 'topup' ? 'selected' : '' }}>Top-ups</option>
                <option value="deduction" {{ request('type') == 'deduction' ? 'selected' : '' }}>Deductions</option>
                <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refunds</option>
            </select>
        </form>
    </div>
    
    @if($transactions->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8f9fa, #f1f5f9);">
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Type</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Description</th>
                        <th style="padding: 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.25rem 1rem;">
                            <div style="font-weight: 600; color: #172030;">{{ $transaction->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">{{ $transaction->created_at->format('h:i A') }}</div>
                        </td>
                        <td style="padding: 1.25rem 1rem;">
                            @if($transaction->type === 'topup')
                                <span style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 0.35rem 0.85rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-arrow-up" style="margin-right: 0.25rem;"></i> Top Up
                                </span>
                            @elseif($transaction->type === 'deduction')
                                <span style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; padding: 0.35rem 0.85rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-arrow-down" style="margin-right: 0.25rem;"></i> Deduction
                                </span>
                            @else
                                <span style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; padding: 0.35rem 0.85rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-rotate-left" style="margin-right: 0.25rem;"></i> Refund
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1.25rem 1rem; color: #333; max-width: 300px;">
                            {{ $transaction->reference ?? 'N/A' }}
                        </td>
                        <td style="padding: 1.25rem 1rem; text-align: right;">
                            <span style="font-weight: 800; font-size: 1.1rem; color: {{ $transaction->type === 'deduction' ? '#dc2626' : '#059669' }};">
                                {{ $transaction->type === 'deduction' ? '-' : '+' }}₱{{ number_format($transaction->amount, 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            {{ $transactions->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 4rem 2rem; color: #6b7280;">
            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fa-solid fa-receipt" style="font-size: 2rem; color: #cbd5e1;"></i>
            </div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 0.5rem;">No Transactions Yet</h4>
            <p style="font-size: 0.9rem;">Your transaction history will appear here.</p>
        </div>
    @endif
</div>
@endsection