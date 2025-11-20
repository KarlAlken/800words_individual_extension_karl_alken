<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// I define public routes that anyone can access
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/languages/{language}/flashcards', [FlashcardController::class, 'show'])->name('languages.flashcards');

// I define account route (accessible to everyone, shows different content based on auth status)
Route::get('/account', [AuthController::class, 'showLogin'])->name('account');

// I define login route for guests only
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// I define routes that require authentication
Route::middleware('auth')->group(function () {
    Route::view('/review', 'review')->name('review');
    Route::view('/progress', 'progress')->name('progress');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// I define admin routes that require admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin');
    Route::get('/flashcards', [AdminDashboardController::class, 'flashcards'])->name('admin.flashcards.index');
    Route::post('/languages', [AdminDashboardController::class, 'storeLanguage'])->name('admin.languages.store');
    Route::patch('/languages/{language}', [AdminDashboardController::class, 'updateLanguage'])->name('admin.languages.update');
    Route::delete('/languages/{language}', [AdminDashboardController::class, 'deleteLanguage'])->name('admin.languages.destroy');
    Route::post('/flashcards', [AdminDashboardController::class, 'storeFlashcard'])->name('admin.flashcards.store');
    Route::patch('/flashcards/{flashcard}', [AdminDashboardController::class, 'updateFlashcard'])->name('admin.flashcards.update');
    Route::delete('/flashcards/{flashcard}', [AdminDashboardController::class, 'deleteFlashcard'])->name('admin.flashcards.destroy');
});
