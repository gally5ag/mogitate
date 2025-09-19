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
        if (!$this->season) return null;

        // '春,夏' / '春 夏' / 配列 いずれにも対応
        $raw = is_array($this->season)
            ? $this->season
            : preg_split('/[,\s]+/u', trim((string) $this->season), -1, PREG_SPLIT_NO_EMPTY);

        // 正規化＆不正値除去
        $valid = ['春', '夏', '秋', '冬'];
        $picked = array_values(array_intersect(array_unique($raw), $valid));

        return $picked ? implode('・', $picked) : null; // 例: 春・夏
    }
}
