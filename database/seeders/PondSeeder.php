<?php

namespace Database\Seeders;

use App\Models\Fish;
use App\Models\Pond;
use Illuminate\Database\Seeder;

class PondSeeder extends Seeder
{
    public function run(): void
    {
        $ponds = [
            [
                'code' => 'K-01',
                'name' => 'Kolam Nila A',
                'location' => 'Blok A',
                'volume_m3' => 120,
                'depth_m' => 1.5,
                'notes' => 'Kolam tanah dengan sirkulasi air sederhana.',
                'fish' => ['Ikan Nila', '2026-10-01', 2000],
            ],
            [
                'code' => 'K-02',
                'name' => 'Kolam Lele B',
                'location' => 'Blok B',
                'volume_m3' => 80,
                'depth_m' => 1.2,
                'notes' => 'Kolam terpal untuk pembesaran lele.',
                'fish' => ['Ikan Lele Dumbo', '2026-10-10', 1500],
            ],
            [
                'code' => 'K-03',
                'name' => 'Kolam Nila C',
                'location' => 'Blok C',
                'volume_m3' => 100,
                'depth_m' => 1.4,
                'notes' => null,
                'fish' => ['Ikan Nila', '2026-10-20', 1800],
            ],
            [
                'code' => 'K-04',
                'name' => 'Kolam Ikan Mas D',
                'location' => 'Blok D',
                'volume_m3' => 90,
                'depth_m' => 1.3,
                'notes' => 'Belum memiliki data acuan kualitas air di sistem.',
                'fish' => ['Ikan Mas', '2026-10-25', 1200],
            ],
        ];

        foreach ($ponds as $data) {
            $pond = Pond::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'location' => $data['location'],
                'volume_m3' => $data['volume_m3'],
                'depth_m' => $data['depth_m'],
                'notes' => $data['notes'],
            ]);

            $fish = Fish::where('name', $data['fish'][0])->firstOrFail();

            $pond->fishes()->attach($fish->id, [
                'stock_date' => $data['fish'][1],
                'population' => $data['fish'][2],
                'is_active' => true,
            ]);
        }
    }
}
