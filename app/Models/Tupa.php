<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tupa extends Model
{
    use HasFactory;

    protected $table = 'tupa';

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'effective_start_date',
        'effective_end_date',
        'is_active',
    ];

    protected $casts = [
        'effective_start_date'  => 'date',
        'effective_end_date'    => 'date',
        'is_active'             => 'boolean',
    ];

    /**
     * Accesor para obtener la URL del archivo
     */
    public function getUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Accesor para obtener el año del TUPA
     */
    public function getYearAttribute()
    {
        return $this->effective_start_date ? $this->effective_start_date->format('Y') : null;
    }

    /**
     * Scope para obtener TUPAs activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relación con procedimientos del TUPA
     */
    public function procedures()
    {
        return $this->hasMany(TupaProcedure::class, 'tupa_id');
    }
}
