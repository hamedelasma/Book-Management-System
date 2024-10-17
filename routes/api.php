<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UploaderController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'user'])->name('user');

    Route::get('books/search', SearchController::class)->name('books.search');
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('books', BookController::class);

    Route::post('/uploader', UploaderController::class)
        ->middleware(AdminMiddleware::class)
        ->name('uploader');
});
