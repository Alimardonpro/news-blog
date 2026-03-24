<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostLiked;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();
        
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);

            if ($post->user_id !== $user->id) {
                // MANA SHU YERDA 2 TA NARSANI YUBORISh KERAK: KIM va NIMA
                $post->user->notify(new PostLiked($user, $post));
            }
        }

        return back();
    }
}