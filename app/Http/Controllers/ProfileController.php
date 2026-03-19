<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Profilni ko'rsatish
    public function show($username)
    {
        $user = User::where('username', $username)
            ->withCount(['followers', 'following'])
            ->firstOrFail();

        $posts = $user->posts()
            ->withCount(['likes', 'comments', 'views'])
            ->get();

        return view('profile', compact('user', 'posts'));
    }

    // Obunachilar va Kuzatilayotganlar ro'yxati
    public function usersList($username, $type)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        if ($type === 'followers') {
            $users = $user->followers()->paginate(20);
            $title = "Obunachilar";
        } else {
            $users = $user->following()->paginate(20);
            $title = "Kuzatilayotganlar";
        }

        return view('profile.users-list', compact('users', 'title', 'user'));
    }

    // Lenta (Obuna bo'linganlar posti)
    public function feed()
    {
        $followingIds = Auth::user()->following()->pluck('following_id');
        $feedPosts = Post::whereIn('user_id', $followingIds)
            ->with('user')
            ->withCount(['likes', 'comments', 'views'])
            ->latest()
            ->paginate(15);

        return view('feed', compact('feedPosts'));
    }

    public function edit() {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request) {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($user->banner) Storage::disk('public')->delete($user->banner);
            $data['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $user->update($data);
        return redirect()->route('profile.show', $user->username);
    }
}