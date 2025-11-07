<?php

use App\Http\Controllers\Api\AntrianController;
use App\Http\Controllers\Api\LoketController;
use Illuminate\Support\Facades\Route;

// Loket routes
Route::apiResource('lokets', LoketController::class);

// Antrian routes
Route::prefix('antrians')->group(function () {
    Route::post('/ambil', [AntrianController::class, 'ambil']);
    Route::patch('/{antrian}/status', [AntrianController::class, 'updateStatus']);
    Route::get('/current', [AntrianController::class, 'current']);
    Route::get('/menunggu', [AntrianController::class, 'menunggu']);
    Route::get('/history', [AntrianController::class, 'history']);
});
