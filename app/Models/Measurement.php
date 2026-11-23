<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    use HasFactory;

    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_IOT = 'iot';
    public const SOURCE_SIMULATION = 'simulation';

    protected $fillable = [
        'pond_id',
        'fish_id',
        'measured_at',
        'temperature_c',
        'dissolved_oxygen_mg_l',
        'ph',
        'ammonia_mg_l',
        'ammonia_type',
        'alkalinity_mg_l',
        'source',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'measured_at' => 'datetime',
            'temperature_c' => 'float',
            'dissolved_oxygen_mg_l' => 'float',
            'ph' => 'float',
            'ammonia_mg_l' => 'float',
            'alkalinity_mg_l' => 'float',
        ];
    }

    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }

    public function fish(): BelongsTo
    {
        return $this->belongsTo(Fish::class);
    }

    public function isSimulation(): bool
    {
        return $this->source === self::SOURCE_SIMULATION;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['pond_id'] ?? null, fn (Builder $q, $pondId) => $q->where('pond_id', $pondId))
            ->when($filters['fish_id'] ?? null, fn (Builder $q, $fishId) => $q->where('fish_id', $fishId))
            ->when($filters['source'] ?? null, fn (Builder $q, $source) => $q->where('source', $source))
            ->when($filters['from'] ?? null, fn (Builder $q, $from) => $q->whereDate('measured_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $q, $to) => $q->whereDate('measured_at', '<=', $to))
            ->when($filters['search'] ?? null, function (Builder $q, $search) {
                $q->where(function (Builder $inner) use ($search) {
                    $inner->where('notes', 'like', "%{$search}%")
                        ->orWhereHas('pond', fn (Builder $pond) => $pond->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('fish', fn (Builder $fish) => $fish->where('name', 'like', "%{$search}%"));
                });
            });
    }
}
