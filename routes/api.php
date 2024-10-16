<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
