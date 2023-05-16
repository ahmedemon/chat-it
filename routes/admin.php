<?php

use Illuminate\Support\Facades\Route;

Route::get('login', [AdminLoginController::class, 'viewLogin'])->name('login.view');
Route::post('admin-login', [AdminLoginController::class, 'login'])->name('login.post');
Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::prefix('passwords')->group(function () {
    Route::get('forget-password', [ForgotPasswordController::class, 'getEmail'])->name('forget.password');
    Route::post('forget-password', [ForgotPasswordController::class, 'postEmail'])->name('forget.password.store');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'getPassword'])->name('reset.password');
    Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('reset.password.store');
});
