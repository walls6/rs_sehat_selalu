<?php

namespace Database\Seeders;

use App\Models\Loket;
use Illuminate\Database\Seeder;

class LoketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokets = [
            [
                'nama_loket' => 'Pendaftaran Umum',
                'code' => 'A',
                'deskripsi' => 'Loket untuk pendaftaran pasien umum',
            ],
            [
                'nama_loket' => 'Poli Gigi',
                'code' => 'B',
                'deskripsi' => 'Loket untuk layanan poli gigi',
            ],
            [
                'nama_loket' => 'Farmasi',
                'code' => 'C',
                'deskripsi' => 'Loket untuk pengambilan obat',
            ],
            [
                'nama_loket' => 'Poli Anak',
                'code' => 'D',
                'deskripsi' => 'Loket untuk layanan poli anak',
            ],
            [
                'nama_loket' => 'Poli Dalam',
                'code' => 'E',
                'deskripsi' => 'Loket untuk layanan poli penyakit dalam',
            ],
        ];

        foreach ($lokets as $loket) {
            Loket::create($loket);
        }
    }
}

