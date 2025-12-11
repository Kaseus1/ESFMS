<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\FacultyDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GuestRegistrationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReservationController;
// Admin Controllers
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminFacilityController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\GuestManagementController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\GuestApprovalController;
use App\Http\Controllers\Admin\AdminWalletController;
// Faculty Controllers
use App\Http\Controllers\Faculty\FacultyReservationController;
use App\Http\Controllers\Faculty\FacultyFacilityController;
use App\Http\Controllers\Faculty\FacultyWalletController;
// Student Controllers
use App\Http\Controllers\Student\StudentReservationController;
use App\Http\Controllers\Student\StudentFacilityController;
use App\Http\Controllers\Student\StudentWalletController;
// Guest Controllers
use App\Http\Controllers\Guest\GuestReservationController;
use App\Http\Controllers\Guest\GuestFacilityController;
use App\Http\Controllers\Guest\GuestWalletController;
use App\Http\Controllers\GuestDashboardController;

use App\Http\Controllers\Api\FacilityAvailabilityController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'faculty' => redirect()->route('faculty.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'guest' => redirect()->route('guest.dashboard'),
            default => redirect()->route('login')
        };
    }
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes (Role-Based Login Pages)
|--------------------------------------------------------------------------
*/
Route::get('/system/authenticate', function() {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->check()) {
        abort(403, 'Unauthorized access');
    }
    return view('auth.admin-login');
})->name('admin.login');

Route::get('/faculty/login', function() {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.faculty-login');
})->name('faculty.login');

Route::get('/student/login', function() {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.student-login');
})->name('student.login');

Route::get('/guest/login', function() {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.guest-login');
})->name('guest.login');

/*
|--------------------------------------------------------------------------
| Guest Registration Routes
|--------------------------------------------------------------------------
*/
Route::get('/guest/register', [GuestRegistrationController::class, 'show'])
    ->middleware('guest')
    ->name('guest.register');

Route::post('/guest/register', [GuestRegistrationController::class, 'store'])
    ->middleware('guest')
    ->name('guest.register.store');

/*
|--------------------------------------------------------------------------
| Faculty & Student Registration Routes
|--------------------------------------------------------------------------
*/
Route::get('/faculty/register', function() {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.faculty-register');
})->middleware('guest')->name('faculty.register');

Route::post('/faculty/register', [RegisteredUserController::class, 'storeFaculty'])
    ->middleware('guest')
    ->name('faculty.register.store');

Route::get('/student/register', function() {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.student-register');
})->middleware('guest')->name('student.register');

Route::post('/student/register', [RegisteredUserController::class, 'storeStudent'])
    ->middleware('guest')
    ->name('student.register.store');

Route::get('/registration/pending', [RegisteredUserController::class, 'pending'])
    ->middleware('guest')
    ->name('registration.pending');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/events', [AdminDashboardController::class, 'calendarEvents'])->name('dashboard.events');
    
    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // WALLET MANAGEMENT ROUTES
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [AdminWalletController::class, 'index'])->name('index');
        Route::get('/topup', [AdminWalletController::class, 'topupForm'])->name('topup');
        Route::post('/topup', [AdminWalletController::class, 'topup'])->name('topup.store');
        Route::get('/transactions', [AdminWalletController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/{user}', [AdminWalletController::class, 'userTransactions'])->name('user-transactions');
        Route::post('/adjust/{user}', [AdminWalletController::class, 'adjustBalance'])->name('adjust'); // ADDED THIS LINE
    });
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/approve', [UserManagementController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [UserManagementController::class, 'reject'])->name('reject');
        Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('resetPassword');
        Route::post('/bulk-approve', [UserManagementController::class, 'bulkApprove'])->name('bulkApprove');
        Route::post('/bulk-update', [UserManagementController::class, 'bulkUpdate'])->name('bulkUpdate');
        Route::delete('/bulk-delete', [UserManagementController::class, 'bulkDelete'])->name('bulkDelete');
    });
    
    // Facilities
    Route::get('facilities/ajax', [AdminFacilityController::class, 'ajax'])->name('facilities.ajax');
    Route::resource('facilities', AdminFacilityController::class);  
    Route::post('facilities/{id}/restore', [AdminFacilityController::class, 'restore'])
    ->name('facilities.restore');
    
    // Guest Management
    Route::controller(GuestManagementController::class)->group(function () {
        Route::get('guests', 'index')->name('guests.index');
        Route::get('guests/{guest}', 'show')->name('guests.show');
        Route::get('guests/{guest}/edit', 'edit')->name('guests.edit');
        Route::put('/{guest}', 'update')->name('update');
        Route::post('guests/{guest}/approve', 'approve')->name('guests.approve');
        Route::post('guests/{guest}/reject', 'reject')->name('guests.reject');
        Route::post('guests/{guest}/reactivate', 'reactivate')->name('guests.reactivate');
        Route::post('guests/{guest}/suspend', 'suspend')->name('guests.suspend');
        Route::patch('guests/{guest}/notes', 'updateNotes')->name('guests.updateNotes');
        Route::delete('guests/{guest}', 'destroy')->name('guests.destroy');
        Route::post('guests/bulk-approve', 'bulkApprove')->name('guests.bulkApprove');
        Route::delete('guests/bulk-delete', 'bulkDelete')->name('guests.bulkDelete');
        Route::get('guests/export', 'export')->name('guests.export');
    });
    
    // Reservations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [AdminReservationController::class, 'index'])->name('index');
        Route::resource('admin/reservations', AdminReservationController::class, ['as' => 'admin']);
        Route::get('/create', [AdminReservationController::class, 'create'])->name('create');
        Route::post('/', [AdminReservationController::class, 'store'])->name('store');
        Route::post('/check-availability', [AdminReservationController::class, 'checkAvailability'])->name('checkAvailability');
        Route::get('/{reservation}', [AdminReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [AdminReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [AdminReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [AdminReservationController::class, 'destroy'])->name('destroy');
        Route::get('/events', [AdminReservationController::class, 'events'])->name('events');
        Route::patch('/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/approve', [AdminReservationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AdminReservationController::class, 'reject'])->name('reject');
        Route::post('/{id}/refund', [AdminReservationController::class, 'refund'])->name('refund');
    });
    
    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AdminAnalyticsController::class, 'index'])->name('index');
        Route::get('/export', [AdminAnalyticsController::class, 'export'])->name('export');
    });
    
    // Export Data
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/', [AdminExportController::class, 'index'])->name('index');
        Route::post('/data', [AdminExportController::class, 'export'])->name('data');
        Route::get('/users', [AdminExportController::class, 'exportUsers'])->name('users');
        Route::get('/facilities', [AdminExportController::class, 'exportFacilities'])->name('facilities');
        Route::get('/reservations', [AdminExportController::class, 'exportReservations'])->name('reservations');
        Route::get('/guests', [AdminExportController::class, 'exportGuestRequests'])->name('guests');
    });
});

