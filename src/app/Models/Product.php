<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image_path',
        'season',
        'description'
    ];

    // 表示用ヘルパ
    public function getSeasonLabelAttribute(): ?string
    {
        return [1 => '春', 2 => '夏', 3 => '秋', 4 => '冬'][$this->season] ?? null;
    }
}
