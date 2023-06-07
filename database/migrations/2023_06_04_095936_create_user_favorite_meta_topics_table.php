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
        Schema::create('user_favorite_meta_topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('meta_topic_id');
            $table->integer('user_id');

            $table->foreign('meta_topic_id')->references('id')->on('meta_topics');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favorite_meta_topics');
    }
};
