<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Genre dan Author: Read All dan Show publik, sisanya admin
Route::apiResource('genres', GenreController::class)->only(['index', 'show']);
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('genres', GenreController::class)->except(['index', 'show']);
    Route::apiResource('authors', AuthorController::class)->except(['index', 'show']);
});

// Transaksi: Create, Update, Show khusus customer yang sudah login
Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::apiResource('transaksis', TransaksiController::class)
        ->only(['store', 'update', 'show']);
});

// Transaksi: Read All dan Destroy khusus admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('transaksis', TransaksiController::class)
        ->only(['index', 'destroy']);
});