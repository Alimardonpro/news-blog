<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        // 1. User allaqachon like bosganmi tekshiramiz
        $like = Like::where('post_id', $post->id)
                    ->where('user_id', Auth::id())
                    ->first();

        if ($like) {
            // Agar bor bo'lsa -> O'CHIRAMIZ (Unlike)
            $like->delete();
        } else {
            // Agar yo'q bo'lsa -> YARATAMIZ (Like)
            Like::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
            ]);
        }

        return back(); // Turgan sahifasiga qaytaradi
    }
}