<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Catin;

class KuisLog extends Model
{
    protected $fillable = ['catin_id', 'kuis_id', 'nilai', 'nama_peserta'];

    public function catin(): BelongsTo
    {
        return $this->belongsTo(Catin::class, 'catin_id');
    }

    public function user(): BelongsTo
    {
        return $this->catin();
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->nama_peserta) {
            return $this->nama_peserta;
        }

        return trim(($this->catin?->nama_suami ?? '') . ' / ' . ($this->catin?->nama_istri ?? '')) ?: 'Unknown';
    }
}
