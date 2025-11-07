<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('home');
});

// Patient pages (public)
Route::get('/pasien', function () {
    return view('pasien.index');
})->name('pasien.index');

// Display page (public)
Route::get('/display', function () {
    return view('display.index');
})->name('display.index');

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Staff pages (protected)
Route::middleware('auth')->group(function () {
    Route::get('/petugas', function () {
        return view('petugas.index');
    })->name('petugas.index');
});
