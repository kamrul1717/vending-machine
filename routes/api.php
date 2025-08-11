<?php

use App\Http\Controllers\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/purchase', [PurchaseController::class, 'process']);