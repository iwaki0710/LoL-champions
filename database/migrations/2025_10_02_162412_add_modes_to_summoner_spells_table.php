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
    Schema::table('summoner_spells', function (Blueprint $table) {
        // ゲームモードをJSON形式で保存するカラムを追加
        $table->json('modes')->nullable()->after('image_full');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summoner_spells', function (Blueprint $table) {
            //
        });
    }
};
