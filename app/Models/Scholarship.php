<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $table        = 'scholarships';
    protected $primaryKey   = 'id';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'vacancies',
        'discount_percentage',
        'discount_details',
        'requirements',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'vacancies'           => 'integer',
        'discount_percentage' => 'decimal:2',
        'sort_order'          => 'integer',
        'is_active'           => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
