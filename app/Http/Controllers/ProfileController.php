<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
     * (Bu funksiya endi ishlatilmaydi, chunki view o'chirilgan, 
     * lekin kodda tursa xalaqit bermaydi)
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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // --- MUHIM TUZATISH ---
        // Oldin shunchaki route('profile.show') edi.
        // Endi ichiga ['username' => ...] qo'shdik.
        // Chunki route /@{username} ni kutayapti.
        
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