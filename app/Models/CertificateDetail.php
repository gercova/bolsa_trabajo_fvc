<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateDetail extends Model
{
    protected $fillable = [
        'certificate_id',
        'module_id',
        'score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function certificate(): BelongsTo {
        return $this->belongsTo(Certificate::class, 'certificate_id', 'id');
    }

    public function module(): BelongsTo {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
