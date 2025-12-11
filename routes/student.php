<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->group(function () {  // ← Add 'auth' here

    // 🎓 Student Dashboard
    Route::get('/dashboard/student', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');
});