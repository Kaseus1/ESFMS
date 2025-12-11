@extends('layouts.admin')

@section('title', 'User Wallet Transactions')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.wallet.index') }}" 
               class="inline-flex items-center px-3 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}'s Wallet</h1>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" 
                    onclick="openAdjustModal({{ $user->id }}, '{{ $user->name }}', {{ $user->wallet_balance }})"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Adjust Balance
            </button>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- User Avatar & Info -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $user->role === 'faculty' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $user->role === 'student' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $user->role === 'guest' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Current Balance -->
            <div class="text-center md:text-left">
                <p class="text-sm font-medium text-gray-500">Current Balance</p>
                <p class="text-2xl font-bold {{ $user->wallet_balance > 0 ? 'text-green-600' : 'text-gray-500' }}">
                    ₱{{ number_format($user->wallet_balance, 2) }}
                </p>
            </div>

            <!-- Total Top-ups -->
            <div class="text-center md:text-left">
                <p class="text-sm font-medium text-gray-500">Total Top-ups</p>
                <p class="text-2xl font-bold text-green-600">₱{{ number_format($totalTopups ?? 0, 2) }}</p>
            </div>

            <!-- Total Spent -->
            <div class="text-center md:text-left">
                <p class="text-sm font-medium text-gray-500">Total Spent</p>
                <p class="text-2xl font-bold text-red-600">₱{{ number_format($totalSpent ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Transaction History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $transaction->type === 'topup' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $transaction->type === 'deduction' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $transaction->type === 'refund' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $transaction->type === 'adjustment' ? 'bg-blue-100 text-blue-800' : '' }}">
                                @if($transaction->type === 'topup')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                @elseif($transaction->type === 'deduction')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                @elseif($transaction->type === 'refund')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                @endif
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $transaction->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-semibold 
                                {{ in_array($transaction->type, ['topup', 'refund']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($transaction->type, ['topup', 'refund']) ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            ₱{{ number_format($transaction->balance_after ?? 0, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            No transactions found for this user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Adjust Balance Modal -->
<div id="adjustModal" 
     x-data="{ show: false, userId: null, userName: '', currentBalance: 0 }"
     x-show="show"
     x-on:open-adjust-modal.window="show = true; userId = $event.detail.userId; userName = $event.detail.userName; currentBalance = $event.detail.currentBalance"
     x-on:keydown.escape.window="show = false"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Adjust Wallet Balance</h3>
            <p class="text-sm text-gray-600 mb-4">User: <strong x-text="userName"></strong></p>
            <p class="text-sm text-gray-600 mb-4">Current Balance: <strong>₱<span x-text="currentBalance.toFixed(2)"></span></strong></p>
            
            <form method="POST" x-bind:action="`/admin/wallet/adjust/${userId}`">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="add">Add Balance</option>
                            <option value="deduct">Deduct Balance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                        <textarea name="reason" required rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Reason for adjustment..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="show = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Adjust Balance</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAdjustModal(userId, userName, currentBalance) {
    window.dispatchEvent(new CustomEvent('open-adjust-modal', {
        detail: { userId, userName, currentBalance }
    }));
}
</script>
@endpush
@endsection
