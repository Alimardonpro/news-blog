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
        Schema::create('post_views', function (Blueprint $table) {
        $table->id();
        $table->foreignId('post_id')->constrained()->cascadeOnDelete(); // Qaysi post
        $table->foreignId('user_id')->nullable(); // Kim ko'rdi (mehmon bo'lishi ham mumkin)
        $table->string('ip_address', 45); // IP manzil (spamdan himoya)
        $table->string('user_agent')->nullable(); // Brauzer turi
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
