<?php

use App\Http\Controllers\Chats\ChatsController;
use App\Http\Controllers\Chats\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/', function () {
    //     return Inertia::render('Chats/Index', [
    //         'canLogin' => Route::has('login'),
    //         'canRegister' => Route::has('register'),
    //         'laravelVersion' => Application::VERSION,
    //         'phpVersion' => PHP_VERSION,
    //     ]);
    // })->name('home');

    Route::get('/profiles', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::patch('/profiles', [ProfileController::class, 'update'])->name('profiles.update');
    Route::delete('/profiles', [ProfileController::class, 'destroy'])->name('profiles.destroy');


    Route::get('/',[ChatsController::class, 'index'])->name('chats.index');
    Route::get('/chat',[ChatsController::class, 'index'])->name('chats.index');
    Route::get('/chat/{chat}',[ChatsController::class, 'show'])->name('chats.show');
    Route::get('/chat/{chat}/details',[ChatsController::class, 'details'])->name('chats.details');
    Route::post('/chat',[ChatsController::class, 'store'])->name('chats.store');
    Route::post('/chat/{message}',[ChatsController::class, 'update'])->name('chats.update');
    Route::delete('/chat/{message}',[ChatsController::class, 'delete'])->name('chats.delete');
    Route::post('/group',[GroupController::class, 'store'])->name('group.store');

    Route::get('/profile',[UsersController::class, 'index'])->name('users.index');
    Route::get('/profile/{user}',[UsersController::class, 'show'])->name('users.show');
});

require __DIR__.'/auth.php';
