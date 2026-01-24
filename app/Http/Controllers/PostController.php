<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
}