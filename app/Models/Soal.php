<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soals';
    
    protected $fillable = [
        'kuis_id', 
        'pertanyaan', 
        'opsi_a', 
        'opsi_b', 
        'opsi_c', 
        'opsi_d', 
        'jawaban_benar'
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}