<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

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
     * Get the full URL for the image.
     */
    public function getUrlAttribute(): string
    {
        if (!$this->path) {
            return '';
        }

        if (Str::startsWith($this->path, ['http://', 'https://'])) {
            return $this->path;
        }

        if (Str::startsWith($this->path, ['images/', 'img/'])) {
            return asset($this->path);
        }

        if (Str::startsWith($this->path, 'storage/')) {
            return asset($this->path);
        }

        return asset('storage/' . $this->path);
    }

    /**
     * Obtener el modelo al que pertenece la imagen
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
