<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pond extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'location',
        'volume_m3',
        'depth_m',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'volume_m3' => 'float',
            'depth_m' => 'float',
        ];
    }

    public function fishes(): BelongsToMany
    {
        return $this->belongsToMany(Fish::class, 'pond_fish')
            ->withPivot(['stock_date', 'population', 'is_active'])
            ->withTimestamps();
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function latestMeasurement(): HasOne
    {
        return $this->hasOne(Measurement::class)->latestOfMany('measured_at');
    }
}
