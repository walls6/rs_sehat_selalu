<?php

// app/Models/Antrian.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antrian extends Model {
    use HasFactory;
    protected $fillable = ['loket_id','nomor_antrian','status','waktu_panggil'];

    public function loket() {
        return $this->belongsTo(Loket::class);
    }
}
