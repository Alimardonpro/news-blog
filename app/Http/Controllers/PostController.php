<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PostView;

class PostController extends Controller
{
    // 1. Postni ko'rish
    public function show(Post $post)
    {
        return view('dashboard', ['posts' => collect([$post])]); 
    }

    // 2. Postni saqlash (CREATE) - BIR NECHTA RASM UCHUN MOSLASHTIRILDI
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            // image emas, images bo'ldi va array qabul qiladi (maksimum 6 ta)
            'images' => 'nullable|array|max:6', 
            // 2048 (2MB) o'rniga 10240 (10MB) qilib oshirildi
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $paths = []; // Rasmlar yo'lini saqlash uchun bo'sh massiv

        // Agar rasmlar yuklangan bo'lsa, har birini aylanib saqlaymiz
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('posts', 'public');
            }
        }

        Post::create([
            'user_id' => Auth::id(),
            'body' => $request->body,
            // Massivni JSON shaklida matn qilib bazaga yozamiz
            'image' => !empty($paths) ? json_encode($paths) : null,
        ]);

        return back()->with('status', 'Post yaratildi!');
    }

    // 3. Postni o'chirish (DELETE) - BARCHA RASMLARNI XOTIRADAN O'CHIRISH
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        if ($post->image) {
            // Rasmlarni JSON dan o'qiymiz
            $images = json_decode($post->image, true);
            
            // Agar massiv bo'lsa, hammasini bittalab o'chiramiz
            if (is_array($images)) {
                foreach ($images as $img) {
                    Storage::disk('public')->delete($img);
                }
            } else {
                // Eski (bitta rasm formatida) saqlangan postlar bo'lsa
                Storage::disk('public')->delete($post->image);
            }
        }

        $post->delete();

        return back()->with('status', 'Post o\'chirildi!');
    }

    // 4. Postni tahrirlash (UPDATE) - YANGI RASMLAR UCHUN
    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:1000',
            'images' => 'nullable|array|max:6',
            // Bu yerda ham 2048 o'rniga 10240 (10MB) qilib oshirildi
            'images.*' => 'image|max:10240',
        ]);

        // Agar yangi rasmlar yuklangan bo'lsa
        if ($request->hasFile('images')) {
            
            // 1-qadam: Eski rasmlarni xotiradan tozalaymiz
            if ($post->image) {
                $oldImages = json_decode($post->image, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImg) {
                        Storage::disk('public')->delete($oldImg);
                    }
                } else {
                    Storage::disk('public')->delete($post->image);
                }
            }

            // 2-qadam: Yangi rasmlarni yuklaymiz
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('posts', 'public');
            }
            
            $post->image = json_encode($paths);
        }

        $post->body = $request->body;
        $post->save();

        return back()->with('status', 'Post yangilandi!');
    }

    // 5. Ko'rishlar sonini oshirish (O'zgarmadi)
    public function incrementView(Request $request, Post $post)
    {
        $ip = $request->ip();
        $userId = Auth::id();

        $query = PostView::where('post_id', $post->id);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $ip)->whereNull('user_id');
        }

        if ($query->exists()) {
            return response()->json(['views' => $post->views()->count()]);
        }

        PostView::create([
            'post_id' => $post->id,
            'user_id' => $userId,
            'ip_address' => $ip,
            'user_agent' => $request->header('User-Agent'),
        ]);

        return response()->json(['views' => $post->views()->count()]);
    }
}