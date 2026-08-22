<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionRequirement extends Model
{
    protected $table        = 'admission_requirements';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'requirement',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
