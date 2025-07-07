<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/admin-login', function () {
    return view('admin-login');
});
Route::get('/feedback', function () {
    return view('feedback');
});
Route::get('/admin-dashboard', function () {
    return view('admin-dashboard');
});