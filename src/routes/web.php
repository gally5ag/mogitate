<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// 一覧・検索
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'index']);
Route::get('/products/register', [ProductController::class, 'create']);
Route::post('/products/register', [ProductController::class, 'store']);

Route::get('/products/{productId}', [ProductController::class, 'edit']);
Route::post('/products/{productId}/update', [ProductController::class, 'update']);
Route::post('/products/{productId}/delete', [ProductController::class, 'destroy']);
