<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BarangController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\CustomerController;

Route::post('/attempt-register', [AuthController::class, 'register']);
Route::post('/attempt-login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Barang
Route::get('/barang', [BarangController::class, 'list_barang']);
Route::post('/attempt-barang', [BarangController::class, 'store']);
Route::post('/update-barang/{id}', [BarangController::class, 'updateBarang']);
Route::delete('/delete-barang/{id}', [BarangController::class, 'destroy']);

// Driver
Route::get('/driver', [DriverController::class, 'list_driver']);
Route::post('/attempt-driver', [DriverController::class, 'add_driver']);
Route::post('/update-driver/{id}', [DriverController::class, 'update_driver']);
Route::delete('/delete-driver/{id}', [DriverController::class, 'delete_driver']);

Route::get('/customer-list', [CustomerController::class, 'list_customer']);
Route::post('/customer-add', [CustomerController::class, 'add_customer']);
Route::post('/customer-update/{id}', [CustomerController::class, 'update_customer']);
Route::delete('/customer-delete/{id}', [CustomerController::class, 'delete_customer']);

