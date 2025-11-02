<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = ['loket_id', 'nomor_antrian', 'status', 'waktu_panggil'];

    protected $casts = [
        'waktu_panggil' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship dengan Loket
     */
    public function loket()
    {
        return $this->belongsTo(Loket::class);
    }

    /**
     * Scope untuk antrian yang menunggu
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope untuk antrian yang dipanggil
     */
    public function scopeCalled($query)
    {
        return $query->where('status', 'dipanggil');
    }

    /**
     * Scope untuk antrian yang selesai
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk antrian hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }
}
