<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\User\ChattingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('get-conversation', [AjaxController::class, 'getConversation'])->name('get.conversation');

    Route::get('send-message', [ChattingController::class, 'sendMessage'])->name('send.message');
});
