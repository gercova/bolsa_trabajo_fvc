<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DegreeRecord extends Model
{
    protected $table = 'degree_records';

    protected $fillable = [
        'record_number',
        'modular_code',
        'institution_name',
        'management_type',
        'department',
        'study_program',
        'mention',
        'formative_level',
        'productive_family',
        'document_type',
        'document_number',
        'full_names',
        'birth_date',
        'gender',
        'graduation_date',
        'institutional_registration_number',
        'diploma_issue_date',
        'minedu_registration_date',
        'generated_title_code',
        'file_number',
        'registration_type',
        'specialist_user',
        'diploma_type',
    ];

    protected $casts = [
        'birth_date'                => 'date',
        'graduation_date'           => 'date',
        'diploma_issue_date'        => 'date',
        'minedu_registration_date'  => 'datetime',
    ];
}
