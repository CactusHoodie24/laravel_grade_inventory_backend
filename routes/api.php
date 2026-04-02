<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working'
    ]);
});

Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);
Route::put('/items/{id}', [ItemController::class, 'update']);
Route::delete('/items/{id}', [ItemController::class, 'destroy']);
Route::post('/items/{id}/add-stock', [ItemController::class, 'addStock']);
Route::post('/items/{id}/remove-stock', [ItemController::class, 'removeStock']);
Route::middleware(['auth:sanctum', 'role:clerk'])->group(function () {
    Route::post('/items/{id}/request', [ItemController::class, 'requestStock']);
});
Route::middleware(['auth:sanctum', 'role:section_head'])->group(function () {

    Route::post('/items', [ItemController::class, 'store']);
    Route::post('/requests/{id}/approve', [ItemController::class, 'approve']);
    Route::post('/requests/{id}/reject', [ItemController::class, 'reject']);

});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/reports/stock', [ReportController::class, 'stockSummary']);
    Route::get('/reports/usage', [ReportController::class, 'stockUsage']);
    Route::get('/reports/transactions', [ReportController::class, 'transactions']);
    Route::get('/reports/requests', [ReportController::class, 'requestStats']);

});