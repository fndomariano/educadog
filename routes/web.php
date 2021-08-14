<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Application\ActivityController;
use App\Http\Controllers\Application\CustomerController;
use App\Http\Controllers\Application\PetController;

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

Route::get('/home', [App\Http\Controllers\Application\HomeController::class, 'index'])->name('home');

Route::get('/customers', [CustomerController::class, 'index'])->name('customer_index');
Route::get('/customer/{id}/show', [CustomerController::class, 'show'])->name('customer_show');
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer_create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer_store');
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer_edit');
Route::put('/customer/{id}/update', [CustomerController::class, 'update'])->name('customer_update');
Route::delete('/customer/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer_delete');

Route::get('pets', [PetController::class, 'index'])->name('pet_index');
Route::get('pet/{id}/show', [PetController::class, 'show'])->name('pet_show');
Route::get('pet/create', [PetController::class, 'create'])->name('pet_create');
Route::post('pet/store', [PetController::class, 'store'])->name('pet_store');
Route::get('pet/{id}/edit', [PetController::class, 'edit'])->name('pet_edit');
Route::put('pet/{id}/update', [PetController::class, 'update'])->name('pet_update');
Route::delete('pet/{id}/destroy', [PetController::class, 'destroy'])->name('pet_delete');

Route::get('activities', [ActivityController::class, 'index'])->name('activity_index');
Route::get('activity/{id}/show', [ActivityController::class, 'show'])->name('activity_show');
Route::get('activity/create', [ActivityController::class, 'create'])->name('activity_create');
Route::post('activity/store', [ActivityController::class, 'store'])->name('activity_store');
Route::get('activity/{id}/edit', [ActivityController::class, 'edit'])->name('activity_edit');
Route::put('activity/{id}/update', [ActivityController::class, 'update'])->name('activity_update');
Route::delete('activity/{id}/destroy', [ActivityController::class, 'destroy'])->name('activity_delete');
Route::delete('activity/destroyMedia/{id}', [ActivityController::class, 'destroyMedia'])->name('activity_delete_media');

Route::get('/api/login', [AuthController::class, 'login'])->name('api_login');
