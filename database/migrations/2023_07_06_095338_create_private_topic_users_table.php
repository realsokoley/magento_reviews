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
        Schema::create('private_topic_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('private_topic_id');
            $table->foreign('private_topic_id')->references('id')->on('private_topics');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_topic_users');
    }
};
