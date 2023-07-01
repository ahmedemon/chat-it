<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('web', function () {
    return view('web');
})->name('web');

Route::get('group', function () {
    return view('group-web');
})->name('group-web');

Route::get('/chat-box', function () {
    return view('chatbox');
});

Route::get('/fcm-check', function () {
    return view('fcm');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('login-or-create', [AjaxController::class, 'loginOrAccount'])->name('login.or.create');

Route::get('storage', function () {
    Artisan::call('storage:link');
});
