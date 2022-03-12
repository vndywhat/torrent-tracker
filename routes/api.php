<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\UserController;
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

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('registration', [AuthenticationController::class, 'registration'])->name('registration');

    Route::post('login', [AuthenticationController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::get('user', [UserController::class, 'me'])->name('me');

        Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    });
});
