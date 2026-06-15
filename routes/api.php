<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrackingController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    
    Route::get('/referrals/assigned', [TrackingController::class, 'assignedReferrals']);
    Route::post('/referrals/{referral}/track', [TrackingController::class, 'updateLocation']);
});
