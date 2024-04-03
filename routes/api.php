<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chats\ChatsController;
use App\Http\Controllers\Friends\FriendsController;
use App\Http\Controllers\Users\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getMessage/{chat}/{page}',[ChatsController::class, 'getMessages'])->middleware('auth')->name('message.get');

Route::post('/friend',[FriendsController::class, 'store'])->name('friend.store');
Route::post('/friend/{friend}',[FriendsController::class, 'accepted'])->name('friend.accepted');
Route::get('/chat/{chat}/details',[ChatsController::class, 'details'])->name('chats.details.api');
Route::post('/chat/destroyMessages/{method}',[ChatsController::class, 'destroy'])->name('chats.destroy');
Route::post('/getUsersForIds',[UsersController::class, 'getUsersForIds'])->name('chats.getUsersForIds');
