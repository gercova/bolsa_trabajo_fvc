<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function modelType(): MorphTo
    {
        return $this->morphTo();
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'program_id', 'id');
    }
}
