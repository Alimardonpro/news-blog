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
        
        // 1. Foydalanuvchi shu postga layk bosganmi, yo'qmi tekshiramiz
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // 2. Agar oldin layk bosgan bo'lsa, laykni qaytarib olamiz (o'chiramiz)
            $like->delete();
        } else {
            // 3. Agar bosmagan bo'lsa, yangi layk qo'shamiz
            $post->likes()->create(['user_id' => $user->id]);

            // 4. Va aynan mana shu paytda (faqat layk BOSILGANDA) xabar yuboramiz
            // O'ziga o'zi layk bossa, xabar bormaydi
            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostLiked($user));
            }
        }

        return back();
    }
}