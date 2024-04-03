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
        Schema::table('deleted_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('chat_group_id')->nullable();
            $table->index('chat_group_id');
            $table->foreign('chat_group_id')->references('id')->on('chat_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deleted_messages', function (Blueprint $table) {
            $table->dropForeign('deleted_messages_chat_group_id_foreign');
            $table->dropIndex('deleted_messages_chat_group_id_index');
            $table->dropColumn('chat_group_id');
        });
    }
};
