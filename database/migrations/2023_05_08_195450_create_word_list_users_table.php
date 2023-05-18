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
        Schema::create('word_list_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('word_list_id');
            $table->integer('user_id');
            $table->unsignedBigInteger('rating')->nullable();

            $table->foreign('word_list_id')->references('id')->on('word_lists');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('word_list_users');
    }
};
