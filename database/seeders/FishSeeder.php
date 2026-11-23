<?php

namespace Database\Seeders;

use App\Models\Fish;
use Illuminate\Database\Seeder;

class FishSeeder extends Seeder
{
    public function run(): void
    {
        $fishes = [
            [
                'name' => 'Ikan Nila',
                'scientific_name' => 'Oreochromis niloticus',
                'description' => 'Ikan air tawar yang banyak dibudidayakan di kolam dan keramba. Toleran terhadap kisaran kualitas air yang cukup lebar, namun pertumbuhan optimal pada suhu hangat.',
            ],
            [
                'name' => 'Ikan Lele Dumbo',
                'scientific_name' => 'Clarias gariepinus',
                'description' => 'Ikan berkumis (catfish) yang mampu bertahan pada kadar oksigen rendah. Tetap memerlukan pemantauan DO dan amonia agar tidak stres.',
            ],
            [
                'name' => 'Ikan Mas',
                'scientific_name' => 'Cyprinus carpio',
                'description' => 'Ikan air tawar populer di Indonesia. Data acuan kualitas air untuk jenis ini belum dimasukkan ke sistem.',
            ],
            [
                'name' => 'Ikan Gurame',
                'scientific_name' => 'Osphronemus goramy',
                'description' => 'Ikan labirinta yang dapat mengambil oksigen langsung dari udara. Data acuan kualitas air untuk jenis ini belum dimasukkan ke sistem.',
            ],
            [
                'name' => 'Ikan Patin',
                'scientific_name' => 'Pangasius hypophthalmus',
                'description' => 'Ikan air tawar bernilai ekonomi tinggi. Data acuan kualitas air untuk jenis ini belum dimasukkan ke sistem.',
            ],
        ];

        foreach ($fishes as $fish) {
            Fish::create($fish);
        }

        $this->seedStandards();
    }

    /**
     * Nilai acuan awal diambil dari ringkasan pedoman budidaya.
     * Sengaja ditandai belum tervalidasi agar status di aplikasi tidak menyesatkan.
     */
    private function seedStandards(): void
    {
        $nila = Fish::where('name', 'Ikan Nila')->firstOrFail();
        $lele = Fish::where('name', 'Ikan Lele Dumbo')->firstOrFail();

        $nila->standards()->createMany([
            [
                'parameter' => 'temperature',
                'min_value' => 25,
                'max_value' => 32,
                'unit' => '°C',
                'source' => 'FAO – Cultured Aquatic Species: Oreochromis niloticus (ringkasan parameter air)',
                'notes' => 'Suhu memengaruhi laju metabolisme dan proporsi amonia bebas NH3.',
            ],
            [
                'parameter' => 'dissolved_oxygen',
                'min_value' => 3,
                'max_value' => null,
                'unit' => 'mg/L',
                'source' => 'FAO – Cultured Aquatic Species: Oreochromis niloticus (ringkasan parameter air)',
                'notes' => 'Nilai DO dapat turun pada malam hingga pagi hari karena respirasi dan dekomposisi.',
            ],
            [
                'parameter' => 'ph',
                'min_value' => 6.5,
                'max_value' => 9.0,
                'unit' => '',
                'source' => 'FAO – Cultured Aquatic Species: Oreochromis niloticus (ringkasan parameter air)',
                'notes' => 'pH tinggi meningkatkan proporsi amonia bebas NH3.',
            ],
            [
                'parameter' => 'ammonia',
                'min_value' => null,
                'max_value' => 0.02,
                'unit' => 'mg/L',
                'ammonia_type' => 'nh3',
                'source' => 'Boyd, C.E. – Water Quality in Ponds for Aquaculture (ringkasan NH3-N)',
                'notes' => 'Nilai mengacu pada NH3-N (amonia bebas), bukan TAN atau NH4+.',
            ],
            [
                'parameter' => 'alkalinity',
                'min_value' => 50,
                'max_value' => 150,
                'unit' => 'mg/L CaCO3',
                'source' => 'Boyd, C.E. – Water Quality in Ponds for Aquaculture (ringkasan alkalinitas)',
                'notes' => 'Alkalinitas berperan sebagai penyangga perubahan pH.',
            ],
        ]);

        $lele->standards()->createMany([
            [
                'parameter' => 'temperature',
                'min_value' => 25,
                'max_value' => 30,
                'unit' => '°C',
                'source' => 'KKP – Petunjuk Teknis Budidaya Ikan Lele (ringkasan parameter air)',
                'notes' => 'Suhu memengaruhi nafsu makan dan laju pertumbuhan.',
            ],
            [
                'parameter' => 'dissolved_oxygen',
                'min_value' => 3,
                'max_value' => null,
                'unit' => 'mg/L',
                'source' => 'KKP – Petunjuk Teknis Budidaya Ikan Lele (ringkasan parameter air)',
                'notes' => 'Meskipun toleran, DO rendah berkepanjangan meningkatkan risiko stres.',
            ],
            [
                'parameter' => 'ph',
                'min_value' => 6.5,
                'max_value' => 8.5,
                'unit' => '',
                'source' => 'KKP – Petunjuk Teknis Budidaya Ikan Lele (ringkasan parameter air)',
                'notes' => 'Perubahan pH di luar kisaran dapat memengaruhi kesehatan insang.',
            ],
            [
                'parameter' => 'ammonia',
                'min_value' => null,
                'max_value' => 0.05,
                'unit' => 'mg/L',
                'ammonia_type' => 'nh3',
                'source' => 'Boyd, C.E. – Water Quality in Ponds for Aquaculture (ringkasan NH3-N)',
                'notes' => 'Nilai mengacu pada NH3-N (amonia bebas), bukan TAN atau NH4+.',
            ],
            [
                'parameter' => 'alkalinity',
                'min_value' => 50,
                'max_value' => 150,
                'unit' => 'mg/L CaCO3',
                'source' => 'Boyd, C.E. – Water Quality in Ponds for Aquaculture (ringkasan alkalinitas)',
                'notes' => 'Alkalinitas berperan sebagai penyangga perubahan pH.',
            ],
        ]);
    }
}
