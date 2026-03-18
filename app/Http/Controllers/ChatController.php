<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // 1. Asosiy chat sahifasi
    public function index(Request $request)
    {
        $myId = Auth::id();
        
        // Faqat o'zaro yozishgan odamlarni olish
        $users = User::where('id', '!=', $myId)
            ->where(function ($query) use ($myId) {
                $query->whereHas('sentMessages', function($q) use ($myId) {
                    $q->where('receiver_id', $myId);
                })->orWhereHas('receivedMessages', function($q) use ($myId) {
                    $q->where('sender_id', $myId);
                });
            })
            ->get();

        // YANGI: Profildan "Xabar yuborish" bosilganda shu odamni ushlab olamiz
        $startWithUser = null;
        if ($request->has('start_with')) {
            $startWithUser = User::find($request->get('start_with'));
        }

        // View nomini o'zingiznikiga moslang (masalan, 'chat.index' yoki 'chat')
        return view('chat', compact('users', 'startWithUser')); 
    }

    // 2. Global qidiruv
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    // 3. Xabarlarni olish
    public function getMessages(User $user)
    {
        $myId = Auth::id();
        $messages = Message::where(function ($q) use ($myId, $user) {
            $q->where('sender_id', $myId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($myId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // 4. Xabar yuborish
    public function sendMessage(Request $request, User $user)
    {
        $request->validate(['message' => 'required|string|max:2000']);
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);
        return response()->json($message);
    }

    // 5. Xabarni tahrirlash
    public function updateMessage(Request $request, Message $message)
    {
        if ($message->sender_id === Auth::id()) {
            $request->validate(['message' => 'required|string|max:2000']);
            $message->update(['message' => $request->message]);
            return response()->json($message);
        }
        return response()->json(['error' => 'Ruxsat etilmagan'], 403);
    }

    // 6. Xabarni o'chirish
    public function destroyMessage(Message $message)
    {
        if ($message->sender_id === Auth::id()) {
            $message->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Ruxsat etilmagan'], 403);
    }

    // 7. Tarixni tozalash
    public function clearConversation(User $user)
    {
        $myId = Auth::id();
        Message::where(function ($q) use ($myId, $user) {
            $q->where('sender_id', $myId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($myId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $myId);
        })->delete();

        return response()->json(['success' => true]);
    }
}