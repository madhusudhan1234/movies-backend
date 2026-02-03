<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Movie\FavoritesController;
use App\Http\Controllers\Movie\MovieController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/resend', [VerificationController::class, 'resend']);
    Route::get('/me', AuthUserController::class);

    Route::middleware('verified')->group(function () {
        Route::get('/movies/favorites', [FavoritesController::class, 'getListOfFavorites']);
        Route::post('/movies/{movieId}/favorites', [FavoritesController::class, 'addToFavorites']);
        Route::delete('/movies/{movieId}/favorites', [FavoritesController::class, 'removeFromFavorites']);
    });
});

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movieId}', [MovieController::class, 'show']);
