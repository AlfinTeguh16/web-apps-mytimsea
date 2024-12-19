<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UsersMiddleware;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ArticlesController;


Route::get('/', [HomeController::class, 'index'])->name('home.index');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::get('/input-otp', function () {
    return view('pages.auth.otp');
})->name('input-otp');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');

Route::post('/send-otp', [AuthController::class, 'sendOTP'])->name('send-otp');

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('index.admin');
});

Route::middleware([UsersMiddleware::class])->group(function () {
    Route::get('/dashboard/writer', [UsersController::class, 'index'])->name('index.users');
    Route::get('/articles/create', [ArticlesController::class, 'create'])->name('create.articles');
    Route::post('/articles/store', [ArticlesController::class, 'store'])->name('store.articles');

    Route::get('/articles/show', [ArticlesController::class, 'show'])->name('show.articles');

    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('edit.article');
    Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('update.article');

});


