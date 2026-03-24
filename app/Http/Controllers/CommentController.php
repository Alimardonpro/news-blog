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

        // 1. Kommentni bazaga saqlaymiz
        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        // 2. Bildirishnoma yuborish (3 ta argument bilan: Kim, Nima, Qaysi post)
        if ($post->user_id !== auth()->id()) {
            // MANA BU YERGA $post QO'SHILDI:
            $post->user->notify(new PostCommented(auth()->user(), $comment, $post));
        }

        return back();
    }

    public function destroy(Comment $comment)
    {
        // O'chirayotgan odam izoh egasi yoki post egasi ekanini tekshirish
        if (auth()->id() !== $comment->user_id && auth()->id() !== $comment->post->user_id) {
            abort(403);
        }

        $comment->delete();
        return back();
    }
}