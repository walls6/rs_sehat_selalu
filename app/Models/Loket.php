<?php

// app/Models/Loket.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loket extends Model {
    use HasFactory;
    protected $fillable = ['code','nama_loket','deskripsi'];

    public function antrians() {
        return $this->hasMany(Antrian::class);
    }

    public function currentCalled() {
        return $this->hasOne(Antrian::class)->where('status','dipanggil')->latest('waktu_panggil');
    }
}
