<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('runes', function (Blueprint $table) {
            $table->string('id')->primary(); // ルーンのID (例: "Precision")
            $table->string('key')->comment('パスのキー (例: "perk-8000")');
            $table->string('icon_path');
            $table->string('name');
            $table->text('short_desc')->comment('短い説明');
            $table->text('long_desc')->comment('詳細な説明');
            $table->string('path_id')->comment('所属するパスのID (例: "Precision")');
            $table->integer('slot_index')->comment('ルーンのスロット位置 (0:キーストーン, 1-3:通常スロット)');
            $table->boolean('is_keystone')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('runes');
    }
};