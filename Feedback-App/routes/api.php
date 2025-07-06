<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminController;

Route::post('/feedback', [FeedbackController::class, 'store']);
Route::get('/feedbacks', [FeedbackController::class, 'index']); // only approved
Route::put('/feedback/{id}', [FeedbackController::class, 'update']);
Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admin/feedbacks', [FeedbackController::class, 'unapproved']);
Route::get('/admin/all-feedbacks', [FeedbackController::class, 'all']);