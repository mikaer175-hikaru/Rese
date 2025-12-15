<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class Shop extends Model
{
    protected $fillable = [
        'name',
        'area_id',
        'genre_id',
        'image_url',
        'description',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /** スコープ：店舗名の部分一致 */
    public function scopeNameLike($query, ?string $keyword)
    {
        if (!is_null($keyword) && $keyword !== '') {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }
        return $query;
    }

    /** スコープ：エリアIDで絞り込み */
    public function scopeAreaId($query, $areaId)
    {
        if (!empty($areaId)) {
            $query->where('area_id', (int)$areaId);
        }
        return $query;
    }

    /** スコープ：ジャンルIDで絞り込み */
    public function scopeGenreId($query, $genreId)
    {
        if (!empty($genreId)) {
            $query->where('genre_id', (int)$genreId);
        }
        return $query;
    }

    public function getImageUrlAttribute($value): ?string
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // すでにURLならそのまま返す（http/https）
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        // storageの相対パス（例: shop_images/xxx.jpg）なら、diskに応じたURLへ変換
        return Storage::url($value);
    }
}
