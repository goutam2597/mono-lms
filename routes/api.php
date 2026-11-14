<?php

use App\Http\Controllers\Api\V1\AdminAuthController;
use App\Http\Controllers\Api\V1\SmsGatewayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile & Device API (v1)
|--------------------------------------------------------------------------
| All routes are prefixed with api/v1.
*/

Route::get('/ping', fn () => response()->json(['success' => true, 'data' => ['pong' => true]]));

/* ------------------------- Admin Auth for Gateway App ------------------- */
Route::prefix('admin/auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->name('api.v1.admin.auth.login');

    Route::middleware('admin.api.auth')->group(function () {
        Route::get('/me', [AdminAuthController::class, 'me'])->name('api.v1.admin.auth.me');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('api.v1.admin.auth.logout');
        Route::post('/register-device', [AdminAuthController::class, 'registerDevice'])->name('api.v1.admin.auth.register-device');
    });
});

/* ------------------------- Self-Hosted SMS Gateway Device API ----------- */
Route::prefix('sms-gateway')->middleware('gateway.auth')->group(function () {
    Route::get('/status', [SmsGatewayController::class, 'status'])->name('api.v1.sms-gateway.status');
    Route::post('/heartbeat', [SmsGatewayController::class, 'heartbeat'])->name('api.v1.sms-gateway.heartbeat');
    Route::post('/sms/claim', [SmsGatewayController::class, 'claim'])->name('api.v1.sms-gateway.claim');
    Route::post('/sms/incoming', [SmsGatewayController::class, 'incoming'])->name('api.v1.sms-gateway.incoming');
    Route::post('/sms/{id}/report', [SmsGatewayController::class, 'report'])->name('api.v1.sms-gateway.report');
});
