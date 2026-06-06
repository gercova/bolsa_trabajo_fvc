<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $table        = 'study_programs';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'name',
        'logo_path',
        'description',
        'details',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
}
