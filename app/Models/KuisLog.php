<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisLog extends Model
{
    //
    protected $fillable = ['catin_id', 'kuis_id', 'nilai'];

    public function user() {
        return $this->belongsTo(User::class, 'catin_id');
    }
}
