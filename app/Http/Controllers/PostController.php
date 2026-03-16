<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PostView;

class PostController extends Controller
{
    // 1. Postni saqlash (CREATE)
    public function store(Request $request)
    {
        // Validatsiya
        $request->validate([
            'body' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB gacha rasm
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
        }

        // Bazaga yozish
        Post::create([
            'user_id' => Auth::id(),
            'body' => $request->body,
            'image' => $path,
        ]);

        return back()->with('status', 'Post created successfully!');
    }

    // 2. Postni o'chirish (DELETE)
    public function destroy(Post $post)
    {
        // Faqat o'zining postini o'chira olsin
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // Agar rasm bo'lsa, uni ham o'chiramiz
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('status', 'Post deleted!');
    }

    // ... class ichida

    // Postni tahrirlash (UPDATE)
    public function update(Request $request, Post $post)
    {
        // 1. Faqat o'zining postini tahrirlay olsin
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // 2. Validatsiya
        $request->validate([
            'body' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        // 3. Rasmni yangilash (Agar yangi rasm yuklansa)
        if ($request->hasFile('image')) {
            // Eskisini o'chiramiz
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            // Yangisini saqlaymiz
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        // 4. Matnni yangilash
        $post->body = $request->body;
        $post->save();

        return back()->with('status', 'Post yangilandi!');
    }

    public function incrementView(Request $request, Post $post)
{
    $ip = $request->ip();
    $userId = Auth::id(); // Login qilgan user IDsi (yoki null)

    // 1. Bazadan qidirishni boshlaymiz
    $query = PostView::where('post_id', $post->id);

    if ($userId) {
        // A) LOGIN QILGANLAR UCHUN:
        // Faqat User ID ni tekshiramiz. IP bir xil bo'lsa ham farqi yo'q.
        // Masalan: Asomiddin (ID: 1) va Bekzod (ID: 2) bitta Wi-Fi da bo'lsa ham, 
        // ID lar har xil bo'lgani uchun ikkalasini ham sanaydi.
        $query->where('user_id', $userId);
    } else {
        // B) MEHMONLAR UCHUN:
        // Login qilmagan bo'lsa, IP bo'yicha tekshiramiz.
        // Faqat oldin shu IP dan mehmon bo'lib kirganlarni tekshiramiz.
        $query->where('ip_address', $ip)->whereNull('user_id');
    }

    // Agar allaqachon ko'rgan bo'lsa (Bazada topilsa)
    if ($query->exists()) {
        return response()->json([
            'views' => $post->views()->count()
        ]);
    }

    // 2. Agar ko'rmagan bo'lsa - YANGI YOZUV QO'SHAMIZ
    PostView::create([
        'post_id' => $post->id,
        'user_id' => $userId,
        'ip_address' => $ip,
        'user_agent' => $request->header('User-Agent'),
    ]);

    return response()->json([
        'views' => $post->views()->count()
    ]);
}
}