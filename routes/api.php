<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\PetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/password/create', [PasswordController::class, 'create'])->name('api_password_create');


Route::post('/login', [AuthController::class, 'login'])->name('api_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('api_logout');


Route::group(['middleware' => ['apiJwt']], function() {
    Route::get('/my-pets', [PetController::class, 'myPets'])->name('api_my_pets');
});