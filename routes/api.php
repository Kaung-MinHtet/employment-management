<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {

    // Company routes
    Route::get('company', [CompanyController::class, 'index']);
    Route::post('company', [CompanyController::class, 'store']);
    Route::get('company/{id}', [CompanyController::class, 'show']);
    Route::put('company/{id}', [CompanyController::class, 'update']);
    Route::delete('company/{id}', [CompanyController::class, 'destroy']);

    // Employee routes
    Route::get('employee', [EmployeeController::class, 'index']);
    Route::post('employee', [EmployeeController::class, 'store']);
    Route::get('employee/{id}', [EmployeeController::class, 'show']);
    Route::put('employee/{id}', [EmployeeController::class, 'update']);
    Route::delete('employee/{id}', [EmployeeController::class, 'destroy']);
});
