<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Rasm yo'lini saqlash uchun ustun qo'shamiz
            $table->string('image')->nullable()->after('message');
            
            // Xabar matni bo'lmasligi ham mumkin (faqat rasm yuborsa)
            $table->string('message')->nullable()->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
