<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['studio_id', 'seat_code', 'row', 'number', 'type'];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}