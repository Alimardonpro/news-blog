<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'message', 
        'image',    
        'audio',    
        'video',     
        'is_read', 
        'read_at'
    ];

    // YANGI: Chatga fayl hajmini jo'natamiz
    protected $appends = ['media_size'];

    public function getMediaSizeAttribute()
    {
        // Rasm, video yoki audio bormi tekshiramiz
        $file = $this->video ?: ($this->image ?: $this->audio);
        
        if ($file && !str_starts_with($file, 'data:') && !str_starts_with($file, 'blob:')) {
            if (Storage::disk('public')->exists($file)) {
                $bytes = Storage::disk('public')->size($file); // Hajmini baytda olamiz
                
                // O'qilishi oson formatga o'tkazamiz
                if ($bytes >= 1048576) {
                    return round($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    return round($bytes / 1024, 0) . ' KB';
                } else {
                    return $bytes . ' bayt';
                }
            }
        }
        return null; // Fayl yo'q bo'lsa
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}