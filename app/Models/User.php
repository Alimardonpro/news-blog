<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username', 
        'email',
        'password',
        'avatar', 
        'banner',
        'bio',     
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 1. Mening Postlarim (User -> Posts)
    public function posts()
    {
        return $this->hasMany(Post::class)->latest(); // Eng yangilari birinchi chiqadi
    }

    // 2. Men bosgan Likelar (User -> Likes)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 3. Men obuna bo'lganlar (Men kuzatayotgan odamlar)
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    // 4. Menga obuna bo'lganlar (Mening obunachilarim)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    // 5. Yordamchi funksiya: Men shu odamni kuzatyapmanmi?
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    // 6. Foydalanuvchi yuborgan xabarlar (Chat uchun muhim)
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // 7. Foydalanuvchi qabul qilgan xabarlar (Chat uchun muhim)
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}