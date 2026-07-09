<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModularCertification extends Model
{
    protected $table        = 'modular_certification';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'module',
        'model_type',
        'program_id',
        'is_active'
    ];

    /**
     * Obtener el modelo al que pertenece la imagen (Producto,etcccc, etc.)
     */
    public function modelType(): MorphTo
    {
        return $this->morphTo();
    }
}