/*
|--------------------------------------------------------------------------
| Faculty Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('dashboard', [FacultyDashboardController::class, 'index'])->name('dashboard');
    
    // Wallet
    Route::get('wallet', [FacultyWalletController::class, 'index'])->name('wallet.index');
    
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [FacultyReservationController::class, 'index'])->name('index');
        Route::get('/create', [FacultyReservationController::class, 'create'])->name('create');
        Route::post('/', [FacultyReservationController::class, 'store'])->name('store');
        Route::post('/check-availability', [FacultyReservationController::class, 'checkAvailability'])->name('checkAvailability');
        Route::get('/{reservation}', [FacultyReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [FacultyReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [FacultyReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [FacultyReservationController::class, 'destroy'])->name('destroy');
        Route::post('/{reservation}/cancel', [FacultyReservationController::class, 'cancel'])->name('cancel');
    });
    
    // Facilities with AJAX endpoint
    Route::get('facilities/ajax', [FacultyFacilityController::class, 'ajax'])->name('facilities.ajax');
    Route::get('facilities', [FacultyFacilityController::class, 'index'])->name('facilities.index');
    Route::get('facilities/{facility}', [FacultyFacilityController::class, 'show'])->name('facilities.show');
    
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Wallet
    Route::get('wallet', [StudentWalletController::class, 'index'])->name('wallet.index');
    
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [StudentReservationController::class, 'index'])->name('index');
        Route::get('/create', [StudentReservationController::class, 'create'])->name('create');
        Route::post('/', [StudentReservationController::class, 'store'])->name('store');
        Route::post('/check-availability', [StudentReservationController::class, 'checkAvailability'])->name('checkAvailability');
        Route::get('/{reservation}', [StudentReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [StudentReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [StudentReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [StudentReservationController::class, 'destroy'])->name('destroy');
        Route::patch('/{reservation}/cancel', [StudentReservationController::class, 'cancel'])->name('cancel');
    });
    
    // Facilities with AJAX endpoint
    Route::get('facilities/ajax', [StudentFacilityController::class, 'ajax'])->name('facilities.ajax');
    Route::get('facilities', [StudentFacilityController::class, 'index'])->name('facilities.index');
    Route::get('facilities/{facility}', [StudentFacilityController::class, 'show'])->name('facilities.show');
    
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guest'])->group(function () {
    Route::get('/guest/status', function () {
        if (auth()->user()->status !== 'approved') {
            return view('guest.registration');
        }
        return redirect()->route('guest.dashboard');
    })->name('guest.status');
});

Route::middleware(['auth', 'guest.approved', 'role:guest'])->prefix('guest')->name('guest.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [GuestDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [GuestDashboardController::class, 'data'])->name('dashboard.data');
    
    // Wallet
    Route::get('/wallet', [GuestWalletController::class, 'index'])->name('wallet.index');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Facilities Routes - AJAX route MUST come before parameterized routes
    Route::get('/facilities/ajax', [GuestFacilityController::class, 'ajax'])->name('facilities.ajax');
    Route::get('/facilities', [GuestFacilityController::class, 'index'])->name('facilities.index');
    Route::get('/facilities/{facility}', [GuestFacilityController::class, 'show'])->name('facilities.show');
    
    // Reservations Routes - Specific routes BEFORE parameterized routes
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [GuestReservationController::class, 'index'])->name('index');
        Route::get('/create', [GuestReservationController::class, 'create'])->name('create');
        Route::post('/', [GuestReservationController::class, 'store'])->name('store');
        Route::post('/check-availability', [GuestReservationController::class, 'checkAvailability'])->name('checkAvailability');
        
        // Parameterized routes LAST
        Route::get('/{reservation}', [GuestReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [GuestReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [GuestReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [GuestReservationController::class, 'destroy'])->name('destroy');
        Route::post('/{reservation}/cancel', [GuestReservationController::class, 'cancel'])->name('cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Reservations Export (Admin Only)
|--------------------------------------------------------------------------
*/
Route::get('/reservations/export', [ReservationController::class, 'exportExcel'])
    ->name('reservations.export')
    ->middleware(['auth', 'role:admin']);

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze/Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';