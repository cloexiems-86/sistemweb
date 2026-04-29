<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriLog extends Model
{
    // Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['catin_id', 'materi_id'];

    // Relasi ke User/Catin (Siapa yang melihat?)
    public function catin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'catin_id');
    }

    // Relasi ke Materi (Materi apa yang dilihat?)
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}