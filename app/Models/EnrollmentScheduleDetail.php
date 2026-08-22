<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentScheduleDetail extends Model
{
    protected $table      = 'enrollment_schedule_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'enrollment_schedule_id',
        'program_id',
        'available_slots',
        'observations',
    ];

    protected $casts = [
        'available_slots' => 'integer',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EnrollmentSchedule::class, 'enrollment_schedule_id', 'id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'program_id', 'id');
    }
}
