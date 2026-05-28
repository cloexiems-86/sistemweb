<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    //
    protected $fillable = ['catin_id', 'pendamping_id', 'pengirim', 'pesan', 'is_read'];

    // Relasi agar bisa memanggil nama catin/pendamping di API nanti
    public function catin() {
        return $this->belongsTo(Catin::class);
    }
    public function pendamping() {
        return $this->belongsTo(Pendamping::class);
    }
}
