<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->string('gameName');
            $table->string('tagLine');
            $table->string('puuid'); // 対戦履歴ページへのリンクに必須
            $table->timestamps(); // created_atで検索順を管理
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};