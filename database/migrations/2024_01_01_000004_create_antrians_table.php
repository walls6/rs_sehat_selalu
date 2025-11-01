<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loket_id')->constrained('lokets')->cascadeOnDelete();
            $table->string('nomor_antrian'); // e.g., "A001", "B001"
            $table->string('status')->default('menunggu'); // 'menunggu', 'dipanggil', 'selesai'
            $table->timestamp('waktu_panggil')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('loket_id');
            $table->index('status');
            $table->index('created_at');
        });
        
        // Check constraint untuk status (PostgreSQL) - harus setelah tabel dibuat
        if (config('database.default') === 'pgsql') {
            \DB::statement('ALTER TABLE antrians ADD CONSTRAINT antrians_status_check CHECK (status IN (\'menunggu\', \'dipanggil\', \'selesai\'))');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
