<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk memberitahu Laravel nama tabel aslinya
    protected $table = 'pengumumans';

    // Sekalian tambahkan fillable agar bisa simpan data
    protected $fillable = ['judul', 'isi', 'target'];
}