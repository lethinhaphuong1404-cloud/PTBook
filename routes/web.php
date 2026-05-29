<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\PaymentController;

/*********************
 * Public Routes
 *********************/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Login
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Password Reset
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

/*********************
 * Book & Audio Routes
 *********************/

// 1. Danh sách sách nói chung
Route::get('/audio-books', [BookController::class, 'audioIndex'])->name('books.audio');

// 2. Tìm kiếm
Route::get('/search', [BookController::class, 'search'])->name('books.search');

// 3. Nhóm routes liên quan đến Book (Slug)
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    
    // Đặt các route cụ thể lên trước route {book:slug} để tránh bị nhận nhầm là slug
    Route::post('/{id}/increment-audio-view', [BookController::class, 'incrementAudioView'])->name('audio.increment');
    
    // Route Chi tiết & Đọc/Nghe
    Route::get('/{book:slug}', [BookController::class, 'show'])->name('show');
    Route::get('/{book:slug}/read/{chapter?}', [BookController::class, 'read'])->name('read');
    Route::get('/{book:slug}/listen/{chapter?}', [BookController::class, 'listen'])->name('listen');
    Route::get('/{book:slug}/download', [BookController::class, 'download'])->name('download');
});

// Alias cho books.listen (Nếu bạn đang dùng route('books.listen') ở nhiều nơi)
Route::get('/listen/{slug}', [BookController::class, 'listen'])->name('books.listen');

/*********************
 * Authenticated Routes
 *********************/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    
    // Comment & Rating
    Route::post('/books/{book}/comment', [BookController::class, 'storeComment'])->name('books.comment');
    Route::post('/books/{book}/rate', [BookController::class, 'storeRating'])->name('books.rate');
});

/*********************
 * Admin Routes
 *********************/
/*********************
 * Admin Routes
 *********************/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('books', AdminBookController::class);
    
    // Chapter Management
    Route::post('/books/{bookId}/chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::put('/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::patch('/chapters/update-audio/{bookId}', [ChapterController::class, 'updateAudio'])->name('chapters.updateAudio');
    Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');
});

/*********************
 * Authenticated Routes (Thanh toán & Hồ sơ)
 *********************/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    
    // Route thanh toán MoMo - ĐƯA RA NGOÀI ADMIN
    Route::get('/payment/momo/{plan}', [PaymentController::class, 'showMomoGateway'])
        ->name('payment.momo');

    Route::post('/payment/momo/confirm', [PaymentController::class, 'processMomoFake'])
        ->name('payment.momo.confirm');

    // Comment & Rating
    Route::post('/books/{book}/comment', [BookController::class, 'storeComment'])->name('books.comment');
    Route::post('/books/{book}/rate', [BookController::class, 'storeRating'])->name('books.rate');
});