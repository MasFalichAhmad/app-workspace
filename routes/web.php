<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SectionController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('pages', PageController::class);
Route::resource('sections', SectionController::class);
Route::resource('items', ItemController::class);
Route::resource('attachments', AttachmentController::class)->only(['store', 'destroy']);

Route::put('/items/{id}/selesai', [ItemController::class, 'toggleSelesai'])
->name('items.selesai');