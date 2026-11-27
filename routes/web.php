<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\SparepartController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('pages', PageController::class);
Route::resource('sections', SectionController::class);
Route::resource('items', ItemController::class);
Route::resource('attachments', AttachmentController::class)->only(['store', 'destroy']);


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/service-orders/report', [ServiceOrderController::class, 'report'])->name('service-orders.report');
    Route::put('/service-orders/{id}/selesai', [ServiceOrderController::class, 'tandaiSelesai'])->name('service-orders.selesai');
    Route::resource('service-orders', ServiceOrderController::class);

    Route::resource('customers', CustomerController::class);

    Route::middleware(['admin'])->group(function () {
        Route::resource('service-types', ServiceTypeController::class)->except(['show']);
        Route::resource('spareparts', SparepartController::class);
    });
});