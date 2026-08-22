<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDetail extends Model
{
    protected $table        = 'admission_details';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'admission_id',
        'program_id',
        'vacancies',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function admission(): BelongsTo {
        return $this->belongsTo(Admission::class, 'admission_id', 'id');
    }

    public function program(): BelongsTo {
        return $this->belongsTo(StudyProgram::class, 'program_id', 'id');
    }
}
