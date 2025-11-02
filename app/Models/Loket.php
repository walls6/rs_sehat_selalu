<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loket extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nama_loket', 'deskripsi'];

    /**
     * Relationship dengan Antrian
     */
    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    /**
     * Relationship untuk antrian yang sedang dipanggil
     */
    public function currentCalled()
    {
        return $this->hasOne(Antrian::class)
            ->where('status', 'dipanggil')
            ->latest('waktu_panggil');
    }

    /**
     * Relationship untuk antrian yang menunggu
     */
    public function waitingAntrians()
    {
        return $this->hasMany(Antrian::class)
            ->where('status', 'menunggu')
            ->orderBy('created_at');
    }

    /**
     * Get jumlah antrian menunggu untuk loket ini
     */
    public function getWaitingCountAttribute()
    {
        return $this->antrians()->where('status', 'menunggu')->count();
    }

    /**
     * Get antrian terakhir yang dipanggil
     */
    public function getLatestCalledAttribute()
    {
        return $this->antrians()
            ->where('status', 'dipanggil')
            ->latest('waktu_panggil')
            ->first();
    }
}
