<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatParkirController;

// Middleware untuk autentikasi user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint untuk memproses pemindaian QR Code
Route::post('/scan-qr', [RiwayatParkirController::class, 'scanQR']);
