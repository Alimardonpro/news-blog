<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // 1. Obunachilar va Kuzatuvlar sahifasini ko'rsatish
    public function index()
    {
        $user = Auth::user();
        
        // Ikkala ro'yxatni ham 'created_at' (qachon obuna bo'lgani) bo'yicha eng yangilarini birinchi qilib olamiz
        $followers = $user->followers()->orderByPivot('created_at', 'desc')->get();
        $following = $user->following()->orderByPivot('created_at', 'desc')->get();

        // resources/views/follows.blade.php fayliga ma'lumotlarni yuboramiz
        return view('followers', compact('user', 'followers', 'following'));
    }

    // 2. Kuzatish yoki Kuzatishni bekor qilish (Follow / Unfollow)
    public function toggle(User $user)
    {
        $me = Auth::user();

        // Xavfsizlik: Odam o'ziga-o'zi obuna bo'la olmasligi kerak
        if ($me->id === $user->id) {
            return back();
        }

        // 'toggle' funksiyasi juda aqlli: 
        // Agar men bu odamni kuzatayotgan bo'lsam - ro'yxatdan o'chiradi (Unfollow)
        // Agar kuzatmayotgan bo'lsam - ro'yxatga qo'shadi (Follow)
        $me->following()->toggle($user->id);

        // Orqaga, ya'ni shu tugma bosilgan sahifaga qaytarib yuboramiz
        return back();
    }
}