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
Route::post('/profile/password/forget', [ProfileController::class, 'resetPasswordLink'])->name('api_profile_password_reset_generate_link');


Route::group(['middleware' => ['web']], function() {
    Route::get('/profile/password/reset/{token}', [ProfileController::class, 'resetPasswordForm'])->name('api_profile_password_reset_form');
    Route::post('/profile/password/reset', [ProfileController::class, 'resetPassword'])->name('api_profile_password_reset');
});

Route::group(['middleware' => ['apiJwt']], function() {
    Route::post('/profile/{id}/edit', [ProfileController::class, 'editProfile'])->name('api_profile_edit_profile');
    Route::get('/profile/pets', [ProfileController::class, 'pets'])->name('api_profile_pets');
    Route::get('/profile/pets/{id}/activities', [ProfileController::class, 'petsActivities'])->name('api_profile_pets_activities');
    Route::get('/profile/pets/activities/{id}', [ProfileController::class, 'petActivity'])->name('api_profile_pet_activity');
});