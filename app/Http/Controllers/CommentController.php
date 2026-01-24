<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Izoh yozish
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:500',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'body' => $request->body,
        ]);

        return back(); // Turgan joyiga qaytaradi
    }

    // Izohni o'chirish
    public function destroy(Comment $comment)
    {
        // Faqat o'zining izohini o'chira olsin
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back();
    }
}