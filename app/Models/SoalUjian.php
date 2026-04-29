<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional jika nama tabelmu sudah soal_ujians)
    protected $table = 'soal_ujians';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'pertanyaan',
        'pil_a',
        'pil_b',
        'pil_c',
        'pil_d',
        'kunci_jawaban',
    ];
}