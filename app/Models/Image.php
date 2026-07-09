<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $table        = 'images';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'path',
        'is_main',
        'imageable_id',
        'imageable_type'
    ];

    protected $casts = [
        'is_main'       => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * Obtener el modelo al que pertenece la imagen (Producto,etcccc, etc.)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
