@extends('layouts.admin')

@section('title', 'Export Data')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Export Data
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Export system data in various formats for reporting and analysis
            </p>
        </div>
    </div>

    <!-- Data Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $dataCounts['users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-calendar-check text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Reservations</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $dataCounts['reservations'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-building text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Facilities</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $dataCounts['facilities'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-user-clock text-orange-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Guest Requests</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $dataCounts['guest_requests'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Users Export -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fa-solid fa-users mr-2 text-blue-600"></i>
                    Export Users
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Export all user accounts with their information including roles and status.
                </p>
                
                <form action="{{ route('admin.export.data') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="users">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select name="format" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" required>
                                <option value="csv">CSV</option>
                                <option value="json">JSON</option>
                                <option value="xlsx" disabled>Excel (Coming Soon)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Records</label>
                            <div class="text-sm text-gray-500">{{ $dataCounts['users'] }} total</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" 
                                   name="date_from" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" 
                                   name="date_to" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-solid fa-download mr-2"></i>
                        Export Users
                    </button>
                </form>
            </div>
        </div>

        <!-- Reservations Export -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fa-solid fa-calendar-check mr-2 text-green-600"></i>
                    Export Reservations
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Export all facility reservations with details about users, facilities, and status.
                </p>
                
                <form action="{{ route('admin.export.data') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="reservations">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select name="format" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" required>
                                <option value="csv">CSV</option>
                                <option value="json">JSON</option>
                                <option value="xlsx" disabled>Excel (Coming Soon)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Records</label>
                            <div class="text-sm text-gray-500">{{ $dataCounts['reservations'] }} total</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" 
                                   name="date_from" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" 
                                   name="date_to" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fa-solid fa-download mr-2"></i>
                        Export Reservations
                    </button>
                </form>
            </div>
        </div>

        <!-- Facilities Export -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fa-solid fa-building mr-2 text-purple-600"></i>
                    Export Facilities
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Export all facilities with location details and reservation statistics.
                </p>
                
                <form action="{{ route('admin.export.data') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="facilities">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select name="format" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" required>
                                <option value="csv">CSV</option>
                                <option value="json">JSON</option>
                                <option value="xlsx" disabled>Excel (Coming Soon)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Records</label>
                            <div class="text-sm text-gray-500">{{ $dataCounts['facilities'] }} total</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" 
                                   name="date_from" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" 
                                   name="date_to" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <i class="fa-solid fa-download mr-2"></i>
                        Export Facilities
                    </button>
                </form>
            </div>
        </div>

        <!-- Guest Requests Export -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fa-solid fa-user-clock mr-2 text-orange-600"></i>
                    Export Guest Requests
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Export all guest registration requests and their approval status.
                </p>
                
                <form action="{{ route('admin.export.data') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="guest_requests">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select name="format" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" required>
                                <option value="csv">CSV</option>
                                <option value="json">JSON</option>
                                <option value="xlsx" disabled>Excel (Coming Soon)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Records</label>
                            <div class="text-sm text-gray-500">{{ $dataCounts['guest_requests'] }} total</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" 
                                   name="date_from" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" 
                                   name="date_to" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <i class="fa-solid fa-download mr-2"></i>
                        Export Guest Requests
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Export Options -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fa-solid fa-archive mr-2 text-gray-600"></i>
                Bulk Export
            </h3>
            <p class="text-sm text-gray-500 mb-6">
                Export all data at once with comprehensive reports for backup or analysis purposes.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.analytics.export', ['format' => 'csv']) }}" 
                   class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <i class="fa-solid fa-file-csv mr-2 text-green-600"></i>
                    Export All (CSV)
                </a>
                
                <a href="{{ route('admin.analytics.export', ['format' => 'json']) }}" 
                   class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <i class="fa-solid fa-file-code mr-2 text-blue-600"></i>
                    Export All (JSON)
                </a>
                
                <button disabled 
                        class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                    <i class="fa-solid fa-file-excel mr-2"></i>
                    Export All (Excel) - Coming Soon
                </button>
            </div>
        </div>
    </div>

    <!-- Export Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">
                    Export Instructions
                </h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>CSV files</strong> can be opened in Excel, Google Sheets, or any spreadsheet application</li>
                        <li><strong>JSON files</strong> are best for programmatic data processing or system integration</li>
                        <li>Leave date fields empty to export all records regardless of date</li>
                        <li>Specify date ranges to export only records within that period</li>
                        <li>Large exports may take a few moments to generate - please be patient</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection