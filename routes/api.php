<?php

use Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttandanceController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Attendance routes
    Route::get('/attendances', [AttendanceController::class, 'index']);
    Route::post('/attendances', [AttendanceController::class, 'store']);
    
    // Employee routes
    Route::get('/profile', [EmployeeController::class, 'show']);
    Route::put('/profile', [EmployeeController::class, 'update']);
    
    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::get('/attendances/late', [AttendanceController::class, 'getLateAttendances']);
    });
});

// Admin only routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // ... route lain yang sudah ada ...
    
    // User management routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
});
