<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    // 以下の$fillableプロパティを追記
    protected $fillable = [
        'gameName',
        'tagLine',
        'puuid',
    ];
}