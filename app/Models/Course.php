<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'certificate_type',
        'study_program_id',
        'event_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'course_id', 'id');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'course_id', 'id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'course_id', 'id');
    }

    public function isTraining(): bool
    {
        return $this->certificate_type === 'capacitacion';
    }
}
