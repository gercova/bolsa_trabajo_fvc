<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnrollmentSchedule extends Model
{
    protected $table      = 'enrollment_schedules';
    protected $primaryKey = 'id';

    protected $fillable = [
        'academic_period',
        'enrollment_type',
        'enrollment_fee',
        'start_date',
        'end_date',
        'observations',
        'is_active',
    ];

    protected $casts = [
        'enrollment_fee' => 'decimal:2',
        'start_date'     => 'date',
        'end_date'       => 'date',
        'is_active'      => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(EnrollmentScheduleDetail::class, 'enrollment_schedule_id', 'id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdinaria($query)
    {
        return $query->where('enrollment_type', 'ordinaria');
    }

    public function scopeExtraordinaria($query)
    {
        return $query->where('enrollment_type', 'extraordinaria');
    }

    /**
     * Human-readable label for enrollment type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->enrollment_type) {
            'ordinaria'     => 'Matrícula Ordinaria',
            'extraordinaria'=> 'Matrícula Extraordinaria',
            default         => ucfirst($this->enrollment_type),
        };
    }

    /**
     * Returns true if today is within the enrollment window.
     */
    public function getIsOpenAttribute(): bool
    {
        $today = now()->startOfDay();
        return $this->is_active
            && $today->greaterThanOrEqualTo($this->start_date)
            && $today->lessThanOrEqualTo($this->end_date);
    }
}
