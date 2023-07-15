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
        Schema::create('private_topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('topic');
            $table->text('description');
            $table->boolean('ai_words');
            $table->integer('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_topics');
    }
};
