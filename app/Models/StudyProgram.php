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
        'training_itinerary_path',
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
        'order',
        'is_active',
    ];

    protected $casts = [
        'order'         => 'integer',
        'is_active'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * Scope a query to sort programs by sequential order and name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

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

    public function getTrainingItineraryUrlAttribute(): ?string
    {
        if (!$this->training_itinerary_path) {
            return null;
        }
        return \Illuminate\Support\Str::startsWith($this->training_itinerary_path, ['http://', 'https://'])
            ? $this->training_itinerary_path
            : asset('storage/' . $this->training_itinerary_path);
    }

    /**
     * Devuelve la configuración de color sólido institucional asignada a cada programa de estudio.
     */
    public function getColorConfigAttribute(): array
    {
        $slug = $this->slug ?? \Illuminate\Support\Str::slug($this->name ?? '');

        if (str_contains($slug, 'redes') || str_contains($slug, 'comunicaciones') || str_contains($slug, 'computacion')) {
            return [
                'key'         => 'sky',
                'name'        => 'Administración de Redes y Comunicaciones',
                'solid_bg'    => 'bg-sky-600',
                'solid_hover' => 'hover:bg-sky-700',
                'light_bg'    => 'bg-sky-50',
                'text'        => 'text-sky-700',
                'text_dark'   => 'text-sky-900',
                'border'      => 'border-sky-200',
                'badge'       => 'bg-sky-100 text-sky-800 border-sky-200',
                'bar'         => 'bg-sky-600',
                'hex'         => '#0284c7',
                'btn_primary' => 'bg-sky-600 hover:bg-sky-700 text-white',
            ];
        }

        if (str_contains($slug, 'enfermeria') || str_contains($slug, 'salud')) {
            return [
                'key'         => 'rose',
                'name'        => 'Enfermería Técnica',
                'solid_bg'    => 'bg-rose-600',
                'solid_hover' => 'hover:bg-rose-700',
                'light_bg'    => 'bg-rose-50',
                'text'        => 'text-rose-700',
                'text_dark'   => 'text-rose-900',
                'border'      => 'border-rose-200',
                'badge'       => 'bg-rose-100 text-rose-800 border-rose-200',
                'bar'         => 'bg-rose-600',
                'hex'         => '#e11d48',
                'btn_primary' => 'bg-rose-600 hover:bg-rose-700 text-white',
            ];
        }

        if (str_contains($slug, 'agropecuaria') || str_contains($slug, 'agro')) {
            return [
                'key'         => 'emerald',
                'name'        => 'Producción Agropecuaria',
                'solid_bg'    => 'bg-emerald-600',
                'solid_hover' => 'hover:bg-emerald-700',
                'light_bg'    => 'bg-emerald-50',
                'text'        => 'text-emerald-700',
                'text_dark'   => 'text-emerald-900',
                'border'      => 'border-emerald-200',
                'badge'       => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'bar'         => 'bg-emerald-600',
                'hex'         => '#059669',
                'btn_primary' => 'bg-emerald-600 hover:bg-emerald-700 text-white',
            ];
        }

        if (str_contains($slug, 'forestal')) {
            return [
                'key'         => 'teal',
                'name'        => 'Manejo Forestal',
                'solid_bg'    => 'bg-teal-700',
                'solid_hover' => 'hover:bg-teal-800',
                'light_bg'    => 'bg-teal-50',
                'text'        => 'text-teal-700',
                'text_dark'   => 'text-teal-900',
                'border'      => 'border-teal-200',
                'badge'       => 'bg-teal-100 text-teal-800 border-teal-200',
                'bar'         => 'bg-teal-700',
                'hex'         => '#0f766e',
                'btn_primary' => 'bg-teal-700 hover:bg-teal-800 text-white',
            ];
        }

        if (str_contains($slug, 'asistencia') || str_contains($slug, 'administrativa')) {
            return [
                'key'         => 'amber',
                'name'        => 'Asistencia Administrativa',
                'solid_bg'    => 'bg-amber-600',
                'solid_hover' => 'hover:bg-amber-700',
                'light_bg'    => 'bg-amber-50',
                'text'        => 'text-amber-700',
                'text_dark'   => 'text-amber-900',
                'border'      => 'border-amber-200',
                'badge'       => 'bg-amber-100 text-amber-800 border-amber-200',
                'bar'         => 'bg-amber-600',
                'hex'         => '#d97706',
                'btn_primary' => 'bg-amber-600 hover:bg-amber-700 text-white',
            ];
        }

        return [
            'key'         => 'blue',
            'name'        => $this->name ?? 'Programa de Estudio',
            'solid_bg'    => 'bg-blue-700',
            'solid_hover' => 'hover:bg-blue-800',
            'light_bg'    => 'bg-blue-50',
            'text'        => 'text-blue-700',
            'text_dark'   => 'text-blue-900',
            'border'      => 'border-blue-200',
            'badge'       => 'bg-blue-100 text-blue-800 border-blue-200',
            'bar'         => 'bg-blue-600',
            'hex'         => '#1d4ed8',
            'btn_primary' => 'bg-blue-700 hover:bg-blue-800 text-white',
        ];
    }
}