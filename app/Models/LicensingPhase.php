<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicensingPhase extends Model
{
    use HasFactory;

    protected $table = 'licensing_phases';

    protected $fillable = [
        'phase_number',
        'title',
        'subtitle',
        'code',
        'stage_tag',
        'status',
        'is_current',
        'progress_percentage',
        'description',
        'milestones',
        'resolution_number',
        'legal_basis',
        'start_date',
        'end_date',
        'estimated_date',
        'file_path',
        'external_link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'phase_number'        => 'integer',
        'is_current'          => 'boolean',
        'progress_percentage' => 'integer',
        'milestones'          => 'array',
        'start_date'          => 'date',
        'end_date'            => 'date',
        'order'               => 'integer',
        'is_active'           => 'boolean',
    ];

    /**
     * Scope a query to only include active licensing phases.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query ordered by phase order and phase_number.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('phase_number', 'asc');
    }

    /**
     * Get the current active stage model.
     */
    public static function currentStage()
    {
        return static::active()->where('is_current', true)->first()
            ?? static::active()->where('status', 'in_progress')->first()
            ?? static::active()->ordered()->first();
    }

    /**
     * Get color and badge formatting for the status.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'completed' => [
                'label'      => 'Culminado',
                'short_tag'  => 'C',
                'bg_class'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'pill_class' => 'bg-emerald-500 text-white',
                'badge_bg'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                'border'     => 'border-emerald-500',
                'icon'       => 'bi-check-circle-fill',
                'text_color' => 'text-emerald-500',
            ],
            'in_progress' => [
                'label'      => 'En Proceso (P)',
                'short_tag'  => 'P',
                'bg_class'   => 'bg-amber-50 text-amber-800 border-amber-200',
                'pill_class' => 'bg-amber-500 text-white animate-pulse',
                'badge_bg'   => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                'border'     => 'border-amber-500',
                'icon'       => 'bi-hourglass-split',
                'text_color' => 'text-amber-500',
            ],
            'observed' => [
                'label'      => 'En Observación',
                'short_tag'  => 'OBS',
                'bg_class'   => 'bg-rose-50 text-rose-700 border-rose-200',
                'pill_class' => 'bg-rose-500 text-white',
                'badge_bg'   => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                'border'     => 'border-rose-500',
                'icon'       => 'bi-exclamation-octagon-fill',
                'text_color' => 'text-rose-500',
            ],
            default => [
                'label'      => 'Pendiente',
                'short_tag'  => 'PTE',
                'bg_class'   => 'bg-slate-100 text-slate-700 border-slate-200',
                'pill_class' => 'bg-slate-400 text-white',
                'badge_bg'   => 'bg-slate-500/10 text-slate-400 border-slate-500/30',
                'border'     => 'border-slate-300',
                'icon'       => 'bi-circle',
                'text_color' => 'text-slate-400',
            ],
        };
    }
}
