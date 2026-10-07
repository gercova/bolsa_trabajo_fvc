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
        if ($this->isBasicEnglish()) {
            return false;
        }

        return $this->certificate_type === 'capacitacion';
    }

    public function isBasicEnglish(): bool
    {
        $searchTerms = ['inglés', 'ingles', 'english'];
        $courseName = mb_strtolower($this->name ?? '', 'UTF-8');
        $desc = mb_strtolower($this->description ?? '', 'UTF-8');
        $certType = mb_strtolower($this->certificate_type ?? '', 'UTF-8');

        foreach ($searchTerms as $term) {
            if (str_contains($courseName, $term) || str_contains($desc, $term)) {
                return true;
            }
        }

        return $certType === 'ingles' || $certType === 'basic_english';
    }
}
