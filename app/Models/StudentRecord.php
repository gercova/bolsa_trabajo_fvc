<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type_id',
        'document',       
        'last_name_father',
        'last_name_mother',
        'names',       
        'birthdate',
        'gender',
        'mother_tongue',
        'email',
        'phone',
        'pais_procedencia',
        'ubigeo_ie',
        'region_ie',
        'province_ie',
        'district_ie',
        'institution_type_ie',
        'modular_code_ie',
        'institution_name_ie',
        'management_type_ie',
        'year_graduation',
        'region',
        'province',
        'district',
        'codigo_modular',
        'nombre_institucion',
        'tipo_gestion',
        'academic_period',
        'study_program',
        'modality',       
        'modality_type',  
        'headquarters',         
        'route_type',  
        'shift',
        'score',
        'situation',
        'cycle',
        'enrollment_status',
        'period_status',
        'record_type',
        'registration_date',
    ];

    protected $casts = [
        'birthdate'             => 'date',
        'registration_date'     => 'datetime',
        'score'                 => 'decimal:2',
        'year_graduation'       => 'integer',
    ];

    public function documentType(): BelongsTo {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }

    public function getFullNameAttribute(): string {
        return trim("{$this->names} {$this->last_name_father} {$this->last_name_mother}");
    }
}