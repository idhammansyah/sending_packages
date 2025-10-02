<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CustomerController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', [AuthController::class, 'v_login'])->name('login');
Route::get('/register', [AuthController::class, 'v_register'])->name('register');

Route::post('/attempt-register', [AuthController::class, 'register'])->name('attempt.register');
Route::post('/attempt-login', [AuthController::class, 'login'])->name('attempt.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/barang', [BarangController::class, 'v_barang'])->name('barang');
Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
Route::delete('/destroy/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
Route::put('/update/{id}', [BarangController::class, 'update'])->name('barang.update');

Route::get('/driver', [DriverController::class, 'v_User'])->name('driver');
Route::post('/driver-store', [DriverController::class, 'store'])->name('driver.store');
Route::delete('/destroy-driver/{id}', [DriverController::class, 'destroy'])->name('driver.destroy');
Route::put('/update-driver/{id}', [DriverController::class, 'update'])->name('driver.update');


Route::get('/customer', [CustomerController::class, 'v_customer'])->name('customer.index');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

