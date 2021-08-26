<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
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
Route::post('/login', [AuthController::class, 'login'])->name('api_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('api_logout');
Route::post('/refresh', [AuthController::class, 'refresh'])->name('api_refresh');

Route::post('/profile/password/create', [ProfileController::class, 'createPassword'])->name('api_profile_password_create');

Route::group(['middleware' => ['apiJwt']], function() {
    Route::get('/profile/pets', [ProfileController::class, 'pets'])->name('api_profile_pets');
    Route::get('/profile/pets/{id}/activities', [ProfileController::class, 'petsActivities'])->name('api_profile_pets_activities');
});