<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fish extends Model
{
    use HasFactory;

    protected $table = 'fishes';

    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function ponds(): BelongsToMany
    {
        return $this->belongsToMany(Pond::class, 'pond_fish')
            ->withPivot(['stock_date', 'population', 'is_active'])
            ->withTimestamps();
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function standards(): HasMany
    {
        return $this->hasMany(WaterQualityStandard::class);
    }
}
