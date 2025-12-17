@extends('layouts.admin')

@section('title', 'Wallet Top-up')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#172030]">Wallet Top-up</h1>
            <p class="mt-2 text-[#333C4D] opacity-75">Add funds to a user's wallet balance securely.</p>
        </div>
        {{-- Back Button: Secondary Style (White with Muted Border) --}}
        <a href="{{ route('admin.wallet.index') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-[#333C4D] bg-white border border-[#333C4D] border-opacity-20 rounded-lg hover:bg-[#333C4D] hover:bg-opacity-5 hover:text-[#172030] transition-all shadow-sm w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Wallet
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-xl shadow-xl border border-[#333C4D] border-opacity-10 overflow-hidden">
        
        {{-- Card Header Decoration (Brand Blue) --}}
        <div class="h-2 bg-gradient-to-r from-[#002366] to-[#001A4A]"></div>

        <div class="p-6 sm:p-10">
            {{-- Error Handling --}}
            @if($errors->any())
                <div class="mb-8 flex items-start gap-4 p-4 bg-red-50 border-l-4 border-[#EF4444] rounded-r-lg">
                    <svg class="w-6 h-6 text-[#EF4444] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-red-800">Validation Error</h3>
                        <p class="text-sm text-red-700 mt-1">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.wallet.topup.store') }}" class="space-y-8">
                @csrf
                
                {{-- User Selection --}}
                <div class="w-full">
                    <label for="user_id" class="block text-sm font-bold text-[#172030] mb-2 uppercase tracking-wide">
                        Select User <span class="text-[#EF4444]">*</span>
                    </label>
                    <div class="relative group">
                        <select name="user_id" 
                                id="user_id" 
                                required 
                                class="w-full appearance-none px-4 py-3.5 text-[#172030] bg-gray-50 border border-[#333C4D] border-opacity-20 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#002366] focus:border-transparent transition-all shadow-sm text-base truncate pr-10 cursor-pointer hover:border-opacity-40">
                            <option value="" disabled selected>Choose a recipient...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ Str::limit($user->name, 30) }} ({{ $user->email }}) — Current: ₱{{ number_format($user->wallet_balance, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#333C4D] opacity-50 group-hover:opacity-100 transition-opacity">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Amount Input --}}
                <div>
                    <label for="amount" class="block text-sm font-bold text-[#172030] mb-2 uppercase tracking-wide">
                        Top-up Amount <span class="text-[#EF4444]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-[#172030] font-bold text-lg opacity-40 group-focus-within:opacity-100 group-focus-within:text-[#002366] transition-all">₱</span>
                        </div>
                        <input type="number" 
                               name="amount" 
                               id="amount"
                               step="0.01" 
                               min="1" 
                               max="100000" 
                               value="{{ old('amount') }}" 
                               required 
                               placeholder="0.00"
                               class="w-full pl-10 pr-4 py-3.5 text-lg font-semibold text-[#172030] bg-gray-50 border border-[#333C4D] border-opacity-20 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#002366] focus:border-transparent transition-all shadow-sm placeholder-gray-400">
                    </div>
                    <div class="mt-2 flex justify-between items-center text-xs text-[#333C4D] opacity-60">
                        <span>Minimum: ₱1.00</span>
                        <span>Maximum: ₱100,000.00</span>
                    </div>
                </div>

                {{-- Reference/Notes --}}
                <div>
                    <label for="reference" class="block text-sm font-bold text-[#172030] mb-2 uppercase tracking-wide">
                        Reference / Notes <span class="normal-case font-normal text-[#333C4D] opacity-50 ml-1">(Optional)</span>
                    </label>
                    <textarea name="reference" 
                              id="reference"
                              rows="3" 
                              placeholder="Add a reference ID, receipt number, or note for this transaction..."
                              class="w-full px-4 py-3 text-[#172030] bg-gray-50 border border-[#333C4D] border-opacity-20 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#002366] focus:border-transparent transition-all shadow-sm resize-none text-base placeholder-gray-400">{{ old('reference') }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-4 pt-8 border-t border-[#333C4D] border-opacity-10">
                    {{-- Cancel Button: Secondary Style (White with Muted Border) --}}
                    <a href="{{ route('admin.wallet.index') }}" 
                       class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-[#333C4D] bg-white border border-[#333C4D] border-opacity-20 rounded-lg hover:bg-[#333C4D] hover:bg-opacity-5 hover:text-[#172030] transition-colors text-center shadow-sm">
                        Cancel
                    </a>
                    
                    {{-- Process Button: Primary Brand Blue (Strict #002366) --}}
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-[#002366] rounded-lg hover:bg-[#001A4A] focus:ring-4 focus:ring-[#002366]/30 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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