<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\PostCommented;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // 1. Kommentni bazaga saqlaymiz va $comment o'zgaruvchisiga olamiz
        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        // 2. O'zimizning postimiz bo'lmasa, xabar yuboramiz (komment matnini ham qo'shib)
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new PostCommented(auth()->user(), $comment));
        }

        return back();
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();
        return back();
    }
}