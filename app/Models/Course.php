<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'modality',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function modules(): HasMany {
        return $this->hasMany(Module::class, 'course_id', 'id');
    }

    public function itineraries(): HasMany {
        return $this->hasMany(Itinerary::class, 'course_id', 'id');
    }

    public function certificates(): HasMany {
        return $this->hasMany(Certificate::class, 'course_id', 'id');
    }
}
