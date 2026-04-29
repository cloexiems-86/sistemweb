<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendamping extends Model
{
    protected $table = 'pendamping';

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'no_hp',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
    ];
}