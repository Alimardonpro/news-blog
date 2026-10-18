<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'body', 'image', 'theme'];

    public static function themes(): array
    {
        return [
            'cosmic' => [
                'name' => 'Cosmic',
                'class' => 'from-[#1e1b4b] via-[#312e81] to-[#0f172a]',
                'preview' => 'linear-gradient(135deg, #1e1b4b, #312e81, #0f172a)',
                'accent' => '#818cf8',
            ],
            'sunset' => [
                'name' => 'Sunset',
                'class' => 'from-[#881337] via-[#be123c] to-[#4c0519]',
                'preview' => 'linear-gradient(135deg, #881337, #be123c, #4c0519)',
                'accent' => '#fb7185',
            ],
            'emerald' => [
                'name' => 'Emerald',
                'class' => 'from-[#022c22] via-[#064e3b] to-[#021d17]',
                'preview' => 'linear-gradient(135deg, #022c22, #064e3b, #021d17)',
                'accent' => '#34d399',
            ],
            'ocean' => [
                'name' => 'Ocean',
                'class' => 'from-[#0c4a6e] via-[#0369a1] to-[#082f49]',
                'preview' => 'linear-gradient(135deg, #0c4a6e, #0369a1, #082f49)',
                'accent' => '#38bdf8',
            ],
            'cyber' => [
                'name' => 'Cyber',
                'class' => 'from-[#4c1d95] via-[#6d28d9] to-[#1e1b4b]',
                'preview' => 'linear-gradient(135deg, #4c1d95, #6d28d9, #1e1b4b)',
                'accent' => '#c084fc',
            ],
            'midnight' => [
                'name' => 'Midnight',
                'class' => 'from-[#0f172a] via-[#1e293b] to-[#020617]',
                'preview' => 'linear-gradient(135deg, #0f172a, #1e293b, #020617)',
                'accent' => '#94a3b8',
            ],
        ];
    }

    public function getGradientClassAttribute(): string
    {
        $themes = self::themes();
        if ($this->theme && isset($themes[$this->theme])) {
            return $themes[$this->theme]['class'];
        }

        $keys = array_keys($themes);
        $index = abs($this->id ?? 1) % count($keys);
        return $themes[$keys[$index]]['class'];
    }

    public function getGradientStyleAttribute(): string
    {
        $themes = self::themes();
        if ($this->theme && isset($themes[$this->theme])) {
            return $themes[$this->theme]['preview'];
        }

        $keys = array_keys($themes);
        $index = abs($this->id ?? 1) % count($keys);
        return $themes[$keys[$index]]['preview'];
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

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