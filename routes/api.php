<?php

use App\Http\Controllers\Api\ServiceOrderController;
use App\Http\Controllers\Api\SparepartController;
use App\Http\Controllers\Api\SpkController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/spareparts', [SparepartController::class, 'index']);
Route::get('/spareparts/{id}', [SparepartController::class, 'show']);
Route::post('/spareparts', [SparepartController::class, 'store']);
Route::put('/spareparts/{id}', [SparepartController::class, 'update']);
Route::delete('/spareparts/{id}', [SparepartController::class, 'destroy']);

Route::get('/service-orders', [ServiceOrderController::class, 'index']);
Route::put('/service-orders/{id}/selesai', [ServiceOrderController::class, 'tandaiSelesai']);

Route::get('/spk-prioritas', [SpkController::class, 'index']);
Route::get('/stats', [StatsController::class, 'index']);