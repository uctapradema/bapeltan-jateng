<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublicRegistrationForm;
use App\Http\Controllers\SignInController;

Route::get('/', [SignInController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SignInController::class, 'login'])->middleware('throttle:5,1')->name('login.perform');
Route::post('/logout', [SignInController::class, 'logout'])->name('logout');

// Halaman registrasi publik
Route::get('/register', PublicRegistrationForm::class)->name('public.registration');
