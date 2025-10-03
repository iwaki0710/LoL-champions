<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summoner_spells', function (Blueprint $table) {
            $table->string('id')->primary(); // 例: SummonerFlash
            $table->string('name');          // 例: フラッシュ
            $table->text('description');
            $table->integer('cooldown');
            $table->string('key')->unique(); // 例: 4
            $table->string('image_full');    // 例: SummonerFlash.png
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summoner_spells');
    }
};