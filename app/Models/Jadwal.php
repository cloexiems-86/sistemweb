<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $fillable = [
        'jenis_jadwal',    // Ditambahkan agar bisa menyimpan jenis kegiatan (Bimbingan/Rapak)
        'tanggal',
        'topik',
        'sesi',
        'fasilitator',
        'status',
        'lokasi',          
        'pendamping_id'    
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