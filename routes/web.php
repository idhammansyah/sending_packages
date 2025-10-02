<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', [AuthController::class, 'v_login'])->name('login');
Route::get('/register', [AuthController::class, 'v_register'])->name('register');

Route::post('/attempt-register', [AuthController::class, 'register'])->name('attempt.register');
Route::post('/attempt-login', [AuthController::class, 'login'])->name('attempt.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
