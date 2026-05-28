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
        'nama_peserta',
        'person',
        'skor',
        'jawaban_benar',
        'jawaban_salah',
        'status_kelulusan',
    ];

    /**
     * Relasi ke model Catin
     * Satu hasil ujian dimiliki oleh satu Catin
     */
    public function catin()
    {
        return $this->belongsTo(Catin::class, 'catin_id'); 
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->nama_peserta) {
            return $this->nama_peserta;
        }

        if ($this->person === 'suami') {
            return $this->catin?->nama_suami ?? ($this->catin?->nama_lengkap ?? 'Catin Tidak Ditemukan');
        }

        if ($this->person === 'istri') {
            return $this->catin?->nama_istri ?? ($this->catin?->nama_lengkap ?? 'Catin Tidak Ditemukan');
        }

        return $this->catin?->nama_lengkap ?? 'Catin Tidak Ditemukan';
    }

    public function getDisplayNikAttribute(): ?string
    {
        if ($this->person === 'suami') {
            return $this->catin?->nik_suami;
        }

        if ($this->person === 'istri') {
            return $this->catin?->nik_istri;
        }

        return $this->catin?->nik_suami ?? $this->catin?->nik_istri;
    }

    public function getDisplayPeranAttribute(): ?string
    {
        if ($this->person === 'suami') {
            return 'Suami';
        }

        if ($this->person === 'istri') {
            return 'Istri';
        }

        return $this->person ? ucfirst($this->person) : null;
    }
}