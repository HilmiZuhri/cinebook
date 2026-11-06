<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_id',
        'tmdb_movie_id',
        'movie_title',
        'movie_poster',
        'start_time',
        'end_time',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Cek apakah ada jadwal yang bertabrakan di studio yang sama
     */
    public static function hasOverlap(int $studioId, string $start, string $end, ?int $excludeId = null): bool
    {
        return self::where('studio_id', $studioId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($start, $end) {
                // Formula tabrakan waktu:
                // (StartA < EndB) AND (EndA > StartB)
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })
            ->exists();
    }
}