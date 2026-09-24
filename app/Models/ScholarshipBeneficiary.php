<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ScholarshipBeneficiary extends Model
{
    protected $table = 'scholarship_beneficiaries';

    protected $fillable = [
        'academic_period',
        'title',
        'description',
        'resolution_number',
        'file_path',
        'file_size',
        'publication_date',
        'scholarship_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'publication_date' => 'date',
        'file_size'        => 'integer',
        'sort_order'       => 'integer',
    ];

    protected $appends = [
        'file_url',
        'formatted_file_size',
    ];

    /**
     * Relationship to the related scholarship modality (if specific).
     */
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }

    /**
     * Scope for active beneficiary records.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default ordering: period descending, sort order ascending, newest first.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('academic_period', 'desc')
                     ->orderBy('sort_order', 'asc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope for a specific academic period.
     */
    public function scopePeriod($query, string $period)
    {
        return $query->where('academic_period', $period);
    }

    /**
     * Accessor for full public URL of the PDF file.
     * Uses relative URL '/storage/...' so it always resolves to the current origin and port.
     */
    public function getFileUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }

        return '/storage/' . ltrim($this->file_path, '/');
    }

    /**
     * Accessor for human-readable file size (e.g. 1.5 MB).
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = (int) ($this->file_size ?? 0);
        if ($bytes <= 0) {
            return 'PDF';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes) / log(1024));
        $pow = min((int) $pow, count($units) - 1);
        $size = $bytes / pow(1024, $pow);

        return round($size, 1) . ' ' . $units[$pow];
    }
}
