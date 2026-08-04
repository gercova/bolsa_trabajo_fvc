<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyProgram extends Model
{
    protected $table        = 'study_programs';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'name',
        'slug',
        'logo_path',
        'description',
        'details',
        'icon',
        'accent',
        'bg_badge',
        'tag',
        'color_bar',
        'glow_class',
        'badge_class',
        'accent_text',
        'bullet_class',
        'icon_bg_class',
        'border_hover_class',
        'badge_module_class',
        'sidebar_icon_class',
        'cta_bg_class',
        'bar_color_class',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // Relaciones existentes
    public function modules(): MorphMany
    {
        return $this->morphMany(ModularCertification::class, 'model_type', 'model_type', 'program_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable', 'imageable_type', 'imageable_id');
    }

    public function admissionDetails(): HasMany 
    {
        return $this->hasMany(AdmissionDetail::class, 'program_id', 'id');
    }

    public function teacherDetails(): HasMany
    {
        return $this->hasMany(UserRoleDetail::class, 'program_id', 'id');
    }

    // Nuevas relaciones
    public function meta(): HasOne
    {
        return $this->hasOne(ProgramMeta::class, 'study_program_id', 'id');
    }

    public function competencies(): HasMany
    {
        return $this->hasMany(ProgramCompetency::class, 'study_program_id', 'id')->orderBy('order');
    }

    public function jobFields(): HasMany
    {
        return $this->hasMany(ProgramJobField::class, 'study_program_id', 'id')->orderBy('order');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(ProgramRequirement::class, 'study_program_id', 'id')->orderBy('order');
    }

    // Métodos de acceso para obtener datos formateados
    public function getPerfilAttribute()
    {
        return $this->description;
    }

    public function getCompetenciasListAttribute()
    {
        return $this->competencies->map(function ($item) {
            return [
                'title' => $item->title,
                'desc' => $item->description,
                'icon' => $item->icon,
            ];
        })->toArray();
    }

    public function getCampoLaboralListAttribute()
    {
        return $this->jobFields->pluck('description')->toArray();
    }

    public function getRequisitosListAttribute()
    {
        return $this->requirements->pluck('description')->toArray();
    }
}