<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <--- MUHIM: Faylni o'chirish uchun kerak
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Boshqa foydalanuvchining profilini ko'rsatish (@username).
     */
    public function show($username)
    {
        // 1. Bazadan username bo'yicha qidiramiz
        // firstOrFail -> Agar topilmasa 404 xato beradi
        $user = User::where('username', $username)->firstOrFail();

        // 2. Profile sahifasini ochamiz va topilgan $user ni yuboramiz
        return view('profile', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validatsiya qilingan ma'lumotlarni to'ldirish
        $request->user()->fill($request->validated());

        // Agar email o'zgargan bo'lsa, verifikatsiyani bekor qilish
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // --- 1. AVATAR YUKLASH MANTIQI ---
        if ($request->hasFile('avatar')) {
            // Agar eski avatar bo'lsa, uni o'chirib tashlaymiz
            if ($request->user()->avatar) {
                Storage::disk('public')->delete($request->user()->avatar);
            }
            // Yangi avatarni saqlaymiz
            $path = $request->file('avatar')->store('avatars', 'public');
            $request->user()->avatar = $path;
        }

        // --- 2. BANNER YUKLASH MANTIQI (YANGI) ---
        if ($request->hasFile('banner')) {
            // Agar eski banner bo'lsa, uni o'chirib tashlaymiz
            if ($request->user()->banner) {
                Storage::disk('public')->delete($request->user()->banner);
            }
            // Yangi bannerni saqlaymiz
            $path = $request->file('banner')->store('banners', 'public');
            $request->user()->banner = $path;
        }

        // O'zgarishlarni saqlash
        $request->user()->save();

        // Profil sahifasiga qaytarish (username bilan)
        return Redirect::route('profile.show', ['username' => $request->user()->username])
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}