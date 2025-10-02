<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('/attempt-register', [AuthController::class, 'register']);
Route::post('/attempt-login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
