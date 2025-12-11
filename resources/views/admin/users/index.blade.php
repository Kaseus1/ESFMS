@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
<div class="container mx-auto px-4 py-8">
<!-- Header -->

<div class="flex gap-3">
<a href="{{ route('admin.guests.index') }}" 
class="px-4 py-2 bg-[#172030] text-[#FFFFFF] rounded-lg hover:bg-[#1D2636] flex items-center">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
</svg>
Manage Guests
</a>
</div>
</div>
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-4 p-4 bg-[#10B981] bg-opacity-10 border border-[#10B981] border-opacity-20 rounded-lg text-[#10B981] flex items-center">
<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
</svg>
{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 p-4 bg-[#EF4444] bg-opacity-10 border border-[#EF4444] border-opacity-20 rounded-lg text-[#EF4444] flex items-center">
<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
</svg>
{{ session('error') }}
</div>
@endif
<!-- Statistics Cards (Excluding Guests) -->
@php
$totalUsers = $users->total();
$admins = \App\Models\User::where('role', 'admin')->count();
$faculty = \App\Models\User::where('role', 'faculty')->count();
$students = \App\Models\User::where('role', 'student')->count();
@endphp
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
<div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#002366] hover:shadow-xl transition">
<div class="flex items-center justify-between">
<div>
<p class="text-sm font-medium text-[#333C4D] uppercase">Total Users</p>
<p class="text-3xl font-bold text-[#172030] mt-2">{{ $totalUsers }}</p>
<p class="text-xs text-[#333C4D] mt-1 opacity-75">Internal users only</p>
</div>
<div class="bg-[#002366] bg-opacity-10 rounded-lg p-3">
<svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
</svg>
</div>
</div>
</div>
<div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#00285C] hover:shadow-xl transition">
<div class="flex items-center justify-between">
<div>
<p class="text-sm font-medium text-[#333C4D] uppercase">Admins</p>
<p class="text-3xl font-bold text-[#172030] mt-2">{{ $admins }}</p>
<p class="text-xs text-[#333C4D] mt-1 opacity-75">System administrators</p>
</div>
<div class="bg-[#00285C] bg-opacity-10 rounded-lg p-3">
<svg class="w-8 h-8 text-[#00285C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
</svg>
</div>
</div>
</div>
<div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#172030] hover:shadow-xl transition">
<div class="flex items-center justify-between">
<div>
<p class="text-sm font-medium text-[#333C4D] uppercase">Faculty</p>
<p class="text-3xl font-bold text-[#172030] mt-2">{{ $faculty }}</p>
<p class="text-xs text-[#333C4D] mt-1 opacity-75">Teaching staff</p>
</div>
<div class="bg-[#172030] bg-opacity-10 rounded-lg p-3">
<svg class="w-8 h-8 text-[#172030]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
</svg>
</div>
</div>
</div>
<div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#1D2636] hover:shadow-xl transition">
<div class="flex items-center justify-between">
<div>
<p class="text-sm font-medium text-[#333C4D] uppercase">Students</p>
<p class="text-3xl font-bold text-[#172030] mt-2">{{ $students }}</p>
<p class="text-xs text-[#333C4D] mt-1 opacity-75">Enrolled students</p>
</div>
<div class="bg-[#1D2636] bg-opacity-10 rounded-lg p-3">
<svg class="w-8 h-8 text-[#1D2636]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
</svg>
</div>
</div>
</div>
</div>
<!-- Search and Filter -->
<div class="bg-[#FFFFFF] rounded-lg shadow-lg mb-6 p-6">
<form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
<div class="flex-1 min-w-[300px]">
<div class="relative">
<svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
</svg>
<input type="text" 
name="search" 
value="{{ request('search') }}"
placeholder="Search by name or email..."
class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent">
</div>
</div>
<select name="role" class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-[#FFFFFF]">
<option value="">All Roles</option>
<option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
<option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
<option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
</select>
<select name="status" class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-[#FFFFFF]">
<option value="">All Status</option>
<option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
<option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
<option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
</select>
<button type="submit" class="px-6 py-2 bg-[#002366] text-[#FFFFFF] rounded-lg hover:bg-[#001A4A] flex items-center font-medium transition">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
</svg>
Search
</button>
@if(request('search') || request('role') || request('status'))
<a href="{{ route('admin.users.index') }}" 
class="px-6 py-2 bg-[#333C4D] bg-opacity-20 text-[#333C4D] rounded-lg hover:bg-[#333C4D] hover:bg-opacity-30 flex items-center font-medium transition">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
</svg>
Clear
</a>
@endif
</form>
</div>
<!-- Users Table -->
<div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
@if($users->isEmpty())
<div class="p-12 text-center">
<svg class="mx-auto h-16 w-16 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
</svg>
<h3 class="mt-4 text-lg font-medium text-[#172030]">No users found</h3>
<p class="mt-2 text-sm text-[#333C4D] opacity-75">Try adjusting your search or filter criteria.</p>
</div>
@else
<div class="overflow-x-auto">
<table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
<thead class="bg-[#F8F9FA]">
<tr>
<th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">User</th>
<th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Email</th>
<th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Role</th>
<th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Status</th>
<th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Joined</th>
<th class="px-6 py-4 text-center text-xs font-bold text-[#333C4D] uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-[#FFFFFF] divide-y divide-[#333C4D] divide-opacity-10">
@foreach($users as $user)
<tr class="hover:bg-[#F8F9FA] transition">
<td class="px-6 py-4 whitespace-nowrap">
<div class="flex items-center">
<div class="flex-shrink-0 h-11 w-11">
<div class="h-11 w-11 rounded-full bg-gradient-to-br from-[#002366] to-[#00285C] flex items-center justify-center text-white font-bold text-lg shadow-md">
{{ strtoupper(substr($user->name, 0, 1)) }}
</div>
</div>
<div class="ml-4">
<div class="text-sm font-semibold text-[#172030]">{{ $user->name }}</div>
<div class="text-xs text-[#333C4D] opacity-75">ID: {{ $user->id }}</div>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<div class="text-sm text-[#172030]">{{ $user->email }}</div>
@if($user->email_verified_at)
<div class="text-xs text-[#10B981] flex items-center mt-1">
<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
</svg>
Verified
</div>
@else
<div class="text-xs text-[#333C4D] opacity-75 mt-1">Not verified</div>
@endif
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="px-3 py-1 inline-flex text-xs font-bold rounded-full shadow-sm
{{ $user->role === 'admin' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981] border border-[#10B981] border-opacity-20' : 
($user->role === 'faculty' ? 'bg-[#172030] bg-opacity-10 text-[#172030] border border-[#172030] border-opacity-20' : 
'bg-[#1D2636] bg-opacity-10 text-[#1D2636] border border-[#1D2636] border-opacity-20') }}">
{{ ucfirst($user->role) }}
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="px-3 py-1 inline-flex text-xs font-bold rounded-full shadow-sm
{{ $user->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981] border border-[#10B981] border-opacity-20' : 
($user->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B] border border-[#F59E0B] border-opacity-20' : 
'bg-[#EF4444] bg-opacity-10 text-[#EF4444] border border-[#EF4444] border-opacity-20') }}">
{{ ucfirst($user->status) }}
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<div class="text-sm text-[#172030]">{{ $user->created_at->format('M d, Y') }}</div>
<div class="text-xs text-[#333C4D] opacity-75">{{ $user->created_at->diffForHumans() }}</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
<div class="flex items-center justify-center gap-3">
<a href="{{ route('admin.users.show', $user) }}" class="text-[#002366] hover:text-[#001A4A] font-medium">View</a>
<a href="{{ route('admin.users.edit', $user) }}" class="text-[#172030] hover:text-[#1D2636] font-medium">Edit</a>
@if($user->id !== auth()->id())
<form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
onsubmit="return confirm('Are you sure you want to delete this user?');">
@csrf
@method('DELETE')
<button type="submit" class="text-[#EF4444] hover:text-[#DC2626] font-medium">Delete</button>
</form>
@endif
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endif
</div>
<!-- Pagination -->
<div class="mt-6">
{{ $users->links() }}
</div>
</div>
@endsection