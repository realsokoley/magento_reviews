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
        Schema::create('private_topic_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('word_list_id');

            $table->foreign('topic_id')->references('id')->on('private_topics');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->foreign('word_list_id')->references('id')->on('word_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_topic_levels');
    }
};
