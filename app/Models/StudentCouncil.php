<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCouncil extends Model
{
    protected $fillable = [
        'user_id', 
        'study_program_id',
        'name',
        'position',
        'academic_period',
        'is_active',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function studyProgram(): BelongsTo{
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }
}
