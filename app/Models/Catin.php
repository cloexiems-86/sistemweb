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
        'pendamping_id',
        'role' // Pastikan kolom ini ada jika ingin relasi ke pendamping
    ];

    /**
     * Relasi ke Tabel Ujian
     * Digunakan untuk mengecek kelulusan dan menampilkan data di menu Sertifikat
     */
    public function ujian()
    {
        return $this->hasOne(Ujian::class, 'catin_id')->latestOfMany();
    }

    /**
     * Semua hasil ujian yang terkait dengan pasangan (bisa per-individu)
     */
    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'catin_id');
    }

    /**
     * Hasil ujian khusus suami bila ada
     */
    public function ujianSuami()
    {
        return $this->hasOne(Ujian::class, 'catin_id')
            ->where(function ($query) {
                $query->where('person', 'suami')
                      ->orWhere('nama_peserta', $this->nama_suami);
            })
            ->latestOfMany();
    }

    /**
     * Hasil ujian khusus istri bila ada
     */
    public function ujianIstri()
    {
        return $this->hasOne(Ujian::class, 'catin_id')
            ->where(function ($query) {
                $query->where('person', 'istri')
                      ->orWhere('nama_peserta', $this->nama_istri);
            })
            ->latestOfMany();
    }

    /**
     * Relasi ke Tabel Pendamping
     * Digunakan untuk menampilkan siapa pendamping/petugas KUA yang membimbing
     */
    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'pendamping_id');
    }

    /**
     * Relasi Many-to-Many ke Tabel Jadwal
     * Digunakan untuk mengambil jadwal yang diikuti catin
     */
    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'catin_jadwal', 'catin_id', 'jadwal_id');
    }

    /**
     * Nama pendamping utama untuk Catin.
     * Jika relasi pendamping langsung kosong, gunakan pendamping dari jadwal terakhir.
     */
    public function getPendampingAssignedAttribute()
    {
        if ($this->pendamping) {
            return $this->pendamping->nama;
        }

        if ($this->relationLoaded('jadwals')) {
            $jadwal = $this->jadwals->sortByDesc('tanggal')->first();
            return $jadwal?->pendamping?->nama;
        }

        $jadwal = $this->jadwals()->with('pendamping')->latest('tanggal')->first();
        return $jadwal?->pendamping?->nama;
    }

    /**
     * Accessor untuk Nama Gabungan (Opsional)
     * Memudahkan pemanggilan nama pasangan di tampilan tabel
     */
    public function getNamaLengkapAttribute()
    {
        return "{$this->nama_suami} & {$this->nama_istri}";
    }
}