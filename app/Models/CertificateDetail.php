<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateDetail extends Model
{
    protected $fillable = [
        'certificate_id',
        'module_id',
        'topic_name',
        'order',
        'score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'certificate_id', 'id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function getTitleAttribute(): string
    {
        return $this->topic_name ?: ($this->module?->name ?? '');
    }

    public function getScoreInWordsAttribute(): string
    {
        if ($this->score === null || $this->score === '') {
            return '';
        }

        $num = (int) trim((string) $this->score);
        $words = [
            0 => 'Cero', 1 => 'Uno', 2 => 'Dos', 3 => 'Tres', 4 => 'Cuatro',
            5 => 'Cinco', 6 => 'Seis', 7 => 'Siete', 8 => 'Ocho', 9 => 'Nueve',
            10 => 'Diez', 11 => 'Once', 12 => 'Doce', 13 => 'Trece', 14 => 'Catorce',
            15 => 'Quince', 16 => 'Dieciséis', 17 => 'Diecisiete', 18 => 'Dieciocho',
            19 => 'Diecinueve', 20 => 'Veinte',
        ];

        return $words[$num] ?? ucfirst((string) $this->score);
    }
}
