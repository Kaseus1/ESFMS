@extends('layouts.admin')

@section('title', 'Wallet Top-up')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Wallet Top-up</h1>
            <p class="mt-1 text-sm text-gray-500">Add funds to a user's wallet balance</p>
        </div>
        <a href="{{ route('admin.wallet.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <!-- Top-up Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            @if($errors->any())
                <div class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-red-800">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.wallet.topup.store') }}" class="space-y-6">
                @csrf
                
                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-900 mb-2">
                        User <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" 
                            id="user_id" 
                            required 
                            class="w-full px-3.5 py-2.5 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) • ₱{{ number_format($user->wallet_balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-900 mb-2">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                        <input type="number" 
                               name="amount" 
                               id="amount"
                               step="0.01" 
                               min="1" 
                               max="100000" 
                               value="{{ old('amount') }}" 
                               required 
                               placeholder="0.00"
                               class="w-full pl-9 pr-3.5 py-2.5 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Min: ₱1.00 • Max: ₱100,000.00</p>
                </div>

                <!-- Reference/Notes -->
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-900 mb-2">
                        Reference/Notes
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea name="reference" 
                              id="reference"
                              rows="3" 
                              placeholder="Add a reference or note for this transaction..."
                              class="w-full px-3.5 py-2.5 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow resize-none">{{ old('reference') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.wallet.index') }}" 
                       class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Process Top-up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection