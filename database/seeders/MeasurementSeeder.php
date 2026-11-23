<?php

namespace Database\Seeders;

use App\Models\Measurement;
use App\Models\Pond;
use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            'Ikan Nila' => ['temperature' => 28.0, 'do' => 5.4, 'ph' => 7.4, 'ammonia' => 0.012, 'alkalinity' => 112],
            'Ikan Lele Dumbo' => ['temperature' => 28.5, 'do' => 4.3, 'ph' => 7.0, 'ammonia' => 0.028, 'alkalinity' => 96],
            'Ikan Mas' => ['temperature' => 26.5, 'do' => 5.6, 'ph' => 7.2, 'ammonia' => 0.018, 'alkalinity' => 121],
        ];

        $ponds = Pond::with('fishes')->get();

        foreach ($ponds as $pond) {
            $fish = $pond->fishes->first();
            $profile = $profiles[$fish->name] ?? null;

            if (! $profile) {
                continue;
            }

            for ($day = 13; $day >= 0; $day--) {
                foreach (['06:00', '17:00'] as $index => $time) {
                    $date = now()->subDays($day)->format('Y-m-d').' '.$time;

                    // DO pagi lebih rendah; variasi harian dibuat deterministik agar data contoh stabil.
                    $doShift = $index === 0 ? -0.9 : 0.5;
                    $wave = sin($day / 2) * 0.5;
                    $jitter = (($day * 7 + $index * 3) % 5 - 2) / 10;

                    $temperature = round($profile['temperature'] + $wave * 0.6 + $jitter, 2);
                    $dissolvedOxygen = round($profile['do'] + $doShift + $wave * 0.3, 2);
                    $ph = round($profile['ph'] + $wave * 0.12 + $jitter * 0.3, 2);
                    $ammonia = round($profile['ammonia'] * (1 + $wave * 0.2) + max($jitter, 0) * 0.004, 3);
                    $alkalinity = round($profile['alkalinity'] + $wave * 8, 2);

                    $notes = null;

                    if ($fish->name === 'Ikan Nila' && $day === 11) {
                        $ammonia = 0.064;
                        $notes = 'Simulasi lonjakan amonia setelah pemberian pakan berlebih.';
                    }

                    if ($fish->name === 'Ikan Lele Dumbo' && $day === 4 && $index === 0) {
                        $dissolvedOxygen = 2.1;
                        $notes = 'Simulasi DO rendah pada pagi hari.';
                    }

                    Measurement::create([
                        'pond_id' => $pond->id,
                        'fish_id' => $fish->id,
                        'measured_at' => $date,
                        'temperature_c' => $temperature,
                        'dissolved_oxygen_mg_l' => $dissolvedOxygen,
                        'ph' => $ph,
                        'ammonia_mg_l' => $ammonia,
                        'ammonia_type' => 'nh3',
                        'alkalinity_mg_l' => $alkalinity,
                        'source' => Measurement::SOURCE_SIMULATION,
                        'notes' => $notes ?? 'Data simulasi untuk pengembangan tahap awal.',
                    ]);
                }
            }
        }
    }
}
