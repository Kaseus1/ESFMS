@extends('layouts.admin')

@section('title', 'Wallet Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#172030]">Wallet Management</h1>
            <p class="text-[#333C4D] mt-2 opacity-75">Monitor user balances, top-ups, and transactions</p>
        </div>
        <div class="flex gap-3">
<a href="{{ route('admin.wallet.topup') }}"
class="px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center shadow-md transition-all justify-center w-full md:w-auto">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
</svg>
Add Top-up
</a>
</div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#002366] hover:shadow-xl transition h-full">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#333C4D] uppercase truncate">Total Balance</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2 break-words">₱{{ number_format($totalBalance, 2) }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Current user holdings</p>
                </div>
                <div class="flex-shrink-0 bg-[#002366] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#10B981] hover:shadow-xl transition h-full">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#333C4D] uppercase truncate">Total Top-ups</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2 break-words">₱{{ number_format($totalTopups, 2) }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Lifetime deposits</p>
                </div>
                <div class="flex-shrink-0 bg-[#10B981] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#EF4444] hover:shadow-xl transition h-full">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#333C4D] uppercase truncate">Total Spent</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2 break-words">₱{{ number_format($totalSpent, 2) }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Facility payments</p>
                </div>
                <div class="flex-shrink-0 bg-[#EF4444] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#8B5CF6] hover:shadow-xl transition h-full">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#333C4D] uppercase truncate">Total Refunds</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2 break-words">₱{{ number_format($totalRefunds, 2) }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Returned credits</p>
                </div>
                <div class="flex-shrink-0 bg-[#8B5CF6] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#8B5CF6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#FFFFFF] rounded-lg shadow-lg mb-6 p-6">
        <form action="{{ route('admin.wallet.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="w-full md:flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search user name or email..."
                       class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent text-[#172030]">
            </div>

            <div class="w-full md:w-48">
                <select name="role" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-[#FFFFFF] text-[#172030]">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="faculty" {{ request('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="guest" {{ request('role') == 'guest' ? 'selected' : '' }}>Guest</option>
                </select>
            </div>

            <div class="w-full md:w-48">
                <select name="balance_filter" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-[#FFFFFF] text-[#172030]">
                    <option value="">All Balances</option>
                    <option value="positive" {{ request('balance_filter') == 'positive' ? 'selected' : '' }}>With Balance</option>
                    <option value="zero" {{ request('balance_filter') == 'zero' ? 'selected' : '' }}>Zero Balance</option>
                </select>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center justify-center font-medium transition shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                
                <a href="{{ route('admin.wallet.index') }}" 
                   class="flex-1 md:flex-none px-6 py-2 bg-[#333C4D] bg-opacity-20 text-[#333C4D] rounded-lg hover:bg-[#333C4D] hover:bg-opacity-30 flex items-center justify-center font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-10 bg-[#F8F9FA]">
            <h2 class="text-base font-bold text-[#172030]">User Wallets</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
                <thead class="bg-[#F8F9FA]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#333C4D] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#333C4D] divide-opacity-10 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#F8F9FA] transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-[#002366] to-[#00285C] flex items-center justify-center text-white font-bold shadow-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-[#172030]">{{ $user->name }}</div>
                                        <div class="text-xs text-[#333C4D] opacity-75">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full shadow-sm border
                                    {{ $user->role === 'admin' ? 'bg-[#8B5CF6] bg-opacity-10 text-[#8B5CF6] border-[#8B5CF6] border-opacity-20' : '' }}
                                    {{ $user->role === 'faculty' ? 'bg-[#172030] bg-opacity-10 text-[#172030] border-[#172030] border-opacity-20' : '' }}
                                    {{ $user->role === 'student' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981] border-[#10B981] border-opacity-20' : '' }}
                                    {{ $user->role === 'guest' ? 'bg-[#333C4D] bg-opacity-10 text-[#333C4D] border-[#333C4D] border-opacity-20' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold {{ $user->wallet_balance > 0 ? 'text-[#10B981]' : 'text-[#333C4D] opacity-50' }}">
                                    ₱{{ number_format($user->wallet_balance, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-[#333C4D] bg-[#F3F4F6] px-2 py-1 rounded-md">
                                    {{ $user->wallet_transactions_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.wallet.user-transactions', $user) }}" 
                                       class="text-[#002366] hover:text-[#001A4A] font-medium transition">View</a>
                                    
                                    <button type="button" 
                                            onclick="openAdjustModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->wallet_balance }})"
                                            class="text-[#10B981] hover:text-[#059669] font-medium transition">Adjust</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-[#333C4D] opacity-50">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium">No users found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="px-6 py-4 bg-[#FFFFFF] border-t border-[#333C4D] border-opacity-10">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<div id="adjustModal" 
     x-data="{ show: false, userId: null, userName: '', currentBalance: 0 }"
     x-show="show"
     x-on:open-adjust-modal.window="show = true; userId = $event.detail.userId; userName = $event.detail.userName; currentBalance = $event.detail.currentBalance"
     x-on:keydown.escape.window="show = false"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-[#172030]/50 backdrop-blur-sm transition-opacity" 
             @click="show = false"></div>
        
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 transform translate-y-4 sm:translate-y-0"
             x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 transform translate-y-4 sm:translate-y-0"
             class="relative bg-white rounded-xl shadow-2xl max-w-md w-full border border-[#333C4D] border-opacity-10">
            
            <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-10 bg-[#F8F9FA] rounded-t-xl">
                <h3 class="text-lg font-bold text-[#172030]">Adjust Wallet Balance</h3>
            </div>
            
            <div class="px-6 py-4 space-y-4">
                <div class="bg-[#F8F9FA] rounded-lg p-4 border border-[#333C4D] border-opacity-5">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs uppercase font-bold text-[#333C4D] opacity-60">User Account</span>
                        <span class="text-sm font-semibold text-[#172030]" x-text="userName"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs uppercase font-bold text-[#333C4D] opacity-60">Current Balance</span>
                        <span class="text-lg font-bold text-[#10B981]">₱<span x-text="currentBalance.toFixed(2)"></span></span>
                    </div>
                </div>
            </div>
            
            <form method="POST" :action="`/admin/wallet/adjust/${userId}`" class="px-6 pb-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Action Type</label>
                        <select name="type" required class="w-full px-4 py-2 text-sm border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] text-[#172030]">
                            <option value="add">Add Balance (Credit)</option>
                            <option value="deduct">Deduct Balance (Debit)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#333C4D] text-sm font-bold">₱</span>
                            <input type="number" name="amount" step="0.01" min="0.01" required 
                                   class="w-full pl-8 pr-4 py-2 text-sm border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] text-[#172030]"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Reason</label>
                        <textarea name="reason" required rows="3"
                                  class="w-full px-4 py-2 text-sm border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] text-[#172030] resize-none"
                                  placeholder="Why are you adjusting this balance?"></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-[#333C4D] border-opacity-10">
                    <button type="button" 
                            @click="show = false" 
                            class="px-4 py-2 text-sm font-medium text-[#333C4D] bg-white border border-[#333C4D] border-opacity-20 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-[#002366] rounded-lg hover:bg-[#001A4A] shadow-md transition-all">
                        Confirm Adjustment
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