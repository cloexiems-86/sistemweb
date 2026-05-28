<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Catin;

class MateriLog extends Model
{
    // Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['catin_id', 'materi_id', 'peran'];

    // Relasi ke Catin yang melihat materi
    public function catin(): BelongsTo
    {
        return $this->belongsTo(Catin::class, 'catin_id');
    }

    // Backward-compatible alias untuk view yang masih memanggil user
    public function user(): BelongsTo
    {
        return $this->catin();
    }

    public function getNamaPesertaAttribute(): ?string
    {
        if ($this->peran === 'suami') {
            return $this->catin?->nama_suami;
        }

        if ($this->peran === 'istri') {
            return $this->catin?->nama_istri;
        }

        // Jika peran berisi nama peserta langsung, gunakan nilai tersebut.
        return $this->peran ?: ($this->catin?->nama_suami ? $this->catin->nama_suami : $this->catin?->nama_istri);
    }

    public function getEmailPesertaAttribute(): ?string
    {
        if ($this->peran === 'suami') {
            return $this->catin?->email_suami;
        }

        if ($this->peran === 'istri') {
            return $this->catin?->email_istri;
        }

        return $this->catin?->email_suami ?? $this->catin?->email_istri;
    }

    // Relasi ke Materi (Materi apa yang dilihat?)
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}