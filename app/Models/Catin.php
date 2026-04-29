<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catin extends Model
{
    use HasFactory;

    protected $table = 'catins';

    protected $fillable = [
        'username',
        'password',
        'status',
        'nama_suami',
        'nik_suami',
        'phone_suami',
        'email_suami',
        'alamat_suami',
        'nama_istri',
        'nik_istri',
        'phone_istri',
        'email_istri',
        'alamat_istri',
        'wedding_date',
        'ktp_suami',
        'ktp_istri',
        'kk_suami',
        'kk_istri',
        'pendamping_id' // Pastikan kolom ini ada jika ingin relasi ke pendamping
    ];

    /**
     * Relasi ke Tabel Ujian
     * Digunakan untuk mengecek kelulusan dan menampilkan data di menu Sertifikat
     */
    public function ujian()
    {
        return $this->hasOne(Ujian::class, 'catin_id');
    }

    /**
     * Relasi ke Tabel Pendamping
     * Digunakan untuk menampilkan siapa pendamping/petugas KUA yang membimbing
     */
    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'pendamping_id');
    }

    // /**
    //  * Accessor untuk Nama Gabungan (Opsional)
    //  * Memudahkan pemanggilan nama pasangan di tampilan tabel
    //  */
    // public function getNamaLengkapAttribute()
    // {
    //     return "{$this->nama_suami} & {$this->nama_istri}";
    // }
}