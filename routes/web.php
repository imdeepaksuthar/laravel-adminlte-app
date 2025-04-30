<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes(['verify' => true]);

Route::redirect('/', '/home', 301);

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin Routing
// Route::redirect('/admin', '/login');
Route::prefix('admin')->name('admin.')->group(function () {

    // Route::middleware(['redirect.role'])->group(function () {
    //     // Login
    //     Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    //     Route::post('login', [LoginController::class, 'login']);

    //     // Register
    //     Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    //     Route::post('register', [RegisterController::class, 'register']);
    // });
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
        Route::put('/settings/site', [SettingsController::class, 'updateSite'])->name('settings.updateSite');
        Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    });

});


Route::middleware(['redirect.role'])->group(function () {
    Auth::routes(['verify' => true]);
});


Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('user.dashboard');
});

Route::get('/test-mail', function () {
    $details = [
        'title' => 'Test Mail from Laravel',
        'body'  => 'This is a test email sent from a Laravel application.',
    ];

    Mail::to('imdeepaksuthar@gmail.com')->send(new \App\Mail\VerifyEmailMail($details));

    return 'Test email sent!';
});
