<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function modules(): MorphMany
    {
        return $this->morphMany(ModularCertification::class, 'model_type', 'model_type', 'program_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable', 'imageable_type', 'imageable_id');
    }
}
