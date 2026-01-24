<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'body', 'image'];

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest(); // Eng yangi izohlar tepada
    }

    // Postning egasi (Kim yozgan?)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Postga bosilgan likelar
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Yordamchi funksiya: Hozirgi kirgan odam bu postga like bosganmi?
    // Frontendda yurakchani qizil yoki bo'sh ko'rsatish uchun kerak.
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}