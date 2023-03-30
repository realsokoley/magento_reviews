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
        Schema::create('topic_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('topic_id')->nullable()->index('topic_id');
            $table->integer('level_id')->nullable()->index('level_id');
            $table->integer('word_list_id')->nullable()->index('word_list_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_levels');
    }
};
