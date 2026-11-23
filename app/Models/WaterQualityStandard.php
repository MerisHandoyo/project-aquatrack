<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterQualityStandard extends Model
{
    use HasFactory;

    public const PARAMETERS = [
        'temperature' => [
            'column' => 'temperature_c',
            'label' => 'Suhu Air',
            'unit' => '°C',
            'step' => '0.1',
        ],
        'dissolved_oxygen' => [
            'column' => 'dissolved_oxygen_mg_l',
            'label' => 'Oksigen Terlarut (DO)',
            'unit' => 'mg/L',
            'step' => '0.01',
        ],
        'ph' => [
            'column' => 'ph',
            'label' => 'pH',
            'unit' => '',
            'step' => '0.01',
        ],
        'ammonia' => [
            'column' => 'ammonia_mg_l',
            'label' => 'Amonia',
            'unit' => 'mg/L',
            'step' => '0.001',
        ],
        'alkalinity' => [
            'column' => 'alkalinity_mg_l',
            'label' => 'Alkalinitas',
            'unit' => 'mg/L CaCO3',
            'step' => '0.01',
        ],
    ];

    public const AMMONIA_TYPES = [
        'nh3' => 'NH3 (amonia bebas)',
        'nh4' => 'NH4+ (amonium)',
        'tan' => 'TAN (total amonia nitrogen)',
    ];

    protected $fillable = [
        'fish_id',
        'parameter',
        'min_value',
        'max_value',
        'unit',
        'ammonia_type',
        'source',
        'notes',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'min_value' => 'float',
            'max_value' => 'float',
            'is_verified' => 'boolean',
        ];
    }

    public function fish(): BelongsTo
    {
        return $this->belongsTo(Fish::class);
    }

    public function parameterLabel(): string
    {
        return self::PARAMETERS[$this->parameter]['label'] ?? $this->parameter;
    }
}
