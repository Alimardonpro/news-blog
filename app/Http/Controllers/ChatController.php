<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Events\MessageSent;
use App\Events\MessageRead;

class ChatController extends Controller
{
    // 1. Asosiy chat sahifasi
    public function index(Request $request)
    {
        $myId = Auth::id();
        $hasIsRead = Schema::hasColumn('messages', 'is_read');

        $users = User::where('id', '!=', $myId)
            ->where(function ($query) use ($myId) {
                $query->whereHas('sentMessages', function ($q) use ($myId) {
                    $q->where('receiver_id', $myId);
                })->orWhereHas('receivedMessages', function ($q) use ($myId) {
                    $q->where('sender_id', $myId);
                });
            })
            ->withCount(['sentMessages as unread_count' => function ($query) use ($myId, $hasIsRead) {
                $query->where('receiver_id', $myId);

                if ($hasIsRead) {
                    $query->where('is_read', false);
                } else {
                    $query->whereNull('read_at');
                }
            }])
            ->get();

        $startUser = null;

        if ($request->has('user_id')) {
            $startUser = User::find($request->get('user_id'));

            if ($startUser && !$users->contains('id', $startUser->id)) {
                $startUser->unread_count = 0;
                $users->prepend($startUser);
            }
        }

        return view('chat', compact('users', 'startUser'));
    }

    // 2. Xabarlarni olish va o'qildi deb belgilash
    public function getMessages(User $user)
    {
        $myId = Auth::id();
        $hasIsRead = Schema::hasColumn('messages', 'is_read');

        $unreadQuery = Message::where('sender_id', $user->id)
            ->where('receiver_id', $myId);

        if ($hasIsRead) {
            $unreadQuery->where('is_read', false);
        } else {
            $unreadQuery->whereNull('read_at');
        }

        $unreadMessages = $unreadQuery->get();

        if ($unreadMessages->count() > 0) {
            foreach ($unreadMessages as $msg) {
                if ($hasIsRead) {
                    $msg->update(['is_read' => true]);
                } else {
                    $msg->update(['read_at' => now()]);
                }

                // Xabar yuborgan odamga: "sening xabaring o'qildi" deb signal yuboriladi
                broadcast(new MessageRead(
                    $msg->id,
                    $msg->sender_id,
                    $myId
                ))->toOthers();
            }
        }

        $messages = Message::where(function ($q) use ($myId, $user) {
            $q->where('sender_id', $myId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($myId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // 3. Xabar yuborish
    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'image'   => 'nullable|image|max:5120',
            'audio'   => 'nullable|file|max:10240',
            'video'   => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:51200',
        ]);

        if (!$request->filled('message') && !$request->hasFile('image') && !$request->hasFile('audio') && !$request->hasFile('video')) {
            return response()->json(['error' => 'Bo‘sh xabar yuborib bo‘lmaydi'], 422);
        }

        $data = [
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'message'     => $request->message,
            'image'       => $request->hasFile('image') ? $request->file('image')->store('chat_images', 'public') : null,
            'audio'       => $request->hasFile('audio') ? $request->file('audio')->store('chat_audios', 'public') : null,
            'video'       => $request->hasFile('video') ? $request->file('video')->store('chat_videos', 'public') : null,
        ];

        if (Schema::hasColumn('messages', 'is_read')) {
            $data['is_read'] = false;
        } elseif (Schema::hasColumn('messages', 'read_at')) {
            $data['read_at'] = null;
        }

        $message = Message::create($data);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    // 4. Umumiy o'qilmaganlar soni
    public function getTotalUnread()
    {
        $query = Message::where('receiver_id', Auth::id());

        if (Schema::hasColumn('messages', 'is_read')) {
            $query->where('is_read', false);
        } else {
            $query->whereNull('read_at');
        }

        $count = $query->count();
        $notifCount = Auth::user()->unreadNotifications->count();

        return response()->json([
            'count' => $count,
            'notif_count' => $notifCount
        ]);
    }

    // 5. Xabarni tahrirlash
    public function updateMessage(Request $request, Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Ruxsat etilmagan'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        $message->update([
            'message' => $request->message
        ]);

        return response()->json($message);
    }

    // 6. Xabarni o'chirish
    public function deleteMessage(Message $message)
    {
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Ruxsat etilmagan'], 403);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    // 7. Chatni tozalash
    public function clearChat(User $user)
    {
        $myId = Auth::id();

        Message::where(function ($q) use ($myId, $user) {
            $q->where('sender_id', $myId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($myId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $myId);
        })->delete();

        return response()->json(['success' => true]);
    }

    // 8. Global foydalanuvchilarni qidirish
    public function searchUsers(Request $request)
    {
        $q = $request->get('q');

        if (!$q) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%");
            })
            ->limit(20)
            ->get();

        $users->each(function ($user) {
            $user->unread_count = 0;
        });

        return response()->json($users);
    }
}