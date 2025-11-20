<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

// public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/languages/{language}/flashcards', [FlashcardController::class, 'show'])->name('languages.flashcards');

// account route
Route::get('/account', [AuthController::class, 'showLogin'])->name('account');

// login route
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// routes that need login
Route::middleware('auth')->group(function () {
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
    Route::post('/flashcards/{flashcard}/mark-known', [FlashcardController::class, 'markAsKnown'])->name('flashcards.mark-known');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin');
    Route::get('/flashcards', [AdminDashboardController::class, 'flashcards'])->name('admin.flashcards.index');
    Route::post('/languages', [AdminDashboardController::class, 'storeLanguage'])->name('admin.languages.store');
    Route::patch('/languages/{language}', [AdminDashboardController::class, 'updateLanguage'])->name('admin.languages.update');
    Route::delete('/languages/{language}', [AdminDashboardController::class, 'deleteLanguage'])->name('admin.languages.destroy');
    Route::post('/flashcards', [AdminDashboardController::class, 'storeFlashcard'])->name('admin.flashcards.store');
    Route::patch('/flashcards/{flashcard}', [AdminDashboardController::class, 'updateFlashcard'])->name('admin.flashcards.update');
    Route::post('/flashcards/{flashcard}/toggle-known', [AdminDashboardController::class, 'toggleKnown'])->name('admin.flashcards.toggle-known');
    Route::delete('/flashcards/{flashcard}', [AdminDashboardController::class, 'deleteFlashcard'])->name('admin.flashcards.destroy');
});
