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
            $table->text('initial_access_token');
            $table->dateTime('last_synced_at')->default(now());
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
