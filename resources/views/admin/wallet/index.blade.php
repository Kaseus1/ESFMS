@extends('layouts.admin')

@section('title', 'Wallet Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>

        </div>
        <a href="{{ route('admin.wallet.topup') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Top-up
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Balance -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Balance</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">₱{{ number_format($totalBalance, 2) }}</p>
                </div>
                <div class="flex-shrink-0 p-2.5 bg-blue-50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Top-ups -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Top-ups</p>
                    <p class="mt-2 text-2xl font-semibold text-green-600">₱{{ number_format($totalTopups, 2) }}</p>
                </div>
                <div class="flex-shrink-0 p-2.5 bg-green-50 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Spent</p>
                    <p class="mt-2 text-2xl font-semibold text-red-600">₱{{ number_format($totalSpent, 2) }}</p>
                </div>
                <div class="flex-shrink-0 p-2.5 bg-red-50 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Refunds -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Refunds</p>
                    <p class="mt-2 text-2xl font-semibold text-purple-600">₱{{ number_format($totalRefunds, 2) }}</p>
                </div>
                <div class="flex-shrink-0 p-2.5 bg-purple-50 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <form action="{{ route('admin.wallet.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
            </div>
            <div class="sm:w-40">
                <select name="role" class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="faculty" {{ request('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="guest" {{ request('role') == 'guest' ? 'selected' : '' }}>Guest</option>
                </select>
            </div>
            <div class="sm:w-40">
                <select name="balance_filter" class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    <option value="">All Balances</option>
                    <option value="positive" {{ request('balance_filter') == 'positive' ? 'selected' : '' }}>With Balance</option>
                    <option value="zero" {{ request('balance_filter') == 'zero' ? 'selected' : '' }}>Zero Balance</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Search
                </button>
                <a href="{{ route('admin.wallet.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-900">User Wallets</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                {{ $user->role === 'faculty' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                {{ $user->role === 'student' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                {{ $user->role === 'guest' ? 'bg-gray-50 text-gray-700 border border-gray-200' : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-base font-semibold {{ $user->wallet_balance > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                ₱{{ number_format($user->wallet_balance, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $user->wallet_transactions_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.wallet.user-transactions', $user) }}" 
                                   class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">View</a>
                                <button type="button" 
                                        onclick="openAdjustModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->wallet_balance }})"
                                        class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors">Adjust</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm text-gray-500">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
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
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" 
             @click="show = false"></div>
        
        <!-- Modal -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Adjust Wallet Balance</h3>
            </div>
            
            <!-- Content -->
            <div class="px-6 py-4 space-y-3">
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">User</span>
                    <span class="text-sm font-medium text-gray-900" x-text="userName"></span>
                </div>
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Current Balance</span>
                    <span class="text-sm font-semibold text-green-600">₱<span x-text="currentBalance.toFixed(2)"></span></span>
                </div>
            </div>
            
            <!-- Form -->
            <form method="POST" :action="`/admin/wallet/adjust/${userId}`" class="px-6 pb-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Type</label>
                        <select name="type" required class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                            <option value="add">Add Balance</option>
                            <option value="deduct">Deduct Balance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">₱</span>
                            <input type="number" name="amount" step="0.01" min="0.01" required 
                                   class="w-full pl-9 pr-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Reason</label>
                        <textarea name="reason" required rows="3"
                                  class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow resize-none"
                                  placeholder="Provide a reason for this adjustment..."></textarea>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" 
                            @click="show = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all">
                        Adjust Balance
                    </button>
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