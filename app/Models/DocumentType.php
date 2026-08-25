<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    protected $table = 'document_type';
    protected $fillable = [
        'name',
        'abreviation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function studentRecords(): HasMany {
        return $this->hasMany(StudentRecord::class);
    }
}
