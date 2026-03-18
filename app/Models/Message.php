<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    // Xabarni yuborgan foydalanuvchi
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Xabarni qabul qilgan foydalanuvchi
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}