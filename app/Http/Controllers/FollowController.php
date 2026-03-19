<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserFollowed; // <--- YANGI

class FollowController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $followers = $user->followers()->orderByPivot('created_at', 'desc')->get();
        $following = $user->following()->orderByPivot('created_at', 'desc')->get();

        return view('followers', compact('user', 'followers', 'following'));
    }

    public function toggle(User $user)
    {
        $me = Auth::user();

        if ($me->id === $user->id) {
            return back();
        }

        $me->following()->toggle($user->id);

        // YANGI: Agar men bu odamni endi kuzatayotgan bo'lsam, unga xabar yuboramiz!
        if ($me->following()->where('following_id', $user->id)->exists()) {
            $user->notify(new UserFollowed($me));
        }

        return back();
    }
}