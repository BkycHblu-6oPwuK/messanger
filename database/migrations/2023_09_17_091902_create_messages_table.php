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
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('chat_group_id')->nullable(); // групповые сообщения
            $table->text('body');
            $table->timestamps();

            $table->index('sender_id');

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_group_id')->references('id')->on('chat_groups')->onDelete('set null');
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
