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

    // 3. Men obuna bo'lganlar (Following)
    // 'follows' jadvalidan foydalanamiz. Men 'follower_id' man.
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    // 4. Menga obuna bo'lganlar (Followers)
    // 'follows' jadvalidan foydalanamiz. Men 'following_id' man.
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    // Yordamchi funksiya: Men ma'lum bir odamga obuna bo'lganmanmi?
    // Frontendda "Follow" yoki "Unfollow" tugmasini ko'rsatish uchun kerak.
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }
}