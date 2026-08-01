<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\CertificateController;

Route::get('/', [SignInController::class, 'showLoginForm'])->name('home');
Route::get('/login', [SignInController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SignInController::class, 'login'])->middleware('throttle:5,1')->name('login.perform');
Route::post('/logout', [SignInController::class, 'logout'])->name('logout');

Route::get('/register', [RegistrationController::class, 'showForm'])->middleware('guest')->name('public.registration');
Route::post('/register', [RegistrationController::class, 'store'])->middleware('throttle:10,1')->name('public.registration.store');

Route::get('/sertifikat/{id}/download', [CertificateController::class, 'download'])->name('sertifikat.download');
Route::get('/sertifikat/{id}/preview', [CertificateController::class, 'preview'])->name('sertifikat.preview');
