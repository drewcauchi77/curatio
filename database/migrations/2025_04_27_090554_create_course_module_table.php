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
        Schema::create('course_module', function (Blueprint $table) {
            $table->foreignUuid('course_id')->nullable()->index();
            $table->foreignUuid('module_id')->nullable()->index();
            $table->primary(['course_id', 'module_id']);
        });
    }
};
