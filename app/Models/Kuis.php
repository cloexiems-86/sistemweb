<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

    protected $table = 'kuis';

    // PERBAIKAN: Hapus judul, durasi, dan status karena tidak ada di database kamu
    protected $fillable = ['materi_id'];

    // Relasi: Kuis ini milik materi apa?
    public function materi() {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    // Relasi ke Soal
    public function soals() {
        return $this->hasMany(Soal::class, 'kuis_id');
    }

    public function logs() {
        return $this->hasMany(KuisLog::class, 'kuis_id');
    }
}