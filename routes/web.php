<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\CertificateController;

Route::get('/', [SignInController::class, 'showLoginForm'])->name('login');
Route::get('/login', [SignInController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [SignInController::class, 'login'])->middleware('throttle:5,1')->name('login.perform');
Route::post('/logout', [SignInController::class, 'logout'])->name('logout');

// Halaman registrasi publik
Route::get('/register', [RegistrationController::class, 'showForm'])->name('public.registration');
Route::post('/register', [RegistrationController::class, 'store'])->name('public.registration.store');

// Sertifikat
Route::get('/sertifikat/{id}/download', [CertificateController::class, 'download'])->name('sertifikat.download');
Route::get('/sertifikat/{id}/preview', [CertificateController::class, 'preview'])->name('sertifikat.preview');
