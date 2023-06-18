<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AdminLoginController::class, 'viewLogin'])->name('login.view');
Route::post('admin-login', [AdminLoginController::class, 'login'])->name('login.post');
Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'register', 'as' => 'register.'], function () {
    Route::get('/', [RegisterController::class, 'index'])->name('index');
    Route::post('register', [RegisterController::class, 'register'])->name('register');
});

Route::prefix('passwords')->group(function () {
    Route::get('forget-password', [ForgotPasswordController::class, 'getEmail'])->name('forget.password');
    Route::post('forget-password', [ForgotPasswordController::class, 'postEmail'])->name('forget.password.store');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'getPassword'])->name('reset.password');
    Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('reset.password.store');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::put('update/{id}', [ProfileController::class, 'update'])->name('update');
});
Route::middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/view-conversation/{id}', [DashboardController::class, 'viewConversation'])->name('view.conversation');
    Route::post('/send-message/{id}', [DashboardController::class, 'sendMessage'])->name('send.message');
    Route::get('inboxes', [DashboardController::class, 'getInboxes'])->name('get.inboxes');
    Route::get('seen/{id}', [DashboardController::class, 'seen'])->name('seen');
    Route::get('send-email/{id}', [DashboardController::class, 'sendEmail'])->name('send.email');
});
