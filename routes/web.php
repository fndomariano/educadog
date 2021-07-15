<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/customers', [CustomerController::class, 'index'])->name('customer_index');
Route::get('/customer/{id}/show', [CustomerController::class, 'show'])->name('customer_show');
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer_create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer_store');
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer_edit');
Route::put('/customer/{id}/update', [CustomerController::class, 'update'])->name('customer_update');
Route::delete('/customer/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer_delete');
