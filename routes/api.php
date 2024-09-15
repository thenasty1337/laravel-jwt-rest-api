<?php

use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;
use App\Http\Controllers\Spinshield\CallbackController;
use App\Http\Controllers\Chat\ChatController;


Route::get('/games', [GameController::class, 'getGames']);
Route::get('/details/{provider}/{gameSlug}', [GameController::class, 'getGameDetails']);
Route::get('/spinshield', [CallbackController::class, 'router'])->name('spinshield.router');



Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::get('/chat', [ChatController::class, 'index']);


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

    Route::post('/chat', [ChatController::class, 'store']);
    Route::post('/chat/get-messages', [ChatController::class, 'getMessages']);
    Route::post('/chat/remove-message', [ChatController::class, 'removeMessage']);
    Route::post('/chat/clear', [ChatController::class, 'clearChat']);
    Route::post('/chat/lock', [ChatController::class, 'lockChat']);
});


