<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Loket;
use App\Models\Antrian;
use App\Services\AntrianService;

class AntrianServiceProvidedNomorTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_for_loket_with_provided_nomor_conflict_throws()
    {
        $loket = Loket::create([
            'code' => 'Z',
            'nama_loket' => 'Loket Z',
            'deskripsi' => 'Test Z'
        ]);

        // Existing antrian with nomor Z001 today
        Antrian::create([
            'loket_id' => $loket->id,
            'nomor_antrian' => 'Z001',
            'status' => 'menunggu'
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Nomor antrian sudah digunakan untuk loket ini hari ini.');

        $service = new AntrianService();
        // attempt to create with the same manual nomor
        $service->createForLoket($loket, 'Z001');
    }
}
