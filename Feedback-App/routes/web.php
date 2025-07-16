<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/feedback', function () {
    return view('feedback');
});

// Public feedback submission
Route::post('/feedback', [FeedbackController::class, 'store'])
    ->name('feedback.store');

// Admin routes (protected by auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [FeedbackController::class, 'dashboard'])
        ->name('dashboard');
        
    Route::post('/feedback/{feedback}/approve', [FeedbackController::class, 'approve'])
        ->name('feedback.approve');
        
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])
        ->name('feedback.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/feedback/{feedback}/disapprove', [FeedbackController::class, 'disapprove'])
    ->name('feedback.disapprove');
});

require __DIR__.'/auth.php';