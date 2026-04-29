<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Import Model Kuis jika berada di namespace yang sama atau berbeda
// use App\Models\Kuis; 

class Materi extends Model
{
    protected $table = 'materis';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'status'
    ];

    /**
     * RELASI: Satu Materi memiliki banyak Kuis
     * Ini yang menyebabkan error 'count() on null' jika tidak ada
     */
    public function kuis() {
        // Karena nama tabelmu 'kuis' (bukan quizzes), pastikan relasinya begini
        return $this->hasOne(Kuis::class, 'materi_id'); 
    }

    public function logs()
    {
        // Ini artinya satu materi punya banyak catatan di tabel materi_logs
        return $this->hasMany(MateriLog::class, 'materi_id');
    }
}