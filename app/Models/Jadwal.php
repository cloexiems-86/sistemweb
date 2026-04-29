<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $fillable = [
        'tanggal',
        'topik',
        'sesi',
        'fasilitator',
        'status',
        'lokasi',          // TAMBAHKAN INI agar bisa disimpan
        'pendamping_id'    // TAMBAHKAN INI agar bisa disimpan
    ];

    /**
     * Relasi Many-to-Many ke model Catin
     */
    public function catins()
    {
        // Menghubungkan ke tabel 'catin_jadwal' yang baru kita buat
        return $this->belongsToMany(Catin::class, 'catin_jadwal', 'jadwal_id', 'catin_id');
    }

    /**
     * Relasi ke model Pendamping
     */
    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'pendamping_id');
    }
}