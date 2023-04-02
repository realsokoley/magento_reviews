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
        Schema::create('dialogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('word_list_id');
            $table->text('dialog_data')->nullable();

            $table->foreign('word_list_id')->references('id')->on('word_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialogs');
    }
};
