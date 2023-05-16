<?php

use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('get-conversation', [AjaxController::class, 'getConversation'])->name('get.conversation');
});
