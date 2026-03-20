<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatController; 
use App\Http\Controllers\FollowController;
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

// 3. HIMOYALANGAN ROUTELAR (Faqat login qilganlar uchun)
Route::middleware('auth')->group(function () {
    
    // LENTA (Following feed)
    Route::get('/feed', [ProfileController::class, 'feed'])->name('feed');

    // POSTLAR
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/view', [PostController::class, 'incrementView'])->name('posts.view');

    // IZOHLAR
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{user}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/total-unread', [ChatController::class, 'getTotalUnread']);
    Route::get('/chat/search', [ChatController::class, 'searchUsers']);
    
    // YAngi qo'shilgan route'lar: Tahrirlash, O'chirish va Tozalash uchun
    Route::put('/chat/messages/{message}', [ChatController::class, 'updateMessage']);
    Route::delete('/chat/messages/{message}', [ChatController::class, 'deleteMessage']);
    Route::delete('/chat/{user}/clear', [ChatController::class, 'clearChat']);

    // FOLLOW (Obuna bo'lish)
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');
    Route::get('/network', [FollowController::class, 'index'])->name('network');

    // PROFIL SOZLAMALARI
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // BILDIRISHNOMALAR
    Route::get('/notifications', function () {
        $user = Auth::user();
        $notifications = $user->notifications;
        $user->unreadNotifications->markAsRead();
        return view('notifications', compact('notifications'));
    })->name('notifications');

    // PROFIL VA OBUNACHILAR RO'YXATI (Bular oxirida bo'lishi shart)
    Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/@{username}/{type}', [ProfileController::class, 'usersList'])
        ->name('profile.users')
        ->where('type', 'followers|following');
    // new routing every day
}); 

require __DIR__.'/auth.php';