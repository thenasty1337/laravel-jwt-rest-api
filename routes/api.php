<?php

use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Chat\MessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

use App\Http\Controllers\Transactions\WebhookController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Spinshield\CallbackController;
use App\Http\Controllers\Spinshield\SpinshieldController;


Route::get('/games', [GameController::class, 'getGames']);
Route::get('/details/{provider}/{gameSlug}', [GameController::class, 'getGameDetails']);
Route::get('/spinshield', [CallbackController::class, 'router'])->name('spinshield.router');


Route::get('/messages', [MessageController::class, 'index']); // Public route

Route::middleware('auth:api')->group(function () {
    Route::post('/messages', [MessageController::class, 'store']); // Protected route
    Route::delete('/messages/{id}', [MessageController::class, 'destroy']); // Protected route
});

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!']);
});

require __DIR__ . '/auth.php';

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'show'])
        ->name('user.show');
});

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::patch('/user', [UserController::class, 'update'])
        ->name('user.update');

    Route::patch('/user/change-password', [UserController::class, 'changePassword'])
        ->name('user.change-password');
});


Route::post('/webhook/transaction', [WebhookController::class, 'handleTransaction']);
