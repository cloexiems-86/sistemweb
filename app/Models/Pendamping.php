<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendamping extends Model
{
    use HasFactory;

    // INI KUNCI UTAMANYA: Mengunci nama tabel agar Laravel tidak mencari "pendampings"
    protected $table = 'pendamping';

    // Sesuaikan fillable ini dengan nama-nama kolom di tabel pendamping milikmu
    protected $fillable = [
        'nama',
        'nip',
        'whatsapp', // atau 'no_hp' / 'no_whatsapp', sesuaikan dengan database kamu
        'email',
        // tambahkan kolom lain jika ada
    ];

    /**
     * Relasi ke model Jadwal (Satu pendamping bisa punya banyak jadwal)
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'pendamping_id');
    }
}