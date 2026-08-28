<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'credits',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function itineraries(): HasMany {
        return $this->hasMany(Itinerary::class, 'module_id', 'id');
    }

    public function certificateDetails(): HasMany {
        return $this->hasMany(CertificateDetail::class, 'module_id', 'id');
    }
}
