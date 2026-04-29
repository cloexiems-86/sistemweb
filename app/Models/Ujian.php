<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    // Laravel secara default akan menganggap nama tabelnya 'ujians'
    // Kita paksa agar menggunakan nama 'ujian' sesuai migration
    protected $table = 'ujian';

    // Kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'catin_id',
        'skor',
        'jawaban_benar',
        'jawaban_salah',
        'status_kelulusan',
    ];

    /**
     * Relasi ke model Catin
     * Satu hasil ujian dimiliki oleh satu Catin
     */
    // app/Models/Ujian.php
    public function catin()
    {
        // Pastikan foreign key-nya benar (misal: catin_id atau id_catin)
        return $this->belongsTo(Catin::class, 'catin_id'); 
    }
}