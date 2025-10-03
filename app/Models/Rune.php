<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rune extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
}
