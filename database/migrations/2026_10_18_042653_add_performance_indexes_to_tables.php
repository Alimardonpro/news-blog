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
        Schema::table('post_views', function (Blueprint $table) {
            $table->index(['post_id', 'user_id'], 'post_views_post_user_idx');
            $table->index(['post_id', 'ip_address'], 'post_views_post_ip_idx');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index(['receiver_id', 'is_read'], 'messages_receiver_read_idx');
            $table->index(['sender_id', 'receiver_id', 'created_at'], 'messages_conversation_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('last_seen', 'users_last_seen_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_views', function (Blueprint $table) {
            $table->dropIndex('post_views_post_user_idx');
            $table->dropIndex('post_views_post_ip_idx');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_receiver_read_idx');
            $table->dropIndex('messages_conversation_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_last_seen_idx');
        });
    }
};
