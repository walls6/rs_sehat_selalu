<?php

namespace App\Services;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AntrianService
{
    /**
     * Generate a unique nomor_antrian for a loket for today.
     * If $providedNomor is given, it will be validated for uniqueness.
     * Returns created Antrian model.
     * Throws Exception on failure.
     */
    public function createForLoket($loket, ?string $providedNomor = null)
    {
        try {
            $nomorAntrian = $providedNomor;

            $today = Carbon::today();

            if (!$nomorAntrian) {
                $countToday = Antrian::where('loket_id', $loket->id)
                    ->whereDate('created_at', $today)
                    ->count();

                $nomorUrut = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
                $nomorAntrian = ($loket->code ?? '') . $nomorUrut;

                // Ensure uniqueness (safety loop)
                $counter = 1;
                while (Antrian::where('loket_id', $loket->id)
                    ->where('nomor_antrian', $nomorAntrian)
                    ->whereDate('created_at', $today)
                    ->exists()) {
                    $nomorUrut = str_pad($countToday + $counter + 1, 3, '0', STR_PAD_LEFT);
                    $nomorAntrian = ($loket->code ?? '') . $nomorUrut;
                    $counter++;
                    if ($counter > 1000) {
                        throw new \Exception('Tidak dapat menghasilkan nomor antrian unik.');
                    }
                }
            }

            // If caller provided a nomor_antrian, ensure it's unique for this loket today
            if ($providedNomor) {
                $exists = Antrian::where('loket_id', $loket->id)
                    ->where('nomor_antrian', $providedNomor)
                    ->whereDate('created_at', $today)
                    ->exists();

                if ($exists) {
                    throw new \Exception('Nomor antrian sudah digunakan untuk loket ini hari ini.');
                }
            }

            $antrian = Antrian::create([
                'loket_id' => $loket->id,
                'nomor_antrian' => $nomorAntrian,
                'status' => 'menunggu',
            ]);

            return $antrian;
        } catch (\Exception $e) {
            Log::error('AntrianService createForLoket error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update status for an antrian model.
     * Returns the updated model.
     */
    public function updateStatus(Antrian $antrian, string $status)
    {
        try {
            // Idempotency: if status already equals requested status, return as-is
            if ($antrian->status === $status) {
                // ensure waktu_panggil exists when requested status is 'dipanggil'
                if ($status === 'dipanggil' && !$antrian->waktu_panggil) {
                    $antrian->waktu_panggil = Carbon::now();
                    $antrian->save();
                }
                return $antrian->refresh();
            }

            $antrian->status = $status;

            if ($status === 'dipanggil') {
                $antrian->waktu_panggil = Carbon::now();
            }

            $antrian->save();

            return $antrian->refresh();
        } catch (\Exception $e) {
            Log::error('AntrianService updateStatus error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get waiting antrians for a loket
     */
    public function waitingForLoket($loket)
    {
        return Antrian::where('loket_id', $loket->id)
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get currently called antrians; optionally filter by loket_id
     */
    public function currentCalled($loketId = null)
    {
        $query = Antrian::where('status', 'dipanggil')
            ->with('loket')
            ->orderBy('waktu_panggil', 'desc');

        if ($loketId) {
            $query->where('loket_id', $loketId);
        }

        return $query->get();
    }
}
