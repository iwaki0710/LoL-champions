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
        Schema::create('items', function (Blueprint $table) {
            $table->string('id')->primary(); // Riot APIのアイテムID
            $table->string('name');
            $table->text('description'); // アイテムの効果 (HTML形式)
            $table->text('plaintext');   // アイテムの簡単な説明
            $table->integer('total_gold');
            $table->json('tags');        // アイテムのカテゴリタグ 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};