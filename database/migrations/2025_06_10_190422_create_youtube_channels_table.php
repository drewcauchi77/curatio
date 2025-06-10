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
        Schema::create('youtube_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('channel_id')->unique();
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->dateTime('last_synced_at')->default(now());
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
