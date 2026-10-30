<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Admission extends Model
{
    protected $table = 'admission';

    protected $primaryKey = 'id';

    protected $appends = [
        'pdf_url',
        'results_pdf_url',
    ];

    protected $fillable = [
        'activity',
        'period',
        'total_vacancies',
        'observation',
        'exam_date',
        'inscription_start_date',
        'inscription_end_date',
        'url_pdf',
        'results_url_pdf',
        'price',
        'monthly_fee',
        'tuition_fee',
        'duration',
        'indications',
        'type',
        'process',
        'area_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'monthly_fee' => 'decimal:2',
        'tuition_fee' => 'decimal:2',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function admissionDetail(): HasMany
    {
        return $this->hasMany(AdmissionDetail::class, 'admission_id', 'id');
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (! $this->url_pdf) {
            return null;
        }

        if (Str::startsWith($this->url_pdf, ['http://', 'https://'])) {
            return $this->url_pdf;
        }

        return Storage::url($this->url_pdf);
    }

    public function getResultsPdfUrlAttribute(): ?string
    {
        if (! $this->results_url_pdf) {
            return null;
        }

        if (Str::startsWith($this->results_url_pdf, ['http://', 'https://'])) {
            return $this->results_url_pdf;
        }

        return Storage::url($this->results_url_pdf);
    }
}
