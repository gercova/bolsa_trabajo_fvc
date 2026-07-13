<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admission extends Model
{
    protected $table        = 'admission';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'activity',
        'period',
        'total_vacancies',
        'observation',
        'exam_date',
        'inscription_start_date',
        'inscription_end_date',
        'url_pdf',
        'price',
        'type',
        'process',
        'area_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function area(): BelongsTo {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function admissionDetail(): HasMany {
        return $this->hasMany(AdmissionDetail::class, 'admission_id', 'id');
    }
}
