<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer_index');
Route::get('/customer/show/{id}', 'CustomerController@show')->name('customer_show');
Route::get('/customer/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('customer_create');
Route::post('/customer/store', [App\Http\Controllers\CustomerController::class, 'store'])->name('customer_store');
Route::get('/customer/edit/{id}', 'CustomerController@edit')->name('customer_edit');
Route::put('/customer/update/{id}', 'CustomerController@update')->name('customer_update');
Route::delete('/customer/delete/{id}', 'CustomerController@delete')->name('customer_delete');
