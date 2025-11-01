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
            $table->string('nomor_antrian'); // e.g., A001
            $table->enum('status', ['menunggu','dipanggil','selesai'])->default('menunggu');
            $table->timestamp('waktu_panggil')->nullable();
            $table->timestamps();
            $table->unique(['loket_id','nomor_antrian']); // optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
