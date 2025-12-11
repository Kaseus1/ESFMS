<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\PaymentStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// PayMongo webhook endpoint
Route::post('/payments/webhook', [PaymentWebhookController::class, 'handle'])
    ->name('payments.webhook');

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Payment status check
    Route::get('/reservations/{reservationId}/payment-status', [PaymentStatusController::class, 'checkStatus'])
        ->name('reservations.payment-status');
});

// Facility availability API
Route::get('/facilities/{facility}/availability', function ($facility) {
    // Return available timeslots for the facility
    // This would be implemented based on your specific requirements
    return response()->json([
        'message' => 'Availability endpoint - implement as needed'
    ]);
})->name('facilities.availability');