<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\UpdateProfileController;
use App\Http\Controllers\Api\V1\Content\IndexContentController;
use App\Http\Controllers\Api\V1\Content\ShowContentController;
use App\Http\Controllers\Api\V1\Genre\IndexGenreController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\Playback\ShowPlaybackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/health', HealthController::class);
Route::post('/v1/auth/register', RegisterController::class)
    ->middleware('throttle:5,1');
Route::post('/v1/auth/login', LoginController::class)
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/v1/auth/logout', LogoutController::class);
    Route::get('/v1/auth/me', MeController::class);

    Route::patch('/v1/auth/profile', UpdateProfileController::class);

});

Route::get('/v1/contents', IndexContentController::class);
Route::get('/v1/contents/{slug}', ShowContentController::class);
Route::get('/v1/genres', IndexGenreController::class);
Route::get(
    '/v1/contents/{slug}/playback',
    ShowPlaybackController::class,
);
