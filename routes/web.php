<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController; // <--- 1. YANGI: BUNI QO'SHING
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

// 1. ASOSIY SAHIFA
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 2. DASHBOARD
Route::get('/dashboard', function () {
    // 2. YANGI: 'comments.user' ni qo'shdik. Bu izoh yozgan odamni ismini olish uchun kerak.
    $posts = Post::with(['user', 'likes', 'comments.user'])->latest()->get();
    
    return view('dashboard', compact('posts')); 
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. AUTH (Tizimga kirganlar uchun)
Route::middleware('auth')->group(function () {
    
    // --- POSTLAR ---
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // --- 3. YANGI: COMMENT ROUTES ---
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


    // --- PROFILE YO'NALTIRISH ---
    Route::get('/profile', function () {
        return redirect()->route('profile.show', ['username' => Auth::user()->username]);
    })->name('profile.me');

    // --- SOZLAMALAR ---
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- QO'SHIMCHA ---
    Route::get('/followers', function () { return view('followers'); })->name('followers');
    Route::get('/chat', function () { return view('chat'); })->name('chat');
    Route::get('/notifications', function () { return view('notifications'); })->name('notifications');
});

// 4. PUBLIC PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');
});

require __DIR__.'/auth.php';