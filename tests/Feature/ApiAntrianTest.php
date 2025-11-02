<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Loket;
use App\Models\Antrian;

class ApiAntrianTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_create_for_loket_returns_201_and_persists()
    {
        $loket = Loket::create([
            'code' => 'C',
            'nama_loket' => 'Loket C',
            'deskripsi' => 'API test'
        ]);

    $response = $this->postJson("/api/lokets/{$loket->id}/antrians", []);

        $response->assertStatus(201);
        $this->assertDatabaseHas('antrians', [
            'loket_id' => $loket->id,
            'status' => 'menunggu'
        ]);
    }

    public function test_api_update_status_changes_status()
    {
        $loket = Loket::create([
            'code' => 'D',
            'nama_loket' => 'Loket D',
            'deskripsi' => 'API test D'
        ]);

        $antrian = Antrian::create([
            'loket_id' => $loket->id,
            'nomor_antrian' => 'D001',
            'status' => 'menunggu'
        ]);

        $response = $this->patchJson("/api/antrians/{$antrian->id}/status", ['status' => 'dipanggil']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('antrians', [
            'id' => $antrian->id,
            'status' => 'dipanggil'
        ]);
    }
}
