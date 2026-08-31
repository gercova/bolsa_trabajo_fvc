<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_code',
        'description',
        'start_date',
        'end_date',
        'duration',
        'modality',
        'issue_date',
        'is_active',
        'download_count',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'download_count' => 'integer',
        'start_date'     => 'date:Y-m-d',
        'end_date'       => 'date:Y-m-d',
        'issue_date'     => 'date:Y-m-d',
    ];

    /**
     * Scope for active certificates.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for modality filter.
     */
    public function scopeModality(Builder $query, string $modality): Builder
    {
        return $query->where('modality', $modality);
    }

    /**
     * Relationship with Course.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Relationship with User / Student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship with Certificate Details (scores per module).
     */
    public function details(): HasMany
    {
        return $this->hasMany(CertificateDetail::class, 'certificate_id', 'id');
    }
}
