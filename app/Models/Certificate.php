<?php

namespace App\Models;

use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_type',
        'participation_type',
        'event_name',
        'study_program_id',
        'institution_name',
        'city',
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
        'is_active' => 'boolean',
        'download_count' => 'integer',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'issue_date' => 'date:Y-m-d',
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
     * Relationship with Study Program.
     */
    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }

    /**
     * Relationship with User / Student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship with Certificate Details (scores per module or training topics).
     */
    public function details(): HasMany
    {
        return $this->hasMany(CertificateDetail::class, 'certificate_id', 'id')->orderBy('order')->orderBy('id');
    }

    /**
     * Check if this certificate follows the training / completion format.
     */
    public function isTraining(): bool
    {
        return ($this->certificate_type ?? $this->course?->certificate_type ?? 'capacitacion') === 'capacitacion';
    }

    /**
     * Permanent institutional verification URL.
     */
    public function getValidationUrlAttribute(): string
    {
        return url('/validar-certificado/'.$this->certificate_code);
    }

    /**
     * Generate vector SVG QR Code for verification.
     */
    public function getQrCodeSvgAttribute(): string
    {
        return QrCodeService::svg($this->validation_url, 130);
    }

    /**
     * Generate base64 Data URI QR Code.
     */
    public function getQrCodeDataUriAttribute(): string
    {
        return QrCodeService::dataUri($this->validation_url, 130);
    }

    /**
     * Resolved study program (from certificate directly or inherited from course).
     */
    public function getEffectiveStudyProgramAttribute(): ?StudyProgram
    {
        return $this->studyProgram ?: $this->course?->studyProgram;
    }

    /**
     * Formatted date range in Spanish (e.g. "del 21 al 24 de setiembre del presente año").
     */
    public function getFormattedDateRangeAttribute(): string
    {
        if (! $this->start_date && ! $this->end_date) {
            return '';
        }

        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'setiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
        ];

        $start = $this->start_date ? Carbon::parse($this->start_date) : null;
        $end = $this->end_date ? Carbon::parse($this->end_date) : null;

        if ($start && $end) {
            if ($start->month === $end->month && $start->year === $end->year) {
                $monthName = $meses[$start->month] ?? $start->format('F');

                return "del {$start->day} al {$end->day} de {$monthName} del presente año";
            }
            $startMonth = $meses[$start->month] ?? $start->format('F');
            $endMonth = $meses[$end->month] ?? $end->format('F');

            return "del {$start->day} de {$startMonth} al {$end->day} de {$endMonth} del {$end->year}";
        }

        if ($end) {
            $endMonth = $meses[$end->month] ?? $end->format('F');

            return "el {$end->day} de {$endMonth} del {$end->year}";
        }

        $startMonth = $meses[$start->month] ?? $start->format('F');

        return "el {$start->day} de {$startMonth} del {$start->year}";
    }

    /**
     * Formatted issue date in Spanish (e.g. "Uchiza, 30 de setiembre del 2026.").
     */
    public function getFormattedIssueDateAttribute(): string
    {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'setiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
        ];

        $date = $this->issue_date ? Carbon::parse($this->issue_date) : now();
        $city = $this->city ?: 'Uchiza';
        $monthName = $meses[$date->month] ?? $date->format('F');

        return "{$city}, {$date->day} de {$monthName} del {$date->year}.";
    }

    /**
     * Resolved list of syllabus topics (TEMARIO) for training certificates.
     */
    public function getTopicsListAttribute(): array
    {
        // 1. If explicit details exist for this certificate
        if ($this->details && $this->details->isNotEmpty()) {
            $topics = $this->details->map(function ($d) {
                return $d->topic_name ?: ($d->module?->name ?? null);
            })->filter()->values()->all();

            if (! empty($topics)) {
                return $topics;
            }
        }

        // 2. Otherwise use the course's modules or itineraries
        if ($this->course) {
            // Check modules
            $moduleNames = $this->course->modules()->where('is_active', true)->orderBy('id')->pluck('name')->all();
            if (! empty($moduleNames)) {
                return $moduleNames;
            }

            // Check itineraries
            $itineraryNames = $this->course->itineraries()->where('is_active', true)->orderBy('id')->pluck('name')->all();
            if (! empty($itineraryNames)) {
                return $itineraryNames;
            }
        }

        return [];
    }
}
