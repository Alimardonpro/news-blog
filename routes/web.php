<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatController; // <--- YANGI: ChatController ulandi
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

// 1. ASOSIY SAHIFA
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 2. DASHBOARD
Route::get('/dashboard', function () {
    $posts = Post::with(['user', 'likes', 'comments.user'])->latest()->get();
    return view('dashboard', compact('posts')); 
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. TIZIMGA KIRGANLAR UCHUN (Barcha himoyalangan routelar bitta guruhda)
Route::middleware('auth')->group(function () {
    
    // --- POSTLAR ---
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/view', [PostController::class, 'incrementView'])->name('posts.view');

    // --- IZOHLAR (COMMENTS) ---
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // --- CHAT TIZIMI ---
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/search', [ChatController::class, 'searchUsers'])->name('chat.search');
    Route::get('/chat/{user}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{user}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    
    // Yangi qo'shilgan funksiyalar:
    Route::delete('/chat/{user}/clear', [ChatController::class, 'clearConversation'])->name('chat.clear'); // Chatni o'chirish
    Route::patch('/chat/message/{message}', [ChatController::class, 'updateMessage'])->name('chat.message.update'); // Xabarni tahrirlash
    Route::delete('/chat/message/{message}', [ChatController::class, 'destroyMessage'])->name('chat.message.destroy'); // Xabarni o'chirish

    // --- PROFIL SOZLAMALARI VA KORINISHI ---
    Route::get('/profile', function () {
        return redirect()->route('profile.show', ['username' => Auth::user()->username]);
    })->name('profile.me');
    
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Boshqa foydalanuvchilarning profilini ko'rish (Ikkita auth guruhini birlashtirdik)
    Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');

    // --- BOSHQA SAHIFALAR ---
    Route::get('/followers', function () { return view('followers'); })->name('followers');
    Route::get('/notifications', function () { return view('notifications'); })->name('notifications');
});

// 4. AUTHENTICATION ROUTELARI (Breeze o'rnatganda keladigan fayl)
require __DIR__.'/auth.php';