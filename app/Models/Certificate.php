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
        'code',
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

    protected static function booted(): void
    {
        static::saving(function (Certificate $certificate) {
            $code = $certificate->attributes['code'] ?? null;
            $certCode = $certificate->attributes['certificate_code'] ?? null;

            if (empty($code) && ! empty($certCode)) {
                $certificate->attributes['code'] = $certCode;
            } elseif (empty($certCode) && ! empty($code)) {
                $certificate->attributes['certificate_code'] = $code;
            }
        });
    }

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
     * Scope for filtering certificates by user (Model, ID or DNI).
     */
    public function scopeForUser(Builder $query, User|int|string $user): Builder
    {
        if ($user instanceof User) {
            return $query->where('user_id', $user->id);
        }

        if (is_numeric($user)) {
            return $query->where('user_id', (int) $user);
        }

        return $query->whereHas('user', fn ($q) => $q->where('dni', $user));
    }

    /**
     * Find an existing duplicate certificate for the same user in the same event/course on the same date.
     */
    public static function findDuplicate(
        int $userId,
        int $courseId,
        ?string $eventName = null,
        ?string $issueDate = null,
        ?string $startDate = null,
        ?int $ignoreId = null
    ): ?self {
        $query = static::where('user_id', $userId);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $trimmedEvent = $eventName ? trim($eventName) : null;

        $query->where(function (Builder $sub) use ($courseId, $trimmedEvent) {
            $sub->where('course_id', $courseId);
            if ($trimmedEvent !== null && $trimmedEvent !== '') {
                $sub->orWhere(function (Builder $evSub) use ($trimmedEvent) {
                    $evSub->whereNotNull('event_name')
                        ->where('event_name', '!=', '')
                        ->whereRaw('LOWER(TRIM(event_name)) = ?', [mb_strtolower($trimmedEvent, 'UTF-8')]);
                });
            }
        });

        if ($issueDate) {
            $query->whereDate('issue_date', $issueDate);
        } elseif ($startDate) {
            $query->whereDate('start_date', $startDate);
        }

        return $query->first();
    }

    /**
     * Generate a unique certificate code for a student DNI using sequence number: CERT-{user_DNI}-{sequence_number}.
     */
    public static function generateUniqueCodeForStudent(string $dni, ?int $courseId = null): string
    {
        $cleanDni = trim($dni);
        $seq = 1;
        $code = "CERT-{$cleanDni}-{$seq}";

        while (static::where('code', $code)->orWhere('certificate_code', $code)->exists()) {
            $seq++;
            $code = "CERT-{$cleanDni}-{$seq}";
        }

        return $code;
    }

    /**
     * Accessor for code attribute.
     */
    public function getCodeAttribute(?string $value): ?string
    {
        return $value ?: ($this->attributes['certificate_code'] ?? null);
    }

    /**
     * Accessor for certificate_code attribute.
     */
    public function getCertificateCodeAttribute(?string $value): ?string
    {
        return $value ?: ($this->attributes['code'] ?? null);
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
        if ($this->isBasicEnglish()) {
            return false;
        }

        return ($this->certificate_type ?? $this->course?->certificate_type ?? 'capacitacion') === 'capacitacion';
    }

    /**
     * Check if this certificate corresponds to the "Basic English" course format.
     */
    public function isBasicEnglish(): bool
    {
        $searchTerms = ['inglés', 'ingles', 'english'];
        $courseName = mb_strtolower($this->course?->name ?? '', 'UTF-8');
        $eventName = mb_strtolower($this->event_name ?? '', 'UTF-8');
        $desc = mb_strtolower($this->description ?? '', 'UTF-8');
        $certType = mb_strtolower($this->certificate_type ?? '', 'UTF-8');

        foreach ($searchTerms as $term) {
            if (str_contains($courseName, $term) || str_contains($eventName, $term) || str_contains($desc, $term)) {
                return true;
            }
        }

        return $certType === 'ingles' || $certType === 'basic_english';
    }

    /**
     * Permanent institutional verification URL.
     */
    public function getValidationUrlAttribute(): string
    {
        $code = trim((string) ($this->code ?: $this->certificate_code ?: ''));

        return $code !== ''
            ? url('/validar-certificado?code='.$code)
            : url('/validar-certificado');
    }

    /**
     * Permanent institutional verification URL (alias for validation_url).
     */
    public function getVerificationUrlAttribute(): string
    {
        return $this->validation_url;
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

    /**
     * Resolved modules, contents, credits and grades for the Basic English certificate reverso.
     */
    public function getEnglishModulesDataAttribute(): array
    {
        $defaultModules = [
            [
                'name' => 'MODULO: I',
                'credits' => 4,
                'score_num' => 14,
                'score_text' => 'Catorce',
                'year' => $this->issue_date ? Carbon::parse($this->issue_date)->format('d/m/Y') : '29/12/2025',
                'observation' => '',
                'contents' => [
                    'Greatings and farewells',
                    'The numbers 0 - 1,000',
                    'The numbers 1,000 - 999,999.',
                    'The time ( What time is it? )',
                    'Days and Celebres detes.',
                    'The alphabet',
                    'The alphabet ( Spelling.',
                    'Verb to be in present time in A.N.I Form.',
                    'Verb to be in past and futuro A.N.I.form.',
                    'Possessive Adjectives.',
                    'The adjectives',
                    'There is and there are A.N.I form.',
                ],
            ],
            [
                'name' => 'MODULO: II',
                'credits' => 3,
                'score_num' => 14,
                'score_text' => 'Catorce',
                'year' => $this->issue_date ? Carbon::parse($this->issue_date)->format('d/m/Y') : '29/12/2025',
                'observation' => '',
                'contents' => [
                    'Demostrative Pronuons A.N.I form',
                    'Regular and Irregular verbs',
                    'Kinds Preposition of places',
                    'Kind Preposition of Time',
                    'Countable and uncountable nouns',
                    'How much and Many',
                    'Wh- quuestions',
                    'Comparative and Superlative',
                    'Simple Present sentence',
                    'Simple past Sentence',
                ],
            ],
        ];

        if (! $this->course || $this->course->modules->isEmpty()) {
            return $defaultModules;
        }

        $result = [];
        $words = [
            0 => 'Cero', 1 => 'Uno', 2 => 'Dos', 3 => 'Tres', 4 => 'Cuatro',
            5 => 'Cinco', 6 => 'Seis', 7 => 'Siete', 8 => 'Ocho', 9 => 'Nueve',
            10 => 'Diez', 11 => 'Once', 12 => 'Doce', 13 => 'Trece', 14 => 'Catorce',
            15 => 'Quince', 16 => 'Dieciséis', 17 => 'Diecisiete', 18 => 'Dieciocho',
            19 => 'Diecinueve', 20 => 'Veinte',
        ];

        foreach ($this->course->modules as $index => $module) {
            $detail = $this->details->firstWhere('module_id', $module->id);
            $scoreNum = $detail?->score !== null && $detail?->score !== '' ? (int) $detail->score : ($defaultModules[$index]['score_num'] ?? 14);
            $scoreText = $words[$scoreNum] ?? ($defaultModules[$index]['score_text'] ?? 'Catorce');
            $contents = $module->itineraries->pluck('name')->all();

            if (empty($contents) && isset($defaultModules[$index])) {
                $contents = $defaultModules[$index]['contents'];
            }

            $result[] = [
                'name' => mb_strtoupper($module->name, 'UTF-8'),
                'credits' => $module->credits ?: ($defaultModules[$index]['credits'] ?? 3),
                'score_num' => $scoreNum,
                'score_text' => $scoreText,
                'year' => $this->issue_date ? Carbon::parse($this->issue_date)->format('d/m/Y') : '29/12/2025',
                'observation' => '',
                'contents' => $contents,
            ];
        }

        return ! empty($result) ? $result : $defaultModules;
    }
}
