<?php

namespace App\Services;

use App\Models\Measurement;
use App\Models\WaterQualityStandard;

class WaterQualityAnalyzer
{
    public const STATUS_OK = 'aman';
    public const STATUS_UNVERIFIED_OK = 'dalam_acuan';
    public const STATUS_WARNING = 'perlu_perhatian';
    public const STATUS_DANGER = 'bahaya';
    public const STATUS_NO_STANDARD = 'tanpa_acuan';
    public const STATUS_TYPE_MISMATCH = 'tidak_sebanding';

    private const SEVERITY = [
        self::STATUS_OK => 0,
        self::STATUS_UNVERIFIED_OK => 1,
        self::STATUS_WARNING => 2,
        self::STATUS_DANGER => 3,
    ];

    /**
     * Bandingkan satu pengukuran dengan standar milik jenis ikannya.
     */
    public function evaluate(Measurement $measurement): array
    {
        $measurement->loadMissing(['fish.standards']);

        $standards = $measurement->fish?->standards->keyBy('parameter') ?? collect();
        $parameters = [];

        foreach (WaterQualityStandard::PARAMETERS as $key => $meta) {
            $parameters[$key] = $this->evaluateParameter(
                $key,
                $meta,
                (float) $measurement->{$meta['column']},
                $standards->get($key),
                $measurement,
            );
        }

        return [
            'parameters' => $parameters,
            'overall' => $this->overall($parameters),
        ];
    }

    private function evaluateParameter(string $key, array $meta, float $value, ?WaterQualityStandard $standard, Measurement $measurement): array
    {
        $base = [
            'key' => $key,
            'label' => $meta['label'],
            'unit' => $meta['unit'],
            'value' => $value,
            'standard' => $standard ? [
                'min' => $standard->min_value,
                'max' => $standard->max_value,
                'unit' => $standard->unit,
                'source' => $standard->source,
                'is_verified' => $standard->is_verified,
                'ammonia_type' => $standard->ammonia_type,
            ] : null,
        ];

        if (! $standard) {
            return $base + [
                'status' => self::STATUS_NO_STANDARD,
                'label_status' => 'Referensi belum tersedia',
                'message' => 'Belum ada nilai acuan untuk parameter ini pada jenis ikan tersebut.',
            ];
        }

        if ($key === 'ammonia' && $standard->ammonia_type !== $measurement->ammonia_type) {
            return $base + [
                'status' => self::STATUS_TYPE_MISMATCH,
                'label_status' => 'Tidak dapat dibandingkan',
                'message' => 'Standar mengacu pada '.WaterQualityStandard::AMMONIA_TYPES[$standard->ammonia_type]
                    .', sedangkan data diukur sebagai '.WaterQualityStandard::AMMONIA_TYPES[$measurement->ammonia_type].'.',
            ];
        }

        $belowMin = $standard->min_value !== null && $value < $standard->min_value;
        $aboveMax = $standard->max_value !== null && $value > $standard->max_value;
        $inRange = ! $belowMin && ! $aboveMax;

        if ($inRange) {
            return $base + [
                'status' => $standard->is_verified ? self::STATUS_OK : self::STATUS_UNVERIFIED_OK,
                'label_status' => $standard->is_verified ? 'Sesuai acuan' : 'Dalam acuan (belum tervalidasi)',
                'message' => $standard->is_verified
                    ? 'Nilai berada dalam rentang acuan.'
                    : 'Nilai masih dalam rentang acuan, tetapi nilai acuan belum tervalidasi.',
            ];
        }

        $range = $this->rangeText($standard);

        return $base + [
            'status' => $standard->is_verified ? self::STATUS_DANGER : self::STATUS_WARNING,
            'label_status' => $standard->is_verified ? 'Di luar acuan' : 'Di luar acuan (belum tervalidasi)',
            'message' => ($belowMin ? 'Nilai di bawah batas acuan' : 'Nilai di atas batas acuan')." ({$range}).",
        ];
    }

    private function rangeText(WaterQualityStandard $standard): string
    {
        $unit = $standard->unit ? ' '.$standard->unit : '';

        if ($standard->min_value !== null && $standard->max_value !== null) {
            return $standard->min_value.'–'.$standard->max_value.$unit;
        }

        if ($standard->min_value !== null) {
            return 'minimal '.$standard->min_value.$unit;
        }

        return 'maksimal '.$standard->max_value.$unit;
    }

    private function overall(array $parameters): array
    {
        $evaluated = collect($parameters)
            ->filter(fn (array $parameter) => isset(self::SEVERITY[$parameter['status']]))
            ->values();

        if ($evaluated->isEmpty()) {
            return [
                'status' => self::STATUS_NO_STANDARD,
                'label' => 'Referensi belum tersedia',
                'message' => 'Belum ada nilai acuan yang dapat dipakai untuk menilai kondisi kolam ini.',
                'issues' => 0,
            ];
        }

        $highest = $evaluated->max(fn (array $parameter) => self::SEVERITY[$parameter['status']]);
        $issues = $evaluated->filter(fn (array $parameter) => self::SEVERITY[$parameter['status']] >= 2)->count();

        return match (true) {
            $highest >= 3 => [
                'status' => self::STATUS_DANGER,
                'label' => 'Bahaya',
                'message' => $issues.' parameter di luar batas acuan.',
                'issues' => $issues,
            ],
            $highest === 2 => [
                'status' => self::STATUS_WARNING,
                'label' => 'Perlu perhatian',
                'message' => $issues.' parameter di luar acuan yang belum tervalidasi.',
                'issues' => $issues,
            ],
            $highest === 1 => [
                'status' => self::STATUS_UNVERIFIED_OK,
                'label' => 'Dalam acuan',
                'message' => 'Seluruh parameter berada dalam rentang acuan, namun nilai acuan belum tervalidasi.',
                'issues' => 0,
            ],
            default => [
                'status' => self::STATUS_OK,
                'label' => 'Baik',
                'message' => 'Seluruh parameter yang memiliki acuan berada dalam rentang.',
                'issues' => 0,
            ],
        };
    }

    /**
     * Estimasi fraksi NH3 dari TAN memakai persamaan Emerson et al. (1975).
     */
    public function nh3Fraction(float $ph, float $temperatureC): float
    {
        $pKa = 0.09018 + (2729.92 / ($temperatureC + 273.15));

        return 1 / (1 + (10 ** ($pKa - $ph)));
    }

    public function estimatedNh3(float $tanMgL, float $ph, float $temperatureC): float
    {
        return $tanMgL * $this->nh3Fraction($ph, $temperatureC);
    }
}
