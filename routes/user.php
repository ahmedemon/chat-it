<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\User\ChattingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('get-conversation', [AjaxController::class, 'getConversation'])->name('get.conversation');
    Route::get('get-new-message', [AjaxController::class, 'getNewMessage'])->name('get.new.message');
    Route::get('get-group-conversation', [AjaxController::class, 'getGroupConversation'])->name('get.group.conversation');
    Route::get('get-new-group-message', [AjaxController::class, 'getGroupNewMessage'])->name('get.new.group.message');

    Route::post('send-message', [ChattingController::class, 'sendMessage'])->name('send.message');
});
