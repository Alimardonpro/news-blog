<?php

use Illuminate\Support\Facades\Broadcast;

// 1. Shaxsiy xabarlar uchun kanal (Faqat xabar egasi o'qiy olishi uchun himoya)
Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 2. Saytda kimlar ONLAYN ekanligini kuzatish uchun kanal (Presence channel)
Broadcast::channel('online', function ($user) {
    // Hozir tarmoqda bo'lgan foydalanuvchilarning ID va Ismini qaytaradi
    return ['id' => $user->id, 'name' => $user->name];
});