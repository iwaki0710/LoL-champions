<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // 主キーが数値の自動増分ではないことを示す
    public $incrementing = false;

    // 主キーの型が文字列であることを示す
    protected $keyType = 'string';

    // tagsカラムを自動的に配列に変換する
    protected $casts = [
        'tags' => 'array',
    ];
}
