<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
           $table->id();
            // Xabarni kim yubordi?
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            // Xabarni kim qabul qildi?
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            // Xabar matni
            $table->text('message');
            // O'qilganlik holati (boshida false bo'ladi)
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
