<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Loket;
use App\Models\Antrian;
use App\Services\AntrianService;

class AntrianServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_for_loket_generates_antrian_and_persists()
    {
        $loket = Loket::create([
            'code' => 'A',
            'nama_loket' => 'Loket A',
            'deskripsi' => 'Test'
        ]);

        $service = new AntrianService();
        $antrian = $service->createForLoket($loket);

        $this->assertInstanceOf(Antrian::class, $antrian);
        $this->assertDatabaseHas('antrians', [
            'id' => $antrian->id,
            'loket_id' => $loket->id,
            'status' => 'menunggu'
        ]);
    }

    public function test_update_status_sets_waktu_panggil_for_dipanggil()
    {
        $loket = Loket::create([
            'code' => 'B',
            'nama_loket' => 'Loket B',
            'deskripsi' => 'Test B'
        ]);

        $antrian = Antrian::create([
            'loket_id' => $loket->id,
            'nomor_antrian' => 'B001',
            'status' => 'menunggu'
        ]);

        $service = new AntrianService();
        $updated = $service->updateStatus($antrian, 'dipanggil');

        $this->assertEquals('dipanggil', $updated->status);
        $this->assertNotNull($updated->waktu_panggil);
        $this->assertDatabaseHas('antrians', [
            'id' => $updated->id,
            'status' => 'dipanggil'
        ]);
    }
}
