<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::redirect('/', '/home', 301);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('admin.settings.updateProfile');
    Route::put('/settings/site', [SettingsController::class, 'updateSite'])->name('admin.settings.updateSite');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('admin.settings.updatePassword');
});


Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});



Route::get('/test-mail', function () {
    $details = [
        'title' => 'Test Mail from Laravel',
        'body' => 'This is a test email sent from a Laravel application.'
    ];

    Mail::to('imdeepaksuthar@gmail.com')->send(new \App\Mail\VerifyEmailMail($details));

    return 'Test email sent!';
});
