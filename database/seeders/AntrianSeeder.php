<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Loket;
use App\Models\Antrian;
use Carbon\Carbon;

class AntrianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $lokets = Loket::all();
        if ($lokets->isEmpty()) {
            return; // LoketSeeder should run first
        }

        foreach ($lokets as $index => $loket) {
            // Generate a few waiting numbers per loket
            for ($i = 1; $i <= 3; $i++) {
                Antrian::create([
                    'loket_id' => $loket->id,
                    'nomor_antrian' => sprintf('%s%03d', $loket->code ?: chr(65 + $index), $i),
                    'status' => 'menunggu',
                    'waktu_panggil' => null,
                    'created_at' => $now->copy()->subMinutes(30 - ($i * 2)),
                    'updated_at' => $now,
                ]);
            }

            // Mark the last one as called for the first two lokets to demo display
            if ($index < 2) {
                $called = Antrian::create([
                    'loket_id' => $loket->id,
                    'nomor_antrian' => sprintf('%s%03d', $loket->code ?: chr(65 + $index), 4),
                    'status' => 'dipanggil',
                    'waktu_panggil' => $now->copy()->subMinutes(1 + $index),
                    'created_at' => $now->copy()->subMinutes(5),
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
