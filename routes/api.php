<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CategoryController;


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!', 'time' => now()]);
});

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);