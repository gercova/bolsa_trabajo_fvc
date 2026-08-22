<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalInstitutionalLink extends Model
{
    protected $table        = 'external_institutional_links';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'name',
        'link',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query): void {
        $query->where('is_active', true);
    }
}
