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
        Schema::create('topic_level_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('topic_level_id')->nullable()->index('topic_level_id');
            $table->integer('task_id')->nullable()->index('task_id');
            $table->text('task_data')->nullable()->index('task_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_level_tasks');
    }
};
